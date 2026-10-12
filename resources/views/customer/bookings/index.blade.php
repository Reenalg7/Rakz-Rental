@extends('layouts.app')
@section('title', 'My Bookings')

@section('content')
<h1 class="text-3xl font-bold mb-6">My Bookings</h1>

@if($bookings->isEmpty())
    <div class="rz-card p-10 text-center text-gray-400">
        You have no bookings yet. <a href="{{ route('customer.vehicles.index') }}" class="rz-primary">Browse vehicles</a> to make your first booking.
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
                                    <span class="rz-badge bg-amber-400/20 text-amber-200 ml-2 text-xs">Replaced</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-400 font-mono">
                                Reg: {{ $b->vehicle_registration_no }}
                                @if($b->wasReplaced()) · was {{ $b->original_vehicle_registration_no }} @endif
                            </div>
                            <div class="text-xs text-gray-400 mt-1">
                                {{ $b->pickup_date->format('M d, Y') }} → {{ $b->return_date->format('M d, Y') }}
                                ({{ $b->total_days }} day{{ $b->total_days > 1 ? 's' : '' }})
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <div class="font-bold rz-primary">FJD {{ number_format($b->total_amount, 2) }}</div>
                            @php
                                $colors = ['pending'=>'bg-amber-400/20 text-amber-200','confirmed'=>'bg-emerald-400/20 text-emerald-200','cancelled'=>'bg-red-400/20 text-red-200','completed'=>'bg-blue-400/20 text-blue-200'];
                            @endphp
                            <span class="rz-badge mt-1 {{ $colors[$b->status] ?? 'bg-gray-400/20' }}">{{ ucfirst($b->status) }}</span>
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
