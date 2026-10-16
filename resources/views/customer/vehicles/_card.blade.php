<div class="rz-card rz-card-hover overflow-hidden relative flex flex-col" data-testid="vehicle-card-{{ $v->registration_no }}">
    @auth
        @if(auth()->user()->isCustomer())
            @php $isFav = auth()->user()->hasFavorited($v->registration_no); @endphp
            <form method="POST" action="{{ route('customer.favorites.toggle', $v->registration_no) }}" class="absolute top-3 right-3 z-10">
                @csrf
                <button type="submit"
                    class="w-9 h-9 rounded-full bg-white border border-line shadow-sm flex items-center justify-center transition hover:border-brand"
                    data-testid="fav-toggle-{{ $v->registration_no }}"
                    aria-label="{{ $isFav ? 'Remove from favorites' : 'Add to favorites' }}"
                    title="{{ $isFav ? 'Remove from favorites' : 'Add to favorites' }}">
                    @if($isFav)
                        <i class="fa-solid fa-heart text-brand" aria-hidden="true"></i>
                    @else
                        <i class="fa-regular fa-heart text-muted" aria-hidden="true"></i>
                    @endif
                </button>
            </form>
        @endif
    @endauth

    @if(!empty($showCompare))
        <label class="absolute top-3 left-3 z-10 flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white border border-line shadow-sm cursor-pointer text-xs text-ink">
            <input type="checkbox" name="v[]" value="{{ $v->registration_no }}"
                class="compare-check align-middle accent-[#005691]" data-testid="compare-check-{{ $v->registration_no }}">
            <span>Compare</span>
        </label>
    @endif
    <div class="aspect-video bg-tint overflow-hidden">
        @if($v->image_url)
            <img src="{{ $v->image_url }}" alt="{{ $v->name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-muted text-sm">No photo</div>
        @endif
    </div>
    <div class="p-5 flex-1 flex flex-col">
        <div class="flex items-start justify-between gap-3 mb-2">
            <div class="min-w-0">
                <h3 class="font-semibold text-lg leading-snug">{{ $v->name }}</h3>
                <p class="text-sm text-muted">{{ $v->make }} · {{ $v->year }}</p>
            </div>
            <span class="rz-plate shrink-0">{{ $v->registration_no }}</span>
        </div>
        <p class="text-sm text-muted mb-5">
            {{ $v->seats }} seats &middot; {{ ucfirst($v->transmission) }} &middot; {{ ucfirst($v->fuel_type) }}
        </p>
        <div class="flex items-end justify-between mt-auto">
            <div>
                <div class="text-xs text-muted">per day</div>
                <div class="text-xl font-semibold text-brand-dark">FJD {{ number_format($v->daily_rate, 0) }}</div>
            </div>
            <a href="{{ route('customer.vehicles.show', $v->registration_no) }}" class="rz-btn-ghost px-4 py-2 text-sm" data-testid="view-vehicle-{{ $v->registration_no }}">View</a>
        </div>
    </div>
</div>
