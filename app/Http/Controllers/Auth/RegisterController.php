<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
    $data = $request->validate([
        'name' => ['required', 'string', 'max:120'],
        'email' => ['required', 'email', 'max:160', 'unique:users,email'],
        'phone' => ['nullable', 'string', 'max:30'],
        'address' => ['nullable', 'string', 'max:255'],
        'driver_license' => ['nullable', 'string', 'max:30'],
        'password' => [
            'required',
            'confirmed',
            Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols(),
        ],
    ]);

    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'] ?? null,
        'address' => $data['address'] ?? null,
        'driver_license' => $data['driver_license'] ?? null,
        'password' => Hash::make($data['password']),
        'role' => 'customer',
    ]);

    Auth::login($user);

    AuditLogger::log(
        'register',
        "New customer registered: {$user->name} ({$user->email})",
        User::class,
        (string) $user->id
    );

    return redirect()->route('customer.vehicles.index')
        ->with('success', 'Welcome to Rakz Rental, '.$user->name.'!');
    }
}
