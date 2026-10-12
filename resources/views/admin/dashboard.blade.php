@extends('layouts.admin')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Overview of fleet activity, bookings, revenue and replacements')

@section('content')
<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label'=>'Total Vehicles','value'=>$stats['total_vehicles'],'icon'=>'fa-car','color'=>'text-sky-300'],
        ['label'=>'Available','value'=>$stats['available_vehicles'],'icon'=>'fa-circle-check','color'=>'text-emerald-300'],
        ['label'=>'Maintenance','value'=>$stats['maintenance_vehicles'],'icon'=>'fa-wrench','color'=>'text-amber-300'],
        ['label'=>'Customers','value'=>$stats['total_customers'],'icon'=>'fa-users','color'=>'text-violet-300'],
    ] as $s)
        <div class="rz-card p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center {{ $s['color'] }}">
                    <i class="fa-solid {{ $s['icon'] }}"></i>
                </div>
                <div>
                    <div class="text-xs text-gray-400">{{ $s['label'] }}</div>
                    <div class="text-2xl font-bold">{{ $s['value'] }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label'=>'Total Bookings','value'=>$stats['total_bookings'],'icon'=>'fa-calendar','color'=>'text-sky-300'],
        ['label'=>'Pending','value'=>$stats['pending_bookings'],'icon'=>'fa-hourglass','color'=>'text-amber-300'],
        ['label'=>'Confirmed','value'=>$stats['confirmed_bookings'],'icon'=>'fa-check','color'=>'text-emerald-300'],
        ['label'=>'Replacements','value'=>$stats['total_replacements'],'icon'=>'fa-shuffle','color'=>'rz-primary'],
    ] as $s)
        <div class="rz-card p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center {{ $s['color'] }}">
                    <i class="fa-solid {{ $s['icon'] }}"></i>
                </div>
                <div>
                    <div class="text-xs text-gray-400">{{ $s['label'] }}</div>
                    <div class="text-2xl font-bold">{{ $s['value'] }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="rz-card p-5 mb-6">
    <div class="text-xs text-gray-400">Total revenue (confirmed + completed)</div>
    <div class="text-3xl font-bold rz-primary">FJD {{ number_format($stats['revenue'], 2) }}</div>
</div>

<!-- Recent -->
<div class="rz-card p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold">Recent bookings</h2>
        <a href="{{ route('admin.bookings.index') }}" class="text-sm rz-primary">View all →</a>
    </div>
    @if($recentBookings->isEmpty())
        <p class="text-sm text-gray-400">No bookings yet.</p>
    @else
        <table class="w-full rz-table text-sm">
            <thead><tr>
                <th class="text-left">#</th><th class="text-left">Customer</th><th class="text-left">Vehicle</th>
                <th class="text-left">Dates</th><th class="text-left">Total</th><th class="text-left">Status</th>
            </tr></thead>
            <tbody>
                @foreach($recentBookings as $b)
                    <tr class="hover:bg-white/5">
                        <td><a href="{{ route('admin.bookings.show', $b) }}" class="rz-primary">#{{ $b->id }}</a></td>
                        <td>{{ $b->user->name }}</td>
                        <td><span class="font-mono text-xs">{{ $b->vehicle_registration_no }}</span><br><span class="text-xs text-gray-400">{{ $b->vehicle?->name }}</span></td>
                        <td class="text-xs">{{ $b->pickup_date->format('M d') }} → {{ $b->return_date->format('M d') }}</td>
                        <td>FJD {{ number_format($b->total_amount, 0) }}</td>
                        <td><span class="rz-badge bg-white/5">{{ ucfirst($b->status) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
