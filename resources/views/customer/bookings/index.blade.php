@extends('layouts.app')
@section('title', 'My Bookings')

@section('content')
<h1 class="text-3xl font-semibold mb-6">My Bookings</h1>

@if($bookings->isEmpty())
    <div class="rz-card p-10 text-center text-muted">
        You have no bookings yet. <a href="{{ route('customer.vehicles.index') }}" class="rz-link">Browse vehicles</a> to make your first booking.
    </div>
@else
    <div class="space-y-4">
        @foreach($bookings as $b)
            <div class="rz-card p-5" data-testid="booking-row-{{ $b->id }}">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        @if($b->vehicle?->image_url)
                            <img src="{{ $b->vehicle->image_url }}" class="w-20 h-16 rounded object-cover">
                        @endif
                        <div>
                            <div class="font-semibold">{{ $b->vehicle?->name ?? '—' }}
                                @if($b->wasReplaced())
                                    <span class="rz-badge bg-amber-50 text-amber-800 ml-2 text-xs">Replaced</span>
                                @endif
                            </div>
                            <div class="text-xs text-muted font-mono">
                                Reg: {{ $b->vehicle_registration_no }}
                                @if($b->wasReplaced()) · was {{ $b->original_vehicle_registration_no }} @endif
                            </div>
                            <div class="text-xs text-muted mt-1">
                                {{ $b->pickup_date->format('M d, Y') }} – {{ $b->return_date->format('M d, Y') }}
                                ({{ $b->total_days }} day{{ $b->total_days > 1 ? 's' : '' }})
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <div class="font-semibold rz-primary">FJD {{ number_format($b->total_amount, 2) }}</div>
                            @php
                                $colors = ['pending'=>'bg-amber-50 text-amber-800','confirmed'=>'bg-emerald-50 text-emerald-800','cancelled'=>'bg-red-50 text-red-800','completed'=>'bg-tint text-brand-dark'];
                            @endphp
                            <span class="rz-badge mt-1 {{ $colors[$b->status] ?? 'bg-gray-100 text-gray-700' }}">{{ ucfirst($b->status) }}</span>
                        </div>
                        <a href="{{ route('customer.bookings.show', $b) }}" class="rz-btn-ghost rounded-md px-3 py-2 text-sm" data-testid="view-booking-{{ $b->id }}">View</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $bookings->links() }}</div>
@endif
@endsection
