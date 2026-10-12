@extends('layouts.app')
@section('title', 'Home')

@section('content')
<!-- Hero -->
<section class="relative overflow-hidden rz-card p-8 md:p-14 mb-10">
    <div class="absolute inset-0 opacity-30"
         style="background-image: url('https://images.unsplash.com/photo-1489686995744-f47e995ffe61?w=1600'); background-size:cover; background-position:center;"></div>
    <div class="relative z-10 max-w-2xl">
        <span class="rz-badge bg-[color:var(--rz-primary)]/20 rz-primary mb-4"> Rakz Rental</span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-4">
            Drive your <span class="rz-primary">next journey</span><br>in confidence.
        </h1>
        <p class="text-gray-300 mb-6 max-w-lg">Transparent daily rates, real-time availability, and
            a fleet identified by its real registration number. Pick your vehicle, pick your dates,
            and we'll do the rest.</p>
        <div class="flex gap-3">
            <a href="{{ route('customer.vehicles.index') }}" class="rz-btn rounded-md px-5 py-3" data-testid="hero-browse">
                <i class="fa-solid fa-car mr-2"></i>Browse Fleet
            </a>
            @guest
                <a href="{{ route('register') }}" class="rz-btn-ghost rounded-md px-5 py-3" data-testid="hero-register">Sign Up Free</a>
            @endguest
        </div>
    </div>
</section>

<!-- Categories -->
@if($categories->count())
<section class="mb-12">
    <h2 class="text-2xl font-bold mb-6">Browse by category</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
        @foreach($categories as $cat)
            <a href="{{ route('customer.vehicles.index', ['category_id' => $cat->id]) }}"
               class="rz-card p-5 text-center hover:-translate-y-1 transition" data-testid="cat-{{ $cat->id }}">
                <i class="fa-solid fa-car-side rz-primary text-2xl mb-2"></i>
                <div class="font-semibold">{{ $cat->name }}</div>
                <div class="text-xs text-gray-400 mt-1">{{ $cat->vehicles_count }} vehicle(s)</div>
            </a>
        @endforeach
    </div>
</section>
@endif

<!-- Featured -->
<section>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Featured fleet</h2>
        <a href="{{ route('customer.vehicles.index') }}" class="text-sm rz-primary" data-testid="featured-see-all">See all →</a>
    </div>
    @if($featured->isEmpty())
        <div class="rz-card p-10 text-center text-gray-400">No vehicles available yet. An admin can add them from the admin portal.</div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featured as $v)
                @include('customer.vehicles._card', ['v' => $v])
            @endforeach
        </div>
    @endif
</section>
@endsection
