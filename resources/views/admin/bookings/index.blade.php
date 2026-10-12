@extends('layouts.admin')
@section('page_title', 'Bookings')

@section('content')
<form method="GET" class="flex gap-2 mb-4">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search customer or reg no" class="rz-input rounded-md px-3 py-2 w-72" data-testid="admin-bookings-search">
    <select name="status" class="rz-input rounded-md px-3 py-2" data-testid="admin-bookings-status">
        <option value="">All status</option>
        @foreach(['pending','confirmed','cancelled','completed'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button class="rz-btn-ghost rounded-md px-4">Search</button>
</form>

<div class="flex justify-end mb-3">
    <a href="{{ route('admin.export.bookings', request()->only('status','q')) }}"
       class="rz-btn-ghost rounded-md px-3 py-1.5 text-sm" data-testid="export-bookings-csv">
        <i class="fa-solid fa-file-csv mr-1"></i>Export CSV
    </a>
</div>

<div class="rz-card overflow-hidden">
    <table class="w-full rz-table text-sm">
        <thead><tr>
            <th class="text-left">#</th><th class="text-left">Customer</th><th class="text-left">Vehicle</th>
            <th class="text-left">Dates</th><th class="text-left">Total</th><th class="text-left">Status</th><th class="text-left">Payment</th><th></th>
        </tr></thead>
        <tbody>
        @forelse($bookings as $b)
            <tr class="hover:bg-white/5" data-testid="admin-booking-row-{{ $b->id }}">
                <td>#{{ $b->id }}</td>
                <td>{{ $b->user->name }}<br><span class="text-xs text-gray-400">{{ $b->user->email }}</span></td>
                <td class="font-mono text-xs">{{ $b->vehicle_registration_no }}<br><span class="text-xs text-gray-400">{{ $b->vehicle?->name }}</span></td>
                <td class="text-xs">{{ $b->pickup_date->format('M d, Y') }} → {{ $b->return_date->format('M d, Y') }}</td>
                <td>FJD {{ number_format($b->total_amount, 2) }}</td>
                <td><span class="rz-badge bg-white/5">{{ ucfirst($b->status) }}</span></td>
                <td><span class="rz-badge bg-white/5">{{ ucfirst($b->billing?->status ?? '—') }}</span></td>
                <td><a href="{{ route('admin.bookings.show', $b) }}" class="rz-primary text-sm" data-testid="admin-view-booking-{{ $b->id }}">Open →</a></td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center py-6 text-gray-400">No bookings.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $bookings->links() }}</div>
@endsection
