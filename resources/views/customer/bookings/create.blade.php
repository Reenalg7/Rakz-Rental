@extends('layouts.app')
@section('title', 'Book ' . $vehicle->name)

@section('content')
<a href="{{ route('customer.vehicles.show', $vehicle->registration_no) }}" class="text-sm text-gray-400 hover:text-white mb-4 inline-block">
    <i class="fa-solid fa-arrow-left mr-1"></i>Back
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 rz-card p-6">
        <h1 class="text-2xl font-bold mb-1">Reserve this vehicle</h1>
        <p class="text-sm text-gray-400 mb-6">Pick your dates. We'll check live availability and calculate your total before you confirm.</p>

        <form method="POST" action="{{ route('customer.bookings.store', $vehicle->registration_no) }}" id="bookingForm" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-300 block mb-1">Pickup date</label>
                    <input id="pickup_date" type="date" name="pickup_date" required min="{{ date('Y-m-d') }}"
                        value="{{ old('pickup_date') }}" class="rz-input w-full rounded-md px-3 py-2" data-testid="booking-pickup">
                </div>
                <div>
                    <label class="text-sm text-gray-300 block mb-1">Return date</label>
                    <input id="return_date" type="date" name="return_date" required min="{{ date('Y-m-d') }}"
                        value="{{ old('return_date') }}" class="rz-input w-full rounded-md px-3 py-2" data-testid="booking-return">
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-300 block mb-1">Notes (optional)</label>
                <textarea name="notes" rows="2" class="rz-input w-full rounded-md px-3 py-2" placeholder="Any special requests?" data-testid="booking-notes">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3 items-center">
                <button type="button" id="checkBtn" class="rz-btn-ghost rounded-md px-4 py-2 text-sm" data-testid="check-availability">
                    <i class="fa-solid fa-calendar-check mr-1"></i>Check availability & price
                </button>
                <span id="checkStatus" class="text-sm"></span>
            </div>

            <button type="submit" id="confirmBtn" disabled class="rz-btn w-full rounded-md py-3 disabled:opacity-40 disabled:cursor-not-allowed" data-testid="confirm-booking">
                <i class="fa-regular fa-circle-check mr-1"></i>Confirm Booking
            </button>
        </form>
    </div>

    <aside class="rz-card p-6 h-fit">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-14 h-14 rounded-md bg-black/40 overflow-hidden flex-shrink-0">
                @if($vehicle->image_url)<img src="{{ $vehicle->image_url }}" class="w-full h-full object-cover">@endif
            </div>
            <div>
                <div class="font-semibold">{{ $vehicle->name }}</div>
                <div class="text-xs text-gray-400 font-mono">{{ $vehicle->registration_no }}</div>
            </div>
        </div>

        <dl class="text-sm space-y-2">
            <div class="flex justify-between"><dt class="text-gray-400">Daily rate</dt><dd>FJD {{ number_format($vehicle->daily_rate, 2) }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-400">Days</dt><dd id="sumDays">—</dd></div>
            <div class="flex justify-between border-t border-white/10 pt-2 mt-2 text-base font-bold"><dt>Total</dt><dd id="sumTotal" class="rz-primary">—</dd></div>
        </dl>
    </aside>
</div>

@push('scripts')
<script>
const checkUrl = "{{ route('customer.bookings.check', $vehicle->registration_no) }}";
const token = document.querySelector('meta[name="csrf-token"]').content;
const pickup = document.getElementById('pickup_date');
const ret = document.getElementById('return_date');
const btn = document.getElementById('checkBtn');
const status = document.getElementById('checkStatus');
const confirmBtn = document.getElementById('confirmBtn');
const sumDays = document.getElementById('sumDays');
const sumTotal = document.getElementById('sumTotal');

async function check() {
    if (!pickup.value || !ret.value) {
        status.innerHTML = '<span class="text-amber-300">Please select both dates.</span>';
        return;
    }
    status.innerHTML = '<span class="text-gray-300"><i class="fa-solid fa-spinner fa-spin"></i> Checking...</span>';
    const res = await fetch(checkUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
        body: JSON.stringify({ pickup_date: pickup.value, return_date: ret.value })
    });
    const data = await res.json();
    if (!res.ok) {
        status.innerHTML = '<span class="text-red-300">Invalid dates.</span>';
        confirmBtn.disabled = true;
        return;
    }
    sumDays.textContent = data.total_days + ' day(s)';
    sumTotal.textContent = 'FJD ' + Number(data.total_amount).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
    if (data.available) {
        status.innerHTML = '<span class="text-emerald-300"><i class="fa-solid fa-check-circle"></i> ' + data.message + '</span>';
        confirmBtn.disabled = false;
    } else {
        status.innerHTML = '<span class="text-red-300"><i class="fa-solid fa-xmark-circle"></i> ' + data.message + '</span>';
        confirmBtn.disabled = true;
    }
}

btn.addEventListener('click', check);
pickup.addEventListener('change', () => { confirmBtn.disabled = true; });
ret.addEventListener('change', () => { confirmBtn.disabled = true; });
</script>
@endpush
@endsection
