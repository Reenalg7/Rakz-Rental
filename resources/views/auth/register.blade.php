@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="rz-card p-8">
        <h1 class="text-2xl font-bold mb-1">Create your account</h1>
        <p class="text-sm text-gray-400 mb-6">Register as a customer to start booking vehicles.</p>

        <form method="POST" action="{{ route('register') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @csrf
            <div class="sm:col-span-2">
                <label class="text-sm text-gray-300 block mb-1">Full name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="rz-input w-full rounded-md px-3 py-2" data-testid="register-name">
            </div>
            <div class="sm:col-span-2">
                <label class="text-sm text-gray-300 block mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="rz-input w-full rounded-md px-3 py-2" data-testid="register-email">
            </div>
            <div>
                <label class="text-sm text-gray-300 block mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="rz-input w-full rounded-md px-3 py-2" data-testid="register-phone">
            </div>
            
            <div class="sm:col-span-2">
                <label class="text-sm text-gray-300 block mb-1">Address</label>
                <input type="text" name="address" value="{{ old('address') }}" class="rz-input w-full rounded-md px-3 py-2" data-testid="register-address">
            </div>
            <div>
                <label class="text-sm text-gray-300 block mb-1">Driver license</label>
                <input type="text" name="driver_license" value="{{ old('driver_license') }}" class="rz-input w-full rounded-md px-3 py-2" data-testid="register-license">
            </div>
            <div></div>
            <div>
                <label class="text-sm text-gray-300 block mb-1">Password</label>
                <input type="password" name="password" required class="rz-input w-full rounded-md px-3 py-2" data-testid="register-password">
            </div>
            <div>
                <label class="text-sm text-gray-300 block mb-1">Confirm password</label>
                <input type="password" name="password_confirmation" required class="rz-input w-full rounded-md px-3 py-2" data-testid="register-password-confirmation">
            </div>

            <div class="sm:col-span-2">
                <button type="submit" class="rz-btn w-full rounded-md py-2.5" data-testid="register-submit">
                    Create account <i class="fa-solid fa-arrow-right ml-1"></i>
                </button>
            </div>
        </form>

        <p class="text-sm text-gray-400 mt-6 text-center">
            Already have an account? <a href="{{ route('login') }}" class="rz-primary font-medium" data-testid="link-login">Log in</a>
        </p>
    </div>
</div>
@endsection
