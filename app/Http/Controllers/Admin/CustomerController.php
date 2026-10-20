<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Throwable;

/**
 * Admin Customer Management.
 * ---------------------------------------------------------------------------
 * Customers are rows in `users` with role = 'customer'. Only administrators
 * can reach this controller: the routes sit inside the `auth` + `admin`
 * middleware group (see routes/web.php), so a guest is redirected to the login
 * page and a customer receives a 403.
 *
 * Rules enforced here:
 *  - The role is NEVER read from the request. Created accounts are always
 *    'customer', and admin accounts cannot be viewed/edited/deleted through
 *    these routes (they 404).
 *  - A customer that has any booking on record cannot be deleted. The
 *    `bookings.user_id` FK cascades, so deleting would silently wipe the
 *    customer's bookings and payment (billing) history and break revenue
 *    reports / invoices. This mirrors how vehicles are retired, not deleted.
 */
class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $q = User::where('role', 'customer')->withCount('bookings');

        if ($keyword = trim((string) $request->input('q'))) {
            $q->where(function ($qq) use ($keyword) {
                $qq->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        $customers = $q->latest()->paginate(15)->withQueryString();
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer): View
    {
        abort_if($customer->role !== 'customer', 404);
        $customer->load(['bookings.vehicle', 'bookings.billing']);
        return view('admin.customers.show', compact('customer'));
    }

    /** Blank "Add customer" form. */
    public function create(): View
    {
        return view('admin.customers.create');
    }

    /** Create a new customer account on behalf of a walk-in / phone customer. */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules(), $this->messages());

        try {
            $customer = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'nic' => $data['nic'] ?? null,
                'driver_license' => $data['driver_license'] ?? null,
                'password' => Hash::make($data['password']),
                'role' => 'customer', // forced, never taken from input
            ]);
        } catch (QueryException $e) {
            return $this->databaseFailure($e, 'create the customer', true);
        }

        AuditLogger::log(
            'customer.create',
            "Customer {$customer->name} ({$customer->email}) created by admin",
            User::class,
            (string) $customer->id
        );

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', "Customer {$customer->name} added.");
    }

    public function edit(User $customer): View
    {
        abort_if($customer->role !== 'customer', 404);
        return view('admin.customers.edit', compact('customer'));
    }

    /** Update a customer's details. Password is optional (blank = keep current). */
    public function update(Request $request, User $customer): RedirectResponse
    {
        abort_if($customer->role !== 'customer', 404);

        $data = $request->validate($this->rules($customer), $this->messages());

        $customer->fill(Arr::only($data, ['name', 'email', 'phone', 'address', 'nic', 'driver_license']));

        $passwordReset = !empty($data['password']);
        if ($passwordReset) {
            $customer->password = Hash::make($data['password']);
        }

        $changed = array_keys($customer->getDirty());
        if (empty($changed)) {
            return redirect()->route('admin.customers.show', $customer)
                ->with('success', 'No changes were made.');
        }

        try {
            $customer->save();
        } catch (QueryException $e) {
            return $this->databaseFailure($e, 'update the customer', true);
        }

        // Log which fields changed, never the password value itself.
        $fields = collect($changed)
            ->map(fn ($f) => $f === 'password' ? 'password (reset)' : $f)
            ->implode(', ');
        AuditLogger::log(
            'customer.update',
            "Customer {$customer->name} (#{$customer->id}) updated: {$fields}",
            User::class,
            (string) $customer->id
        );

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', "Customer {$customer->name} updated.");
    }

    /**
     * Delete a customer — only when they have no bookings at all.
     * The check and the delete run in one transaction (with a row lock on
     * MySQL) so a booking created at the same moment can't be cascade-deleted.
     */
    public function destroy(User $customer): RedirectResponse
    {
        abort_if($customer->role !== 'customer', 404);

        try {
            $outcome = DB::transaction(function () use ($customer) {
                $locked = User::whereKey($customer->id)
                    ->where('role', 'customer')
                    ->lockForUpdate()
                    ->first();

                if (!$locked) {
                    return ['deleted' => false, 'missing' => true];
                }

                $total = $locked->bookings()->count();
                if ($total > 0) {
                    $active = $locked->bookings()->whereIn('status', ['pending', 'confirmed'])->count();
                    return ['deleted' => false, 'total' => $total, 'active' => $active];
                }

                $locked->delete();
                return ['deleted' => true];
            });
        } catch (Throwable $e) {
            Log::error('Customer delete failed', ['customer_id' => $customer->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Could not delete the customer because of a database error. Nothing was changed.');
        }

        if (!empty($outcome['missing'])) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'That customer no longer exists.');
        }

        if (!$outcome['deleted']) {
            AuditLogger::log(
                'customer.delete.blocked',
                "Delete blocked for customer {$customer->name} (#{$customer->id}): {$outcome['total']} booking(s) on record",
                User::class,
                (string) $customer->id
            );

            $activeNote = $outcome['active'] > 0
                ? " ({$outcome['active']} still pending/confirmed)"
                : '';

            return back()->with('error',
                "Cannot delete {$customer->name}: they have {$outcome['total']} booking(s) on record{$activeNote}. "
                .'Customers with booking or payment history are kept so records, invoices and revenue reports stay accurate.');
        }

        AuditLogger::log(
            'customer.delete',
            "Customer {$customer->name} ({$customer->email}) deleted by admin",
            User::class,
            (string) $customer->id
        );

        return redirect()->route('admin.customers.index')
            ->with('success', "Customer {$customer->name} deleted.");
    }

    /* ------------------------------------------------------------------ */

    /** Validation rules shared by create and update. */
    private function rules(?User $customer = null): array
    {
        $emailUnique = Rule::unique('users', 'email');
        if ($customer) {
            $emailUnique->ignore($customer->id);
        }

        // Same strength policy as self-registration (RegisterController).
        $strong = Password::min(8)->mixedCase()->numbers()->symbols();

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', $emailUnique],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+().\-\s]{6,30}$/'],
            'address' => ['nullable', 'string', 'max:255'],
            'nic' => ['nullable', 'string', 'max:30'],
            'driver_license' => ['nullable', 'string', 'max:30'],
            'password' => $customer
                ? ['nullable', 'required_with:password_confirmation', 'confirmed', $strong]
                : ['required', 'confirmed', $strong],
        ];
    }

    private function messages(): array
    {
        return [
            'email.unique' => 'That email address is already registered to another account.',
            'phone.regex' => 'Phone may only contain digits, spaces and + - ( ) . (6–30 characters).',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.required_with' => 'Enter the new password as well as its confirmation.',
        ];
    }

    /**
     * Turn a DB failure into a friendly message instead of a 500 page.
     * A duplicate email that slipped past validation (two admins saving at
     * the same moment) is reported against the email field.
     */
    private function databaseFailure(QueryException $e, string $action, bool $withInput): RedirectResponse
    {
        $message = $e->getMessage();
        $isDuplicateEmail = ($e->errorInfo[0] ?? null) === '23000'
            && (str_contains($message, 'users_email_unique') || str_contains($message, 'users.email'));

        if ($isDuplicateEmail) {
            $back = back()->withErrors(['email' => 'That email address is already registered to another account.']);
        } else {
            Log::error("Customer {$action} failed", ['error' => $message]);
            $back = back()->with('error', "Could not {$action} because of a database error. Please try again.");
        }

        return $withInput ? $back->withInput($this->safeInput()) : $back;
    }

    /** Old input to repopulate the form — never passwords. */
    private function safeInput(): array
    {
        return request()->except(['password', 'password_confirmation']);
    }
}
