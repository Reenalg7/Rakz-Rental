@extends('layouts.app')
@section('title', $vehicle->name)

@section('content')
<a href="{{ route('customer.vehicles.index') }}" class="text-sm text-gray-400 hover:text-white mb-4 inline-block" data-testid="back-link">
    <i class="fa-solid fa-arrow-left mr-1"></i>Back to vehicles
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 rz-card overflow-hidden">
        
    <div class="aspect-video bg-black/40 overflow-hidden">
    @php
        $vehicleImage = 'images/Rakz car/' . $vehicle->registration_no . '.jpg';
    @endphp

    @if(file_exists(public_path($vehicleImage)))
        <img
            src="{{ asset($vehicleImage) }}"
            alt="{{ $vehicle->name }} - {{ $vehicle->registration_no }}"
            class="w-full h-full object-cover"
        >
    @else
        <div class="w-full h-full flex items-center justify-center text-gray-600">
            <i class="fa-solid fa-car text-7xl"></i>
        </div>
    @endif
    </div>

        <div class="p-6">
            <div class="flex items-start justify-between mb-4 flex-wrap gap-2">
                <div>
                    <h1 class="text-3xl font-bold">{{ $vehicle->name }}</h1>
                    <p class="text-gray-400">{{ $vehicle->make }} {{ $vehicle->model }} · {{ $vehicle->year }}</p>
                </div>
                <span class="rz-badge bg-black/40 border border-white/10 rz-primary font-mono" data-testid="vehicle-reg">
                    Reg No: {{ $vehicle->registration_no }}
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 border-y border-white/10 py-4 mb-4 text-sm">
                <div><div class="text-xs text-gray-400">Seats</div><div class="font-semibold">{{ $vehicle->seats }}</div></div>
                <div><div class="text-xs text-gray-400">Transmission</div><div class="font-semibold">{{ ucfirst($vehicle->transmission) }}</div></div>
                <div><div class="text-xs text-gray-400">Fuel</div><div class="font-semibold">{{ ucfirst($vehicle->fuel_type) }}</div></div>
                <div><div class="text-xs text-gray-400">Category</div><div class="font-semibold">{{ $vehicle->category?->name ?? '-' }}</div></div>
            </div>

            <h3 class="font-semibold mb-2">About this vehicle</h3>
            <p class="text-gray-300 text-sm leading-relaxed">{{ $vehicle->description ?: 'No description provided.' }}</p>
        </div>
    </div>

    <aside class="rz-card p-6 h-fit sticky top-24">
        <div class="text-sm text-gray-400">Daily rate</div>
        <div class="text-3xl font-bold rz-primary mb-4">FJD {{ number_format($vehicle->daily_rate, 2) }}</div>

        @auth
            @if(auth()->user()->isCustomer())
                @php $isFav = auth()->user()->hasFavorited($vehicle->registration_no); @endphp
                <form method="POST" action="{{ route('customer.favorites.toggle', $vehicle->registration_no) }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full rz-btn-ghost rounded-md py-2 text-sm" data-testid="fav-detail-{{ $vehicle->registration_no }}">
                        @if($isFav)
                            <i class="fa-solid fa-heart rz-primary mr-1"></i>Saved to favorites
                        @else
                            <i class="fa-regular fa-heart mr-1"></i>Save to favorites
                        @endif
                    </button>
                </form>
                <a href="{{ route('customer.bookings.create', $vehicle->registration_no) }}" class="rz-btn w-full rounded-md py-3 block text-center mt-2" data-testid="book-now">
                    <i class="fa-regular fa-calendar-plus mr-1"></i>Book this vehicle
                </a>
            @else
                <p class="text-xs text-gray-400">Admins cannot book vehicles.</p>
            @endif
        @else
            <a href="{{ route('login') }}" class="rz-btn w-full rounded-md py-3 block text-center" data-testid="book-now-login">
                Log in to book
            </a>
        @endauth

        <div class="mt-6 border-t border-white/10 pt-4 text-xs text-gray-400">
            <i class="fa-solid fa-shield-halved rz-primary mr-1"></i>
            Overlapping bookings are prevented at the database level — your dates are reserved only for you.
        </div>
    </aside>
</div>
@endsection
