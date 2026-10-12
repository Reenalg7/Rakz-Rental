@extends('layouts.admin')
@section('page_title', 'Replace Vehicle on Booking #' . $booking->id)

@section('content')
<a href="{{ route('admin.bookings.show', $booking) }}" class="text-sm text-gray-400 hover:text-white mb-4 inline-block"><i class="fa-solid fa-arrow-left mr-1"></i>Back to booking</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Current vehicle -->
    <div class="rz-card p-6">
        <h3 class="text-sm text-gray-400 uppercase mb-2">Currently assigned</h3>
        @if($booking->vehicle?->image_url)
            <img src="{{ $booking->vehicle->image_url }}" class="w-full h-40 object-cover rounded mb-3">
        @endif
        <div class="font-bold text-lg">{{ $booking->vehicle?->name }}</div>
        <div class="font-mono rz-primary">{{ $booking->vehicle_registration_no }}</div>
        <div class="text-xs text-gray-400 mt-1">{{ $booking->vehicle?->category?->name }} · {{ $booking->vehicle?->make }} {{ $booking->vehicle?->year }}</div>
        <div class="text-xs text-gray-400 mt-2">Rate: FJD {{ number_format($booking->vehicle?->daily_rate ?? 0, 2) }} / day</div>
        <div class="text-xs text-gray-400 mt-3">Dates: {{ $booking->pickup_date->format('M d') }} → {{ $booking->return_date->format('M d') }} ({{ $booking->total_days }}d)</div>
    </div>

    <!-- Replacement form -->
    <div class="lg:col-span-2 rz-card p-6">
        <h2 class="text-xl font-bold mb-1">Select a replacement vehicle</h2>
        <p class="text-sm text-gray-400 mb-4">Only vehicles that are available between <span class="rz-primary">{{ $booking->pickup_date->format('M d') }}</span> and <span class="rz-primary">{{ $booking->return_date->format('M d') }}</span> are shown.</p>

        @if($candidates->isEmpty())
            <div class="rz-card p-6 text-center text-gray-400">No alternative vehicles are available for these dates.</div>
        @else
            <form method="POST" action="{{ route('admin.replacements.store', $booking) }}">
                @csrf
                <div class="space-y-2 max-h-80 overflow-auto pr-2">
                    @foreach($candidates as $c)
                        <label class="flex items-center gap-3 rz-card p-3 cursor-pointer hover:border-[color:var(--rz-primary)]" data-testid="candidate-{{ $c->registration_no }}">
                            <input type="radio" name="replacement_registration_no" value="{{ $c->registration_no }}" required>
                            @if($c->image_url)<img src="{{ $c->image_url }}" class="w-16 h-12 object-cover rounded">@endif
                            <div class="flex-1">
                                <div class="font-semibold">{{ $c->name }} <span class="text-xs text-gray-400">({{ $c->make }} {{ $c->year }})</span></div>
                                <div class="font-mono text-xs rz-primary">{{ $c->registration_no }}</div>
                            </div>
                            <div class="text-sm">FJD {{ number_format($c->daily_rate, 0) }}/d</div>
                        </label>
                    @endforeach
                </div>

                <div class="mt-4">
                    <label class="text-xs text-gray-400">Reason for replacement (optional)</label>
                    <textarea name="reason" rows="2" class="rz-input w-full rounded-md px-3 py-2 mt-1" placeholder="e.g. Original vehicle in maintenance" data-testid="replacement-reason"></textarea>
                </div>

                <div class="mt-4 flex gap-3">
                    <button class="rz-btn rounded-md px-5 py-2.5" data-testid="confirm-replacement"><i class="fa-solid fa-shuffle mr-1"></i>Confirm replacement</button>
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="rz-btn-ghost rounded-md px-5 py-2.5">Cancel</a>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection
