@extends('layouts.app')
@section('title', 'Vehicles')

@section('content')
<header class="mb-8">
    <h1 class="text-3xl sm:text-4xl font-semibold mb-2">Find your ride</h1>
    <p class="text-muted text-sm">Search by name, make, model or registration number. Enter dates to see only vehicles available in your window.</p>
</header>

<!-- Search / filter form -->
<form method="GET" class="rz-card bg-snow p-5 mb-8 grid grid-cols-1 md:grid-cols-6 gap-3">
    <div class="md:col-span-2">
        <label class="rz-label">Keyword / Registration</label>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="e.g. Toyota or LR3180"
            class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="search-keyword">
    </div>
    <div>
        <label class="rz-label">Category</label>
        <select name="category_id" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="search-category">
            <option value="">All</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="rz-label">Transmission</label>
        <select name="transmission" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="search-transmission">
            <option value="">Any</option>
            <option value="automatic" @selected(request('transmission') == 'automatic')>Automatic</option>
            <option value="manual" @selected(request('transmission') == 'manual')>Manual</option>
        </select>
    </div>
    <div>
        <label class="rz-label">Pickup date</label>
        <input type="date" name="pickup_date" value="{{ request('pickup_date') }}" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="search-pickup">
    </div>
    <div>
        <label class="rz-label">Return date</label>
        <input type="date" name="return_date" value="{{ request('return_date') }}" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="search-return">
    </div>

    <div class="md:col-span-6 flex items-center gap-3">
        <button type="submit" class="rz-btn rounded-md px-5 py-2" data-testid="search-submit">
            Search
        </button>
        <a href="{{ route('customer.vehicles.index') }}" class="text-sm text-muted hover:text-brand" data-testid="search-clear">Clear filters</a>
        @if(request('pickup_date') && request('return_date'))
            <span class="text-sm rz-primary ml-auto">Showing only vehicles available from {{ request('pickup_date') }} to {{ request('return_date') }}</span>
        @endif
    </div>
</form>

@if($vehicles->isEmpty())
    <div class="rz-card p-10 text-center text-muted" data-testid="no-vehicles">
        No vehicles match your filters. Try widening your search.
    </div>
@else
    @php $canCompare = auth()->check() && auth()->user()->isCustomer(); @endphp
    @if($canCompare)
        <form method="GET" action="{{ route('customer.compare.show') }}" id="compareForm">
            <div class="rounded-lg bg-tint border border-line p-3 mb-4 flex items-center justify-between gap-3 text-sm sticky top-16 z-30">
                <span style="color: var(--rz-muted);">
                                        Tick <strong>Compare</strong> on 2 or 3 vehicles to see them side by side.
                </span>
                <button type="submit" class="rz-btn rounded-md px-4 py-2 text-sm" data-testid="compare-browse-submit">
                    Compare selected
                </button>
            </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($vehicles as $v)
            @include('customer.vehicles._card', ['v' => $v, 'showCompare' => $canCompare])
        @endforeach
    </div>

    @if($canCompare)
        </form>
        @push('scripts')
        <script>
            // Cap selection at 3 on the browse grid as well.
            (function(){
                document.querySelectorAll('.compare-check').forEach(function(b){
                    b.addEventListener('change', function(){
                        if (document.querySelectorAll('.compare-check:checked').length > 3) {
                            b.checked = false;
                            alert('You can compare up to 3 vehicles at a time.');
                        }
                    });
                });
            })();
        </script>
        @endpush
    @endif

    <div class="mt-6">{{ $vehicles->links() }}</div>
@endif
@endsection
