@extends('layouts.admin')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Overview of fleet activity, bookings, revenue and replacements')

@section('content')
@php
    $colors = ['pending'=>'bg-amber-50 text-amber-800','confirmed'=>'bg-emerald-50 text-emerald-800','cancelled'=>'bg-red-50 text-red-800','completed'=>'bg-tint text-brand-dark'];
@endphp

<!-- Revenue -->
<div class="rounded-lg bg-tint border border-line p-6 mb-8">
    <div class="text-sm text-muted">Total revenue (confirmed + completed)</div>
    <div class="text-4xl font-semibold text-brand-dark mt-1">FJD {{ number_format($stats['revenue'], 2) }}</div>
</div>

<!-- Fleet and customers -->
<h2 class="text-sm font-semibold text-muted mb-3">Fleet and customers</h2>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['label'=>'Total vehicles','value'=>$stats['total_vehicles']],
        ['label'=>'Available','value'=>$stats['available_vehicles']],
        ['label'=>'Maintenance','value'=>$stats['maintenance_vehicles']],
        ['label'=>'Customers','value'=>$stats['total_customers']],
    ] as $s)
        <div class="rz-card p-5">
            <div class="text-sm text-muted">{{ $s['label'] }}</div>
            <div class="text-3xl font-semibold mt-1">{{ $s['value'] }}</div>
        </div>
    @endforeach
</div>

<!-- Bookings -->
<h2 class="text-sm font-semibold text-muted mb-3">Bookings and replacements</h2>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['label'=>'Total bookings','value'=>$stats['total_bookings']],
        ['label'=>'Pending','value'=>$stats['pending_bookings']],
        ['label'=>'Confirmed','value'=>$stats['confirmed_bookings']],
        ['label'=>'Replacements','value'=>$stats['total_replacements']],
    ] as $s)
        <div class="rz-card p-5">
            <div class="text-sm text-muted">{{ $s['label'] }}</div>
            <div class="text-3xl font-semibold mt-1">{{ $s['value'] }}</div>
        </div>
    @endforeach
</div>

<!-- Recent -->
<div class="rz-card overflow-x-auto">
    <div class="flex items-center justify-between px-5 py-4 border-b border-line">
        <h2 class="text-lg font-semibold">Recent bookings</h2>
        <a href="{{ route('admin.bookings.index') }}" class="text-sm rz-link">View all</a>
    </div>
    @if($recentBookings->isEmpty())
        <p class="text-sm text-muted p-5">No bookings yet.</p>
    @else
        <div class="overflow-x-auto">
        <table class="w-full rz-table text-sm">
            <thead><tr>
                <th>#</th><th>Customer</th><th>Vehicle</th><th>Dates</th><th>Total</th><th>Status</th>
            </tr></thead>
            <tbody>
                @foreach($recentBookings as $b)
                    <tr>
                        <td><a href="{{ route('admin.bookings.show', $b) }}" class="rz-link">#{{ $b->id }}</a></td>
                        <td>{{ $b->user->name }}</td>
                        <td><span class="rz-plate">{{ $b->vehicle_registration_no }}</span><br><span class="text-xs text-muted">{{ $b->vehicle?->name }}</span></td>
                        <td class="text-xs whitespace-nowrap">{{ $b->pickup_date->format('M d') }} – {{ $b->return_date->format('M d') }}</td>
                        <td class="whitespace-nowrap">FJD {{ number_format($b->total_amount, 0) }}</td>
                        <td><span class="rz-badge {{ $colors[$b->status] ?? '' }}">{{ ucfirst($b->status) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>
@endsection
