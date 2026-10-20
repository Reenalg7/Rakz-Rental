@php
    $isEdit = isset($customer);
    $c = $customer ?? null;
@endphp

<form method="POST"
    action="{{ $isEdit ? route('admin.customers.update', $c) : route('admin.customers.store') }}"
    class="rz-card p-6 grid grid-cols-1 md:grid-cols-2 gap-4" autocomplete="off">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div>
        <label class="text-xs text-gray-400">Full name</label>
        <input type="text" name="name" value="{{ old('name', $c?->name) }}" required maxlength="120"
            class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="customer-name">
        @error('name')<p class="text-xs text-red-300 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="text-xs text-gray-400">Email</label>
        <input type="email" name="email" value="{{ old('email', $c?->email) }}" required maxlength="160"
            autocomplete="off" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="customer-email">
        @error('email')<p class="text-xs text-red-300 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-xs text-gray-400">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $c?->phone) }}" maxlength="30"
            placeholder="+679 700 0000" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="customer-phone">
        @error('phone')<p class="text-xs text-red-300 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="text-xs text-gray-400">Driver's license</label>
        <input type="text" name="driver_license" value="{{ old('driver_license', $c?->driver_license) }}" maxlength="30"
            class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="customer-license">
        @error('driver_license')<p class="text-xs text-red-300 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-xs text-gray-400">National ID (NIC)</label>
        <input type="text" name="nic" value="{{ old('nic', $c?->nic) }}" maxlength="30"
            class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="customer-nic">
        @error('nic')<p class="text-xs text-red-300 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="text-xs text-gray-400">Address</label>
        <input type="text" name="address" value="{{ old('address', $c?->address) }}" maxlength="255"
            class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="customer-address">
        @error('address')<p class="text-xs text-red-300 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-xs text-gray-400">{{ $isEdit ? 'New password (optional)' : 'Password' }}</label>
        <input type="password" name="password" {{ $isEdit ? '' : 'required' }} autocomplete="new-password"
            class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="customer-password">
        @error('password')<p class="text-xs text-red-300 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="text-xs text-gray-400">Confirm password</label>
        <input type="password" name="password_confirmation" {{ $isEdit ? '' : 'required' }} autocomplete="new-password"
            class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="customer-password-confirm">
    </div>
    <p class="text-xs text-gray-500 md:col-span-2 -mt-2">
        @if($isEdit)
            Leave both password fields blank to keep the customer's current password.
        @endif
        Passwords need at least 8 characters with upper &amp; lower case letters, a number and a symbol.
    </p>

    <div class="md:col-span-2 flex gap-3">
        <button type="submit" class="rz-btn rounded-md px-5 py-2" data-testid="customer-save">
            <i class="fa-solid fa-save mr-1"></i>{{ $isEdit ? 'Update' : 'Create' }} customer
        </button>
        <a href="{{ $isEdit ? route('admin.customers.show', $c) : route('admin.customers.index') }}"
           class="rz-btn-ghost rounded-md px-5 py-2" data-testid="customer-cancel">Cancel</a>
    </div>
</form>
