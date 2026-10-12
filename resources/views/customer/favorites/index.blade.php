@extends('layouts.app')
@section('title', 'My Favorites')

@section('content')
<header class="mb-6">
    <h1 class="text-3xl sm:text-4xl font-bold mb-2">Your favorite vehicles</h1>
    <p class="text-sm" style="color: var(--rz-muted);">
        One-click rebooking for cars you've loved before.
        Click the <i class="fa-solid fa-heart rz-primary"></i> on any vehicle to add it here.
    </p>
</header>

@if($favorites->isEmpty())
    <div class="rz-card p-12 text-center" data-testid="no-favorites">
        <i class="fa-regular fa-heart text-5xl rz-primary mb-4"></i>
        <h2 class="text-xl font-bold mb-2">No favorites yet</h2>
        <p class="text-sm mb-5" style="color: var(--rz-muted);">Browse the fleet and tap the heart on any vehicle to save it here.</p>
        <a href="{{ route('customer.vehicles.index') }}" class="rz-btn rounded-md px-5 py-2.5" data-testid="browse-from-favorites">
            <i class="fa-solid fa-car mr-1"></i>Browse Vehicles
        </a>
    </div>
@else
    <form method="GET" action="{{ route('customer.compare.show') }}" id="compareForm">
        <div class="rz-card p-3 mb-4 flex items-center justify-between gap-3 text-sm">
            <span style="color: var(--rz-muted);">
                <i class="fa-solid fa-scale-balanced rz-primary mr-1"></i>
                Tick 2 or 3 vehicles below, then compare them side-by-side.
            </span>
            <button type="submit" class="rz-btn rounded-md px-4 py-2 text-sm" data-testid="compare-submit">
                <i class="fa-solid fa-scale-balanced mr-1"></i>Compare selected
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($favorites as $fav)
                @php $v = $fav->vehicle; @endphp
                <div class="rz-card overflow-hidden" data-testid="favorite-card-{{ $v->registration_no }}">
                    <label class="block cursor-pointer">
                        <div class="aspect-video bg-black/40 overflow-hidden relative">
                            @if($v->image_url)
                                <img src="{{ $v->image_url }}" alt="{{ $v->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center"><i class="fa-solid fa-car text-5xl" style="color: var(--rz-muted);"></i></div>
                            @endif
                            @if($v->status !== 'available')
                                <span class="absolute top-2 right-2 rz-badge bg-amber-400/80 text-black">{{ ucfirst($v->status) }}</span>
                            @endif
                            <span class="absolute top-2 left-2 rz-badge bg-black/60 text-white text-xs">
                                <input type="checkbox" name="v[]" value="{{ $v->registration_no }}"
                                    class="compare-check mr-1 align-middle" data-testid="compare-check-{{ $v->registration_no }}">
                                Compare
                            </span>
                        </div>
                    </label>
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="font-semibold text-lg">{{ $v->name }}</h3>
                                <p class="text-xs" style="color: var(--rz-muted);">{{ $v->make }} · {{ $v->year }} · Saved {{ $fav->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="rz-badge border font-mono text-xs rz-primary" style="border-color: var(--rz-card-border);">{{ $v->registration_no }}</span>
                        </div>
                        <div class="flex items-end justify-between mt-4">
                            <div>
                                <div class="text-xs" style="color: var(--rz-muted);">per day</div>
                                <div class="text-xl font-bold rz-primary">FJD {{ number_format($v->daily_rate, 0) }}</div>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" onclick="event.preventDefault();document.getElementById('unfav-{{ $v->registration_no }}').submit();"
                                    class="rz-btn-ghost px-3 py-2 rounded-md text-sm" title="Remove from favorites">
                                    <i class="fa-solid fa-heart-crack"></i>
                                </button>
                                @if($v->status === 'available')
                                    <a href="{{ route('customer.bookings.create', $v->registration_no) }}"
                                       class="rz-btn px-3 py-2 rounded-md text-sm" data-testid="rebook-{{ $v->registration_no }}">
                                        <i class="fa-regular fa-calendar-plus mr-1"></i>Book again
                                    </a>
                                @else
                                    <span class="text-xs" style="color: var(--rz-muted);">Not available</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </form>

    <!-- Nested unfav forms (kept outside the compare form so submitting one doesn't submit the other). -->
    @foreach($favorites as $fav)
        <form id="unfav-{{ $fav->vehicle->registration_no }}" method="POST"
              action="{{ route('customer.favorites.toggle', $fav->vehicle->registration_no) }}" class="hidden">
            @csrf
        </form>
    @endforeach

    @push('scripts')
    <script>
        // Cap selection at 3 — un-tick anything beyond that.
        (function(){
            var boxes = document.querySelectorAll('.compare-check');
            boxes.forEach(function(b){
                b.addEventListener('change', function(){
                    var checked = document.querySelectorAll('.compare-check:checked');
                    if (checked.length > 3) {
                        b.checked = false;
                        alert('You can compare up to 3 vehicles at a time.');
                    }
                });
            });
        })();
    </script>
    @endpush
@endif
@endsection
