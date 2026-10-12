<div class="rz-card overflow-hidden hover:-translate-y-1 transition relative" data-testid="vehicle-card-{{ $v->registration_no }}">
    @auth
        @if(auth()->user()->isCustomer())
            @php $isFav = auth()->user()->hasFavorited($v->registration_no); @endphp
            <form method="POST" action="{{ route('customer.favorites.toggle', $v->registration_no) }}" class="absolute top-3 right-3 z-10">
                @csrf
                <button type="submit"
                    class="w-9 h-9 rounded-full bg-black/50 backdrop-blur border border-white/10 flex items-center justify-center transition hover:scale-110"
                    data-testid="fav-toggle-{{ $v->registration_no }}"
                    title="{{ $isFav ? 'Remove from favorites' : 'Add to favorites' }}">
                    @if($isFav)
                        <i class="fa-solid fa-heart rz-primary"></i>
                    @else
                        <i class="fa-regular fa-heart text-white"></i>
                    @endif
                </button>
            </form>
        @endif
    @endauth

    @if(!empty($showCompare))
        <label class="absolute top-3 left-3 z-10 flex items-center gap-1 px-2 py-1 rounded-full bg-black/60 backdrop-blur border border-white/10 cursor-pointer text-xs text-white">
            <input type="checkbox" name="v[]" value="{{ $v->registration_no }}"
                class="compare-check align-middle" data-testid="compare-check-{{ $v->registration_no }}">
            <span>Compare</span>
        </label>
    @endif
    <div class="aspect-video bg-black/40 overflow-hidden">
        @if($v->image_url)
            <img src="{{ $v->image_url }}" alt="{{ $v->name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-600"><i class="fa-solid fa-car text-5xl"></i></div>
        @endif
    </div>
    <div class="p-5">
        <div class="flex items-start justify-between mb-2">
            <div>
                <h3 class="font-semibold text-lg">{{ $v->name }}</h3>
                <p class="text-xs text-gray-400">{{ $v->make }} · {{ $v->year }}</p>
            </div>
            <span class="rz-badge bg-black/40 border border-white/10 rz-primary font-mono text-xs">{{ $v->registration_no }}</span>
        </div>
        <div class="flex items-center gap-4 text-xs text-gray-400 mb-4">
            <span><i class="fa-solid fa-users mr-1"></i>{{ $v->seats }} seats</span>
            <span><i class="fa-solid fa-gears mr-1"></i>{{ ucfirst($v->transmission) }}</span>
            <span><i class="fa-solid fa-gas-pump mr-1"></i>{{ ucfirst($v->fuel_type) }}</span>
        </div>
        <div class="flex items-end justify-between">
            <div>
                <div class="text-xs text-gray-400">per day</div>
                <div class="text-xl font-bold rz-primary">FJD {{ number_format($v->daily_rate, 0) }}</div>
            </div>
            <a href="{{ route('customer.vehicles.show', $v->registration_no) }}" class="rz-btn-ghost px-3 py-2 rounded-md text-sm" data-testid="view-vehicle-{{ $v->registration_no }}">
                View <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</div>
