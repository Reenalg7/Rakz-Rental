@php
    $isEdit = isset($vehicle);
    $v = $vehicle ?? null;
@endphp

<form method="POST"
    action="{{ $isEdit ? route('admin.vehicles.update', $v->registration_no) : route('admin.vehicles.store') }}"
    class="rz-card p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="md:col-span-2">
        <label class="rz-label">Registration number</label>
        <input type="text" name="registration_no" value="{{ old('registration_no', $v?->registration_no) }}"
            {{ $isEdit ? 'readonly' : 'required' }}
            placeholder="LR3180" class="rz-input w-full rounded-md px-3 py-2 mt-1 uppercase font-mono"
            data-testid="vehicle-regno">
        <p class="text-xs text-muted mt-1">The vehicle plate is the primary key and cannot be changed after creation.</p>
    </div>

    <div><label class="rz-label">Name</label>
        <input type="text" name="name" value="{{ old('name', $v?->name) }}" required class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="vehicle-name"></div>
    <div><label class="rz-label">Category</label>
        <select name="category_id" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="vehicle-category">
            <option value="">None</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('category_id', $v?->category_id) == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select></div>

    <div><label class="rz-label">Make</label>
        <input type="text" name="make" value="{{ old('make', $v?->make) }}" required class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="vehicle-make"></div>
    <div><label class="rz-label">Model</label>
        <input type="text" name="model" value="{{ old('model', $v?->model) }}" required class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="vehicle-model"></div>

    <div><label class="rz-label">Year</label>
        <input type="number" name="year" value="{{ old('year', $v?->year ?? date('Y')) }}" required class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="vehicle-year"></div>
    <div><label class="rz-label">Seats</label>
        <input type="number" name="seats" value="{{ old('seats', $v?->seats ?? 5) }}" required class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="vehicle-seats"></div>

    <div><label class="rz-label">Daily rate (FJD)</label>
        <input type="number" step="0.01" name="daily_rate" value="{{ old('daily_rate', $v?->daily_rate) }}" required class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="vehicle-rate"></div>
    <div><label class="rz-label">Transmission</label>
        <select name="transmission" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="vehicle-transmission">
            @foreach(['automatic','manual'] as $t)
                <option value="{{ $t }}" @selected(old('transmission', $v?->transmission) === $t)>{{ ucfirst($t) }}</option>
            @endforeach
        </select></div>

    <div><label class="rz-label">Fuel type</label>
        <input type="text" name="fuel_type" value="{{ old('fuel_type', $v?->fuel_type ?? 'petrol') }}" required class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="vehicle-fuel"></div>
    <div><label class="rz-label">Status</label>
        <select name="status" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="vehicle-status">
            @foreach(['available','maintenance','retired'] as $s)
                <option value="{{ $s }}" @selected(old('status', $v?->status ?? 'available') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select></div>

    <div class="md:col-span-2"><label class="rz-label">Image URL</label>
        <input type="url" name="image_url" value="{{ old('image_url', $v?->image_url) }}" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="vehicle-image"></div>

    <div class="md:col-span-2"><label class="rz-label">Description</label>
        <textarea name="description" rows="3" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="vehicle-description">{{ old('description', $v?->description) }}</textarea></div>

    <div class="md:col-span-2 flex gap-3">
        <button type="submit" class="rz-btn rounded-md px-5 py-2" data-testid="vehicle-save">
            {{ $isEdit ? 'Update' : 'Create' }} vehicle
        </button>
        <a href="{{ route('admin.vehicles.index') }}" class="rz-btn-ghost rounded-md px-5 py-2">Cancel</a>
    </div>
</form>
