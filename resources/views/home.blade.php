@extends('layouts.app')
@section('title', 'Home')

@section('content')
<!-- Hero -->
<section class="rounded-lg bg-tint overflow-hidden grid grid-cols-1 md:grid-cols-2 mb-12">
    <div class="p-8 md:p-12 lg:p-14 flex flex-col justify-center">
        <h1 class="text-4xl sm:text-5xl font-semibold leading-tight mb-4 text-brand-dark">
            Drive your next journey in confidence.
        </h1>
        <p class="text-ink mb-8 max-w-md leading-relaxed">Transparent daily rates, real-time availability, and
            a fleet identified by its real registration number. Pick your vehicle, pick your dates,
            and we'll do the rest.</p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('customer.vehicles.index') }}" class="rz-btn px-5 py-3" data-testid="hero-browse">Browse fleet</a>
            @guest
                <a href="{{ route('register') }}" class="rz-btn-ghost px-5 py-3" data-testid="hero-register">Sign up free</a>
            @endguest
        </div>
    </div>
    <div class="min-h-[220px] md:min-h-[360px] bg-cover bg-center"
         style="background-image: url('https://images.unsplash.com/photo-1489686995744-f47e995ffe61?w=1600');"
         role="img" aria-label="A car on an open road"></div>
</section>

<!-- Categories -->
@if($categories->count())
<section class="mb-12">
    <h2 class="text-2xl font-semibold mb-5">Browse by category</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
        @foreach($categories as $cat)
            <a href="{{ route('customer.vehicles.index', ['category_id' => $cat->id]) }}"
               class="rz-card block p-5" data-testid="cat-{{ $cat->id }}">
                <div class="font-semibold">{{ $cat->name }}</div>
                <div class="text-sm text-muted mt-1">{{ $cat->vehicles_count }} {{ $cat->vehicles_count == 1 ? 'vehicle' : 'vehicles' }}</div>
            </a>
        @endforeach
    </div>
</section>
@endif

<!-- Featured -->
<section>
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-2xl font-semibold">Featured fleet</h2>
        <a href="{{ route('customer.vehicles.index') }}" class="text-sm rz-link" data-testid="featured-see-all">See all vehicles</a>
    </div>
    @if($featured->isEmpty())
        <div class="rounded-lg bg-snow border border-line p-10 text-center text-muted">No vehicles available yet. An admin can add them from the admin portal.</div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featured as $v)
                @include('customer.vehicles._card', ['v' => $v])
            @endforeach
        </div>
    @endif
</section>
@endsection
