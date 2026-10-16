@extends('layouts.app')
@section('title', 'Booking #' . $booking->id)

@section('content')
<a href="{{ route('customer.bookings.index') }}" class="text-sm text-muted hover:text-brand mb-4 inline-block">
    Back to my bookings
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="rz-card p-6">
            <div class="flex items-start justify-between flex-wrap gap-3 mb-4">
                <div>
                    <h1 class="text-2xl font-semibold">Booking #{{ $booking->id }}</h1>
                    <p class="text-sm text-muted">Created {{ $booking->created_at->format('M d, Y H:i') }}</p>
                </div>
                @php $colors = ['pending'=>'bg-amber-50 text-amber-800','confirmed'=>'bg-emerald-50 text-emerald-800','cancelled'=>'bg-red-50 text-red-800','completed'=>'bg-tint text-brand-dark']; @endphp
                <span class="rz-badge {{ $colors[$booking->status] ?? '' }}" data-testid="booking-status">{{ ucfirst($booking->status) }}</span>
            </div>

            @if($booking->wasReplaced())
                <div class="rz-alert rz-alert-warn" data-testid="replacement-notice">
                                        <strong>Your vehicle was replaced.</strong> Originally: <span class="font-mono">{{ $booking->original_vehicle_registration_no }}</span>. Now: <span class="font-mono">{{ $booking->vehicle_registration_no }}</span>.
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="flex gap-4">
                    @if($booking->vehicle?->image_url)
                        <img src="{{ $booking->vehicle->image_url }}" class="w-28 h-24 object-cover rounded">
                    @endif
                    <div>
                        <div class="text-xs text-muted">Vehicle</div>
                        <div class="font-semibold">{{ $booking->vehicle?->name }}</div>
                        <div class="text-xs text-muted font-mono">Reg: {{ $booking->vehicle_registration_no }}</div>
                        <div class="text-xs text-muted">{{ $booking->vehicle?->category?->name }}</div>
                    </div>
                </div>
                <div>
                    <div class="text-xs text-muted">Pickup</div>
                    <div class="font-semibold">{{ $booking->pickup_date->format('M d, Y') }}</div>
                    <div class="text-xs text-muted mt-2">Return</div>
                    <div class="font-semibold">{{ $booking->return_date->format('M d, Y') }}</div>
                </div>
            </div>

            @if($booking->notes)
                <div class="border-t border-line mt-4 pt-4">
                    <div class="text-xs text-muted mb-1">Notes</div>
                    <p class="text-sm">{{ $booking->notes }}</p>
                </div>
            @endif
        </div>

        @if($booking->replacements->isNotEmpty())
            <div class="rz-card p-6">
                <h2 class="text-lg font-semibold mb-3">Replacement history</h2>
                <ul class="space-y-2 text-sm">
                    @foreach($booking->replacements as $r)
                        <li class="border-l-2 border-[color:var(--rz-primary)] pl-3">
                            <span class="font-mono">{{ $r->original_registration_no }}</span>
                            <i class="fa-solid fa-arrow-right mx-2 text-muted"></i>
                            <span class="font-mono rz-primary">{{ $r->replacement_registration_no }}</span>
                            <div class="text-xs text-muted">{{ $r->created_at->format('M d, Y H:i') }} · {{ $r->reason ?: 'No reason given' }}</div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <aside class="rz-card p-6 h-fit">
        <h3 class="font-semibold mb-3">Billing summary</h3>
        <dl class="text-sm space-y-2">
            <div class="flex justify-between"><dt class="text-muted">Daily rate</dt><dd>FJD {{ number_format($booking->daily_rate_snapshot, 2) }}</dd></div>
            <div class="flex justify-between"><dt class="text-muted">Days</dt><dd>{{ $booking->total_days }}</dd></div>
            <div class="flex justify-between border-t border-line pt-2 mt-2 font-semibold"><dt>Total</dt><dd class="rz-primary">FJD {{ number_format($booking->total_amount, 2) }}</dd></div>
            @if($booking->billing)
                <div class="flex justify-between"><dt class="text-muted">Payment</dt>
                    <dd>
                        @if($booking->billing->status === 'paid')
                            <span class="rz-badge bg-emerald-50 text-emerald-800">Paid</span>
                        @elseif($booking->billing->status === 'refunded')
                            <span class="rz-badge bg-gray-100 text-gray-700">Refunded</span>
                        @else
                            <span class="rz-badge bg-amber-50 text-amber-800">Unpaid</span>
                        @endif
                    </dd>
                </div>
            @endif
        </dl>

        @if(in_array($booking->status, ['pending', 'confirmed']))
            <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}" class="mt-5" onsubmit="return confirm('Cancel this booking?');">
                @csrf
                <button type="submit" class="w-full rz-btn-ghost rounded-md py-2 text-sm text-red-700 border-red-300 hover:border-red-600" data-testid="cancel-booking">
                    Cancel booking
                </button>
            </form>
        @endif

        <a href="{{ route('customer.bookings.invoice', $booking) }}" target="_blank"
           class="mt-3 w-full rz-btn rounded-md py-2 text-sm block text-center" data-testid="download-invoice">
            Download Invoice (PDF)
        </a>
    </aside>
</div>
@endsection
