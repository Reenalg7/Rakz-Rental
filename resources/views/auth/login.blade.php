@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto">
    <div class="rz-card p-8">
        <h1 class="text-2xl font-semibold mb-1">Welcome back</h1>
        <p class="text-sm text-muted mb-6">Log in to your Rakz Rental account.</p>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="rz-label mb-1" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="rz-input w-full rounded-md px-3 py-2" data-testid="login-email">
            </div>
            <div>
                <label class="rz-label mb-1" for="password">Password</label>
                <input id="password" type="password" name="password" required
                    class="rz-input w-full rounded-md px-3 py-2" data-testid="login-password">
            </div>
            <label class="flex items-center gap-2 text-sm text-muted">
                <input type="checkbox" name="remember" class="accent-[#005691]"> Remember me
            </label>
            <button type="submit" class="rz-btn w-full rounded-md py-2.5" data-testid="login-submit">
                Log in
            </button>
        </form>

        <p class="text-sm text-muted mt-6 text-center">
            New to Rakz Rental? <a href="{{ route('register') }}" class="rz-link font-medium" data-testid="link-register">Create an account</a>
        </p>

        <div class="mt-6 border-t border-line pt-4 text-xs text-muted">
            <p class="font-semibold text-ink mb-1">Demo credentials</p>
            <p>Admin: admin@rakzrental.com / admin123</p>
            <p>Customer: customer@rakzrental.com / customer123</p>
        </div>
    </div>
</div>
@endsection
