@extends('layouts.app')
@section('title', 'Compare vehicles')

@section('content')
<a href="{{ route('customer.favorites.index') }}" class="text-sm mb-4 inline-block" style="color: var(--rz-muted);">
    <i class="fa-solid fa-arrow-left mr-1"></i>Back to favorites
</a>

<header class="mb-6">
    <h1 class="text-3xl sm:text-4xl font-bold mb-2">Side-by-side comparison</h1>
    <p class="text-sm" style="color: var(--rz-muted);">
        Comparing {{ $vehicles->count() }} vehicle{{ $vehicles->count() > 1 ? 's' : '' }}.
        Pick the one that fits your trip best.
    </p>
</header>

<div class="overflow-x-auto">
    <table class="w-full rz-card" style="border-spacing:0;">
        <thead>
            <tr>
                <th class="text-left p-4 w-40 align-top" style="color: var(--rz-muted);">Attribute</th>
                @foreach($vehicles as $v)
                    <th class="p-4 align-top" style="min-width:220px;" data-testid="compare-col-{{ $v->registration_no }}">     
                <div class="aspect-video overflow-hidden rounded mb-3 relative"
                    style="background: var(--rz-input-bg);">
                    @if($v->image_url)
                        <img
                            src="{{ $v->image_url }}"
                            alt="{{ $v->name }}"
                            class="absolute inset-0 w-full h-full object-cover"
                            loading="lazy"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        >
                        <div class="absolute inset-0 items-center justify-center"
                            style="display: none; color: var(--rz-muted);">
                            <i class="fa-solid fa-car text-3xl"></i>
                        </div>
                    @else
                        <div class="absolute inset-0 flex items-center justify-center"
                            style="color: var(--rz-muted);">
                            <i class="fa-solid fa-car text-3xl"></i>
                        </div>
                    @endif
                </div>

                        <div class="font-bold">{{ $v->name }}</div>
                        <div class="text-xs" style="color: var(--rz-muted);">{{ $v->make }} · {{ $v->year }}</div>
                        <div class="rz-badge mt-1 font-mono text-xs rz-primary border" style="border-color: var(--rz-card-border);">{{ $v->registration_no }}</div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="text-sm">
            @php
                $rows = [
                    ['Category', fn($v) => $v->category?->name ?? '—'],
                    ['Daily rate', fn($v) => 'FJD ' . number_format($v->daily_rate, 2)],
                    ['Seats', fn($v) => $v->seats],
                    ['Transmission', fn($v) => ucfirst($v->transmission)],
                    ['Fuel type', fn($v) => ucfirst($v->fuel_type)],
                    ['Status', fn($v) => ucfirst($v->status)],
                    ['Description', fn($v) => $v->description ?: '—'],
                ];
                $best = $vehicles->sortBy('daily_rate')->first();
                $mostSeats = $vehicles->sortByDesc('seats')->first();
            @endphp
            @foreach($rows as [$label, $value])
                <tr class="border-t" style="border-color: var(--rz-card-border);">
                    <td class="p-4 align-top" style="color: var(--rz-muted);">{{ $label }}</td>
                    @foreach($vehicles as $v)
                        @php
                            $cls = '';
                            if ($label === 'Daily rate' && $v->registration_no === $best->registration_no) $cls = 'rz-primary font-bold';
                            if ($label === 'Seats' && $v->registration_no === $mostSeats->registration_no && $vehicles->pluck('seats')->unique()->count() > 1) $cls = 'rz-primary font-bold';
                        @endphp
                        <td class="p-4 align-top {{ $cls }}">{{ $value($v) }}</td>
                    @endforeach
                </tr>
            @endforeach
            <tr class="border-t" style="border-color: var(--rz-card-border);">
                <td class="p-4"></td>
                @foreach($vehicles as $v)
                    <td class="p-4 align-top">
                        @if($v->status === 'available')
                            <a href="{{ route('customer.bookings.create', $v->registration_no) }}"
                               class="rz-btn rounded-md px-4 py-2 text-sm inline-block" data-testid="compare-book-{{ $v->registration_no }}">
                                <i class="fa-regular fa-calendar-plus mr-1"></i>Book this
                            </a>
                        @else
                            <span class="text-xs" style="color: var(--rz-muted);">Not available</span>
                        @endif
                        <div class="mt-2">
                            <a href="{{ route('customer.vehicles.show', $v->registration_no) }}"
                               class="text-xs rz-primary" data-testid="compare-view-{{ $v->registration_no }}">View details →</a>
                        </div>
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

<div class="rz-card p-4 mt-6 text-xs" style="color: var(--rz-muted);">
    <i class="fa-solid fa-info-circle rz-primary mr-1"></i>
    The cheapest daily rate and the vehicle with the most seats are highlighted in gold.
</div>
@endsection
