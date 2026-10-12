<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, (bool) $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            AuditLogger::log('login', "{$user->name} ({$user->role}) logged in");

            return redirect()->intended(
                $user->role === 'admin'
                    ? route('admin.dashboard')
                    : route('customer.vehicles.index')
            );
        }

        AuditLogger::log('login.failed', "Failed login for {$credentials['email']}");

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        if ($user = Auth::user()) {
            AuditLogger::log('logout', "{$user->name} ({$user->role}) logged out");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
