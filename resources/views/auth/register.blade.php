
@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="rz-card p-8">
        <h1 class="text-2xl font-bold mb-1">Create your account</h1>
        <p class="text-sm text-gray-400 mb-6">
            Register as a customer to start booking vehicles.
        </p>

        <form method="POST" action="{{ route('register') }}"
              class="grid grid-cols-1 sm:grid-cols-2 gap-4"
              novalidate>
            @csrf

            <!-- Full name -->
            <div class="sm:col-span-2">
                <label for="register-name" class="text-sm text-gray-300 block mb-1">
                    Full name
                </label>
                <input
                    id="register-name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autocomplete="name"
                    class="rz-input w-full rounded-md px-3 py-2 @error('name') border-red-500 @enderror"
                    data-testid="register-name"
                    aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                    aria-describedby="name-error"
                >
                @error('name')
                    <p id="name-error" class="text-red-400 text-sm mt-1" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="sm:col-span-2">
                <label for="register-email" class="text-sm text-gray-300 block mb-1">
                    Email
                </label>
                <input
                    id="register-email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    class="rz-input w-full rounded-md px-3 py-2 @error('email') border-red-500 @enderror"
                    data-testid="register-email"
                    aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                    aria-describedby="email-error"
                >
                @error('email')
                    <p id="email-error" class="text-red-400 text-sm mt-1" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="register-phone" class="text-sm text-gray-300 block mb-1">
                    Phone
                </label>
                <input
                    id="register-phone"
                    type="text"
                    name="phone"
                    value="{{ old('phone') }}"
                    autocomplete="tel"
                    class="rz-input w-full rounded-md px-3 py-2 @error('phone') border-red-500 @enderror"
                    data-testid="register-phone"
                    aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                    aria-describedby="phone-error"
                >
                @error('phone')
                    <p id="phone-error" class="text-red-400 text-sm mt-1" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <!-- Driver license -->
            <div>
                <label for="register-license" class="text-sm text-gray-300 block mb-1">
                    Driver license
                </label>
                <input
                    id="register-license"
                    type="text"
                    name="driver_license"
                    value="{{ old('driver_license') }}"
                    class="rz-input w-full rounded-md px-3 py-2 @error('driver_license') border-red-500 @enderror"
                    data-testid="register-license"
                    aria-invalid="{{ $errors->has('driver_license') ? 'true' : 'false' }}"
                    aria-describedby="license-error"
                >
                @error('driver_license')
                    <p id="license-error" class="text-red-400 text-sm mt-1" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address -->
            <div class="sm:col-span-2">
                <label for="register-address" class="text-sm text-gray-300 block mb-1">
                    Address
                </label>
                <input
                    id="register-address"
                    type="text"
                    name="address"
                    value="{{ old('address') }}"
                    autocomplete="street-address"
                    class="rz-input w-full rounded-md px-3 py-2 @error('address') border-red-500 @enderror"
                    data-testid="register-address"
                    aria-invalid="{{ $errors->has('address') ? 'true' : 'false' }}"
                    aria-describedby="address-error"
                >
                @error('address')
                    <p id="address-error" class="text-red-400 text-sm mt-1" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="register-password" class="text-sm text-gray-300 block mb-1">
                    Password
                </label>
                <div class="relative">
                    <input
                        id="register-password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="rz-input w-full rounded-md px-3 py-2 pr-10 @error('password') border-red-500 @enderror"
                        data-testid="register-password"
                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                        aria-describedby="password-error password-hint"
                    >
                    <button
                        type="button"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white"
                        onclick="togglePassword('register-password', this)"
                        aria-label="Show password"
                        aria-pressed="false"
                    >
                        <i class="fa-regular fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
                <p id="password-hint" class="text-xs text-gray-400 mt-1">
                    Use at least 8 characters.
                    The password must contain at least one uppercase letter.
                     The password must contain at least one symbol.
                     The password must contain at least one number.
                </p>
                @error('password')
                    <p id="password-error" class="text-red-400 text-sm mt-1" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm password -->
            <div>
                <label for="register-password-confirmation" class="text-sm text-gray-300 block mb-1">
                    Confirm password
                </label>
                <div class="relative">
                    <input
                        id="register-password-confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="rz-input w-full rounded-md px-3 py-2 pr-10"
                        data-testid="register-password-confirmation"
                    >
                    <button
                        type="button"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white"
                        onclick="togglePassword('register-password-confirmation', this)"
                        aria-label="Show confirm password"
                        aria-pressed="false"
                    >
                        <i class="fa-regular fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="text-red-400 text-sm mt-1" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <!-- Other validation errors -->
            @if($errors->has('g-recaptcha-response'))
                <div class="sm:col-span-2 text-red-400 text-sm" role="alert">
                    {{ $errors->first('g-recaptcha-response') }}
                </div>
            @endif

            <div class="sm:col-span-2">
                <button type="submit" class="rz-btn w-full rounded-md py-2.5"
                        data-testid="register-submit">
                    Create account <i class="fa-solid fa-arrow-right ml-1"></i>
                </button>
            </div>
        </form>

        <p class="text-sm text-gray-400 mt-6 text-center">
            Already have an account?
            <a href="{{ route('login') }}" class="rz-primary font-medium"
               data-testid="link-login">Log in</a>
        </p>
    </div>
</div>

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        const showing = input.type === 'password';

        input.type = showing ? 'text' : 'password';
        icon.classList.toggle('fa-eye', !showing);
        icon.classList.toggle('fa-eye-slash', showing);

        button.setAttribute('aria-pressed', String(showing));
        button.setAttribute(
            'aria-label',
            showing ? 'Hide password' : 'Show password'
        );
    }
</script>
@endsection
