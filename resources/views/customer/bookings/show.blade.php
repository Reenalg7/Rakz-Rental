@extends('layouts.app')
@section('title', 'Booking #' . $booking->id)

@section('content')
<a href="{{ route('customer.bookings.index') }}" class="text-sm text-gray-400 hover:text-white mb-4 inline-block">
    <i class="fa-solid fa-arrow-left mr-1"></i>Back to my bookings
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="rz-card p-6">
            <div class="flex items-start justify-between flex-wrap gap-3 mb-4">
                <div>
                    <h1 class="text-2xl font-bold">Booking #{{ $booking->id }}</h1>
                    <p class="text-sm text-gray-400">Created {{ $booking->created_at->format('M d, Y H:i') }}</p>
                </div>
                @php $colors = ['pending'=>'bg-amber-400/20 text-amber-200','confirmed'=>'bg-emerald-400/20 text-emerald-200','cancelled'=>'bg-red-400/20 text-red-200','completed'=>'bg-blue-400/20 text-blue-200']; @endphp
                <span class="rz-badge {{ $colors[$booking->status] ?? '' }}" data-testid="booking-status">{{ ucfirst($booking->status) }}</span>
            </div>

            @if($booking->wasReplaced())
                <div class="rz-card border-l-4 border-amber-400 text-amber-100 p-4 mb-4 text-sm" data-testid="replacement-notice">
                    <i class="fa-solid fa-shuffle rz-primary mr-2"></i>
                    <strong>Your vehicle was replaced.</strong> Originally: <span class="font-mono">{{ $booking->original_vehicle_registration_no }}</span>. Now: <span class="font-mono">{{ $booking->vehicle_registration_no }}</span>.
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="flex gap-4">
                    @if($booking->vehicle?->image_url)
                        <img src="{{ $booking->vehicle->image_url }}" class="w-28 h-24 object-cover rounded">
                    @endif
                    <div>
                        <div class="text-xs text-gray-400">Vehicle</div>
                        <div class="font-semibold">{{ $booking->vehicle?->name }}</div>
                        <div class="text-xs text-gray-400 font-mono">Reg: {{ $booking->vehicle_registration_no }}</div>
                        <div class="text-xs text-gray-400">{{ $booking->vehicle?->category?->name }}</div>
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-400">Pickup</div>
                    <div class="font-semibold">{{ $booking->pickup_date->format('M d, Y') }}</div>
                    <div class="text-xs text-gray-400 mt-2">Return</div>
                    <div class="font-semibold">{{ $booking->return_date->format('M d, Y') }}</div>
                </div>
            </div>

            @if($booking->notes)
                <div class="border-t border-white/10 mt-4 pt-4">
                    <div class="text-xs text-gray-400 mb-1">Notes</div>
                    <p class="text-sm">{{ $booking->notes }}</p>
                </div>
            @endif
        </div>

        @if($booking->replacements->isNotEmpty())
            <div class="rz-card p-6">
                <h2 class="text-lg font-bold mb-3">Replacement history</h2>
                <ul class="space-y-2 text-sm">
                    @foreach($booking->replacements as $r)
                        <li class="border-l-2 border-[color:var(--rz-primary)] pl-3">
                            <span class="font-mono">{{ $r->original_registration_no }}</span>
                            <i class="fa-solid fa-arrow-right mx-2 text-gray-500"></i>
                            <span class="font-mono rz-primary">{{ $r->replacement_registration_no }}</span>
                            <div class="text-xs text-gray-400">{{ $r->created_at->format('M d, Y H:i') }} · {{ $r->reason ?: 'No reason given' }}</div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <aside class="rz-card p-6 h-fit">
        <h3 class="font-bold mb-3">Billing summary</h3>
        <dl class="text-sm space-y-2">
            <div class="flex justify-between"><dt class="text-gray-400">Daily rate</dt><dd>FJD {{ number_format($booking->daily_rate_snapshot, 2) }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-400">Days</dt><dd>{{ $booking->total_days }}</dd></div>
            <div class="flex justify-between border-t border-white/10 pt-2 mt-2 font-bold"><dt>Total</dt><dd class="rz-primary">FJD {{ number_format($booking->total_amount, 2) }}</dd></div>
            @if($booking->billing)
                <div class="flex justify-between"><dt class="text-gray-400">Payment</dt>
                    <dd>
                        @if($booking->billing->status === 'paid')
                            <span class="rz-badge bg-emerald-400/20 text-emerald-200">Paid</span>
                        @elseif($booking->billing->status === 'refunded')
                            <span class="rz-badge bg-gray-400/20 text-gray-200">Refunded</span>
                        @else
                            <span class="rz-badge bg-amber-400/20 text-amber-200">Unpaid</span>
                        @endif
                    </dd>
                </div>
            @endif
        </dl>

        @if(in_array($booking->status, ['pending', 'confirmed']))
            <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}" class="mt-5" onsubmit="return confirm('Cancel this booking?');">
                @csrf
                <button type="submit" class="w-full rz-btn-ghost rounded-md py-2 text-sm text-red-300 border-red-400/40 hover:border-red-400" data-testid="cancel-booking">
                    <i class="fa-regular fa-circle-xmark mr-1"></i>Cancel booking
                </button>
            </form>
        @endif

        <a href="{{ route('customer.bookings.invoice', $booking) }}" target="_blank"
           class="mt-3 w-full rz-btn rounded-md py-2 text-sm block text-center" data-testid="download-invoice">
            <i class="fa-solid fa-file-pdf mr-1"></i>Download Invoice (PDF)
        </a>
    </aside>
</div>
@endsection
