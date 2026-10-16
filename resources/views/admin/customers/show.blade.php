@extends('layouts.admin')
@section('page_title', $customer->name)

@section('content')
<a href="{{ route('admin.customers.index') }}" class="text-sm text-muted mb-4 inline-block">Back</a>

<div class="rz-card p-6 mb-6">
    <h2 class="text-xl font-semibold">{{ $customer->name }}</h2>
    <div class="text-sm text-muted">{{ $customer->email }} · {{ $customer->phone ?: 'No phone' }}</div>
    <div class="text-xs text-muted mt-1">NIC: {{ $customer->nic ?: '—' }} · License: {{ $customer->driver_license ?: '—' }}</div>
    <div class="text-xs text-muted mt-1">Address: {{ $customer->address ?: '—' }}</div>
</div>

<div class="rz-card overflow-x-auto">
    <table class="w-full rz-table text-sm">
        <thead><tr><th>#</th><th>Vehicle</th><th>Dates</th><th>Total</th><th>Status</th></tr></thead>
        <tbody>
        @forelse($customer->bookings as $b)
            <tr class="hover:bg-snow">
                <td><a href="{{ route('admin.bookings.show', $b) }}" class="rz-link">#{{ $b->id }}</a></td>
                <td class="font-mono text-xs">{{ $b->vehicle_registration_no }}</td>
                <td class="text-xs">{{ $b->pickup_date->format('M d') }} – {{ $b->return_date->format('M d') }}</td>
                <td>FJD {{ number_format($b->total_amount, 0) }}</td>
                <td>{{ ucfirst($b->status) }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center py-6 text-muted">No bookings.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
