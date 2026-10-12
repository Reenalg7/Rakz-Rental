@extends('layouts.admin')
@section('page_title', 'Booking #' . $booking->id)

@section('content')
<a href="{{ route('admin.bookings.index') }}" class="text-sm text-gray-400 hover:text-white mb-4 inline-block"><i class="fa-solid fa-arrow-left mr-1"></i>Back</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        <div class="rz-card p-6">
            <div class="flex items-start justify-between mb-4 flex-wrap gap-3">
                <div>
                    <h2 class="text-xl font-bold">Booking #{{ $booking->id }}</h2>
                    <p class="text-sm text-gray-400">by {{ $booking->user->name }} · {{ $booking->user->email }}</p>
                </div>
                <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="flex items-center gap-2">
                    @csrf
                    <select name="status" class="rz-input rounded-md px-3 py-1.5 text-sm" data-testid="admin-booking-status-select">
                        @foreach(['pending','confirmed','cancelled','completed'] as $s)
                            <option value="{{ $s }}" @selected($booking->status === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button class="rz-btn rounded-md px-3 py-1.5 text-sm" data-testid="admin-update-status">Update</button>
                </form>
            </div>

            @if($booking->wasReplaced())
                <div class="border-l-4 border-amber-400 bg-amber-400/10 text-amber-100 px-4 py-2 text-sm rounded mb-4">
                    Vehicle replaced: <span class="font-mono">{{ $booking->original_vehicle_registration_no }}</span> →
                    <span class="font-mono">{{ $booking->vehicle_registration_no }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-xs text-gray-400">Current vehicle</div>
                    <div class="font-mono rz-primary">{{ $booking->vehicle_registration_no }}</div>
                    <div>{{ $booking->vehicle?->name }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-400">Dates</div>
                    <div>{{ $booking->pickup_date->format('M d, Y') }} → {{ $booking->return_date->format('M d, Y') }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ $booking->total_days }} day(s)</div>
                </div>
            </div>
            @if($booking->notes)
                <div class="mt-4 border-t border-white/10 pt-3 text-sm"><span class="text-xs text-gray-400">Customer notes:</span> {{ $booking->notes }}</div>
            @endif
        </div>

        @if($booking->replacements->isNotEmpty())
            <div class="rz-card p-6">
                <h3 class="font-bold mb-3">Replacement audit trail</h3>
                <ul class="space-y-2 text-sm">
                    @foreach($booking->replacements as $r)
                        <li class="border-l-2 border-[color:var(--rz-primary)] pl-3">
                            <span class="font-mono">{{ $r->original_registration_no }}</span>
                            <i class="fa-solid fa-arrow-right mx-2 text-gray-500"></i>
                            <span class="font-mono rz-primary">{{ $r->replacement_registration_no }}</span>
                            <div class="text-xs text-gray-400">
                                {{ $r->created_at->format('M d, Y H:i') }}
                                · by {{ $r->replacedBy?->name ?? '—' }}
                                · {{ $r->reason ?: 'No reason' }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <aside class="space-y-4">
        <div class="rz-card p-6">
            <h3 class="font-bold mb-2">Billing</h3>
            <dl class="text-sm space-y-1">
                <div class="flex justify-between"><dt class="text-gray-400">Amount</dt><dd class="rz-primary font-bold">FJD {{ number_format($booking->total_amount, 2) }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-400">Status</dt><dd>{{ ucfirst($booking->billing?->status ?? '—') }}</dd></div>
                @if($booking->billing?->paid_at)
                    <div class="flex justify-between"><dt class="text-gray-400">Paid at</dt><dd>{{ $booking->billing->paid_at->format('M d, Y H:i') }}</dd></div>
                @endif
            </dl>
            @if($booking->billing && $booking->billing->status === 'unpaid')
                <form method="POST" action="{{ route('admin.bookings.paid', $booking) }}" class="mt-3">
                    @csrf
                    <button class="rz-btn w-full rounded-md py-2 text-sm" data-testid="admin-mark-paid"><i class="fa-solid fa-money-bill-1 mr-1"></i>Mark as paid</button>
                </form>
            @endif
        </div>

        @if(!in_array($booking->status, ['cancelled','completed']))
            <a href="{{ route('admin.replacements.create', $booking) }}" class="rz-btn-ghost w-full rounded-md py-3 block text-center" data-testid="admin-replace-vehicle">
                <i class="fa-solid fa-shuffle mr-1"></i>Replace vehicle
            </a>
        @endif

        <a href="{{ route('admin.bookings.invoice', $booking) }}" target="_blank"
           class="rz-btn w-full rounded-md py-3 block text-center" data-testid="admin-download-invoice">
            <i class="fa-solid fa-file-pdf mr-1"></i>Download Invoice (PDF)
        </a>
    </aside>
</div>
@endsection
