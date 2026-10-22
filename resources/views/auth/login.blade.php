@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto">
    <div class="rz-card p-8">
        <h1 class="text-2xl font-bold mb-1">Welcome back</h1>
        <p class="text-sm text-gray-400 mb-6">Log in to your Rakz Rental account.</p>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm text-gray-300 block mb-1" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="rz-input w-full rounded-md px-3 py-2" data-testid="login-email">
            </div>
            <div>
                <label class="text-sm text-gray-300 block mb-1" for="password">Password</label>
                <input id="password" type="password" name="password" required
                    class="rz-input w-full rounded-md px-3 py-2" data-testid="login-password">
            </div>
            
            <button type="submit" class="rz-btn w-full rounded-md py-2.5" data-testid="login-submit">
                Log in <i class="fa-solid fa-arrow-right ml-1"></i>
            </button>
        </form>

        <p class="text-sm text-gray-400 mt-6 text-center">
            New to Rakz Rental? <a href="{{ route('register') }}" class="rz-primary font-medium" data-testid="link-register">Create an account</a>
        </p>
        
    </div>
</div>
@endsection
