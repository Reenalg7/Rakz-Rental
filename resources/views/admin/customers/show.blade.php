@extends('layouts.admin')
@section('page_title', $customer->name)

@section('content')
<a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-400 mb-4 inline-block"><i class="fa-solid fa-arrow-left mr-1"></i>Back</a>

<div class="rz-card p-6 mb-6">
    <h2 class="text-xl font-bold">{{ $customer->name }}</h2>
    <div class="text-sm text-gray-400">{{ $customer->email }} · {{ $customer->phone ?: 'No phone' }}</div>
    <div class="text-xs text-gray-400 mt-1">NIC: {{ $customer->nic ?: '—' }} · License: {{ $customer->driver_license ?: '—' }}</div>
    <div class="text-xs text-gray-400 mt-1">Address: {{ $customer->address ?: '—' }}</div>
</div>

<div class="rz-card overflow-hidden">
    <table class="w-full rz-table text-sm">
        <thead><tr><th class="text-left">#</th><th class="text-left">Vehicle</th><th class="text-left">Dates</th><th class="text-left">Total</th><th class="text-left">Status</th></tr></thead>
        <tbody>
        @forelse($customer->bookings as $b)
            <tr class="hover:bg-white/5">
                <td><a href="{{ route('admin.bookings.show', $b) }}" class="rz-primary">#{{ $b->id }}</a></td>
                <td class="font-mono text-xs">{{ $b->vehicle_registration_no }}</td>
                <td class="text-xs">{{ $b->pickup_date->format('M d') }} → {{ $b->return_date->format('M d') }}</td>
                <td>FJD {{ number_format($b->total_amount, 0) }}</td>
                <td>{{ ucfirst($b->status) }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center py-6 text-gray-400">No bookings.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
