<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureUserIsCustomer middleware
 * ---------------------------------------------------------------------------
 * Protects customer-only routes (bookings, profile, etc.).
 */
class EnsureUserIsCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to continue.');
        }

        if (Auth::user()->role !== 'customer') {
            abort(403, 'This section is only available to customers.');
        }

        return $next($request);
    }
}
