<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureUserIsAdmin middleware
 * ---------------------------------------------------------------------------
 * Protects admin routes. If the authenticated user does not have the
 * `admin` role on the users table, we redirect them with an error.
 * This gives us server-side role-based authorization (SRS requirement).
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to continue.');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'You do not have permission to access the admin area.');
        }

        return $next($request);
    }
}
