<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
}
