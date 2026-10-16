@extends('layouts.admin')
@section('page_title', 'Bookings')

@section('content')
@php
    $sc = ['pending'=>'bg-amber-50 text-amber-800','confirmed'=>'bg-emerald-50 text-emerald-800','cancelled'=>'bg-red-50 text-red-800','completed'=>'bg-tint text-brand-dark'];
    $pc = ['paid'=>'bg-emerald-50 text-emerald-800','unpaid'=>'bg-amber-50 text-amber-800','refunded'=>'bg-gray-100 text-gray-700'];
@endphp
<form method="GET" class="flex flex-wrap gap-2 mb-4">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search customer or reg no" class="rz-input rounded-md px-3 py-2 w-full sm:w-72" data-testid="admin-bookings-search">
    <select name="status" class="rz-input rounded-md px-3 py-2" data-testid="admin-bookings-status">
        <option value="">All status</option>
        @foreach(['pending','confirmed','cancelled','completed'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button class="rz-btn px-4">Search</button>
</form>

<div class="flex justify-end mb-3">
    <a href="{{ route('admin.export.bookings', request()->only('status','q')) }}"
       class="rz-btn-ghost rounded-md px-3 py-1.5 text-sm" data-testid="export-bookings-csv">
        Export CSV
    </a>
</div>

<div class="rz-card overflow-x-auto">
    <table class="w-full rz-table text-sm">
        <thead><tr>
            <th>#</th><th>Customer</th><th>Vehicle</th>
            <th>Dates</th><th>Total</th><th>Status</th><th>Payment</th><th></th>
        </tr></thead>
        <tbody>
        @forelse($bookings as $b)
            <tr class="hover:bg-snow" data-testid="admin-booking-row-{{ $b->id }}">
                <td>#{{ $b->id }}</td>
                <td>{{ $b->user->name }}<br><span class="text-xs text-muted">{{ $b->user->email }}</span></td>
                <td><span class="rz-plate">{{ $b->vehicle_registration_no }}</span><br><span class="text-xs text-muted">{{ $b->vehicle?->name }}</span></td>
                <td class="text-xs">{{ $b->pickup_date->format('M d, Y') }} – {{ $b->return_date->format('M d, Y') }}</td>
                <td>FJD {{ number_format($b->total_amount, 2) }}</td>
                <td><span class="rz-badge {{ $sc[$b->status] ?? '' }}">{{ ucfirst($b->status) }}</span></td>
                <td><span class="rz-badge {{ $pc[$b->billing?->status ?? ''] ?? '' }}">{{ ucfirst($b->billing?->status ?? '—') }}</span></td>
                <td><a href="{{ route('admin.bookings.show', $b) }}" class="rz-link text-sm" data-testid="admin-view-booking-{{ $b->id }}">Open</a></td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center py-6 text-muted">No bookings.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $bookings->links() }}</div>
@endsection
