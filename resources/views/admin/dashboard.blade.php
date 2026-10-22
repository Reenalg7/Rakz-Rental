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


<!-- Fleet Utilization -->
<div class="rz-card p-5 mb-6">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-lg font-bold">Fleet Utilization</h2>
            <p class="text-sm text-gray-400">
                Overview of vehicle status across your fleet
            </p>
        </div>
        <i class="fa-solid fa-chart-column rz-primary text-xl"></i>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            [
                'label' => 'Available',
                'value' => $stats['available_vehicles'],
                'color' => 'bg-emerald-400'
            ],
            [
                'label' => 'Booked',
                'value' => $stats['booked_vehicles'],
                'color' => 'bg-sky-400'
            ],
            [
                'label' => 'Maintenance',
                'value' => $stats['maintenance_vehicles'],
                'color' => 'bg-amber-400'
            ],
            [
                'label' => 'Retired',
                'value' => $stats['retired_vehicles'],
                'color' => 'bg-rose-400'
            ],
        ] as $item)
            <div class="rounded-lg bg-white/5 p-4">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm text-gray-300">{{ $item['label'] }}</span>
                    <span class="font-bold">{{ $item['value'] }}</span>
                </div>

                <div class="w-full h-2 rounded-full bg-white/10 overflow-hidden">
                    <div
                        class="{{ $item['color'] }} h-2 rounded-full"
                        style="width: {{ $stats['total_vehicles'] > 0 ? min(100, ($item['value'] / $stats['total_vehicles']) * 100) : 0 }}%"
                    ></div>
                </div>

                <p class="text-xs text-gray-500 mt-2">
                    {{ $stats['total_vehicles'] > 0 ? number_format(($item['value'] / $stats['total_vehicles']) * 100, 1) : '0.0' }}% of total fleet
                </p>
            </div>
        @endforeach
    </div>

    <p class="text-xs text-gray-500 mt-4">
        Note: Booked counts vehicles with at least one pending or confirmed booking.
        It may overlap with other status categories and is not a measure of current occupancy.
    </p>
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
