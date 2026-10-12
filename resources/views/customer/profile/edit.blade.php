@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<h1 class="text-3xl font-bold mb-6">My Profile</h1>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="rz-card p-6">
        <h2 class="text-lg font-bold mb-4">Personal details</h2>
        <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-3">
            @csrf @method('PUT')
            <div><label class="text-xs text-gray-400">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="profile-name"></div>
            <div><label class="text-xs text-gray-400">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="profile-email"></div>
            <div><label class="text-xs text-gray-400">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="profile-phone"></div>
            <div><label class="text-xs text-gray-400">Address</label>
                <input type="text" name="address" value="{{ old('address', $user->address) }}" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="profile-address"></div>
            <div><label class="text-xs text-gray-400">Driver license</label>
                <input type="text" name="driver_license" value="{{ old('driver_license', $user->driver_license) }}" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="profile-license"></div>
            <button type="submit" class="rz-btn rounded-md py-2 px-4" data-testid="profile-save">Save changes</button>
        </form>
    </div>

   <div class="rz-card p-6">
    <h2 class="text-lg font-bold mb-4">Change password</h2>

    <form method="POST" action="{{ route('customer.profile.password') }}" class="space-y-3">
        @csrf
        @method('PUT')

        <div>
            <label class="text-xs text-gray-400">Current password</label>
            <input type="password"
                   name="current_password"
                   required
                   class="rz-input w-full rounded-md px-3 py-2 mt-1"
                   data-testid="password-current">
        </div>

        <div>
            <label class="text-xs text-gray-400">New password</label>
            <input type="password"
                   name="password"
                   required
                   class="rz-input w-full rounded-md px-3 py-2 mt-1"
                   data-testid="password-new">

            <p class="text-xs text-gray-400 mt-1">
                Password must be at least 8 characters and include uppercase,
                lowercase, a number, and a special character.
            </p>
        </div>

        <div>
            <label class="text-xs text-gray-400">Confirm new password</label>
            <input type="password"
                   name="password_confirmation"
                   required
                   class="rz-input w-full rounded-md px-3 py-2 mt-1"
                   data-testid="password-confirm">
        </div>

        <button type="submit"
                class="rz-btn rounded-md py-2 px-4"
                data-testid="password-save">
            Update password
        </button>
    </form>
</div>
@endsection
