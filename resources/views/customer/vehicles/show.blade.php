@extends('layouts.app')
@section('title', $vehicle->name)

@section('content')
<a href="{{ route('customer.vehicles.index') }}" class="text-sm text-muted hover:text-brand mb-4 inline-block" data-testid="back-link">
    Back to vehicles
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 rz-card overflow-hidden">
        <div class="aspect-video bg-tint">
            @if($vehicle->image_url)
                <img src="{{ $vehicle->image_url }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-muted">No photo available</div>
            @endif
        </div>
        <div class="p-6">
            <div class="flex items-start justify-between mb-4 flex-wrap gap-2">
                <div>
                    <h1 class="text-3xl font-semibold">{{ $vehicle->name }}</h1>
                    <p class="text-muted">{{ $vehicle->make }} {{ $vehicle->model }} · {{ $vehicle->year }}</p>
                </div>
                <span class="rz-plate text-sm" data-testid="vehicle-reg">
                    Reg No: {{ $vehicle->registration_no }}
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 border-y border-line py-4 mb-5 text-sm">
                <div><div class="text-xs text-muted">Seats</div><div class="font-semibold">{{ $vehicle->seats }}</div></div>
                <div><div class="text-xs text-muted">Transmission</div><div class="font-semibold">{{ ucfirst($vehicle->transmission) }}</div></div>
                <div><div class="text-xs text-muted">Fuel</div><div class="font-semibold">{{ ucfirst($vehicle->fuel_type) }}</div></div>
                <div><div class="text-xs text-muted">Category</div><div class="font-semibold">{{ $vehicle->category?->name ?? '-' }}</div></div>
            </div>

            <h2 class="font-semibold mb-2">About this vehicle</h2>
            <p class="text-ink text-sm leading-relaxed max-w-prose">{{ $vehicle->description ?: 'No description provided.' }}</p>
        </div>
    </div>

    <aside class="rz-card p-6 h-fit sticky top-24">
        <div class="text-sm text-muted">Daily rate</div>
        <div class="text-3xl font-semibold text-brand-dark mb-5">FJD {{ number_format($vehicle->daily_rate, 2) }}</div>

        @auth
            @if(auth()->user()->isCustomer())
                @php $isFav = auth()->user()->hasFavorited($vehicle->registration_no); @endphp
                <form method="POST" action="{{ route('customer.favorites.toggle', $vehicle->registration_no) }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full rz-btn-ghost rounded-md py-2 text-sm" data-testid="fav-detail-{{ $vehicle->registration_no }}">
                        @if($isFav)
                            Saved to favorites
                        @else
                            Save to favorites
                        @endif
                    </button>
                </form>
                <a href="{{ route('customer.bookings.create', $vehicle->registration_no) }}" class="rz-btn w-full rounded-md py-3 block text-center mt-2" data-testid="book-now">
                    Book this vehicle
                </a>
            @else
                <p class="text-xs text-muted">Admins cannot book vehicles.</p>
            @endif
        @else
            <a href="{{ route('login') }}" class="rz-btn w-full rounded-md py-3 block text-center" data-testid="book-now-login">
                Log in to book
            </a>
        @endauth

        <div class="mt-6 border-t border-line pt-4 text-sm text-muted">
                        Overlapping bookings are prevented at the database level — your dates are reserved only for you.
        </div>
    </aside>
</div>
@endsection
