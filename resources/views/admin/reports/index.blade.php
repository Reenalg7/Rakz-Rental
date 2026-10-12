@extends('layouts.admin')
@section('page_title', 'Reports')
@section('page_subtitle', 'Revenue, utilization and customer analytics')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
<!-- CSV export toolbar -->
<div class="flex flex-wrap gap-2 justify-end mb-4">
    <a href="{{ route('admin.export.revenue') }}" class="rz-btn-ghost rounded-md px-3 py-1.5 text-sm" data-testid="export-revenue-csv">
        <i class="fa-solid fa-file-csv mr-1"></i>Revenue CSV
    </a>
    <a href="{{ route('admin.export.utilization', ['from' => $from->toDateString(), 'to' => $to->toDateString()]) }}"
       class="rz-btn-ghost rounded-md px-3 py-1.5 text-sm" data-testid="export-utilization-csv">
        <i class="fa-solid fa-file-csv mr-1"></i>Utilization CSV
    </a>
    <a href="{{ route('admin.export.customers') }}" class="rz-btn-ghost rounded-md px-3 py-1.5 text-sm" data-testid="export-customers-csv">
        <i class="fa-solid fa-file-csv mr-1"></i>Top Customers CSV
    </a>
    <a href="{{ route('admin.export.bookings') }}" class="rz-btn-ghost rounded-md px-3 py-1.5 text-sm" data-testid="export-bookings-csv-all">
        <i class="fa-solid fa-file-csv mr-1"></i>All Bookings CSV
    </a>
</div>

<!-- Summary -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
    @foreach([
        ['label'=>'Revenue (12m)','value'=>'FJD '.number_format($summary['total_revenue_12m'],0),'icon'=>'fa-coins','color'=>'rz-primary'],
        ['label'=>'Bookings (12m)','value'=>$summary['bookings_12m'],'icon'=>'fa-calendar','color'=>'text-sky-300'],
        ['label'=>'Fleet Size','value'=>$summary['fleet_size'],'icon'=>'fa-car','color'=>'text-emerald-300'],
        ['label'=>'Avg Utilization','value'=>$summary['avg_utilization'].'%','icon'=>'fa-gauge-high','color'=>'text-amber-300'],
        ['label'=>'Replacements','value'=>$summary['replacements'],'icon'=>'fa-shuffle','color'=>'text-violet-300'],
        ['label'=>'Customers','value'=>$summary['customers'],'icon'=>'fa-users','color'=>'text-pink-300'],
    ] as $s)
        <div class="rz-card p-4">
            <div class="flex items-center gap-2">
                <i class="fa-solid {{ $s['icon'] }} {{ $s['color'] }}"></i>
                <div class="text-xs text-gray-400">{{ $s['label'] }}</div>
            </div>
            <div class="text-xl font-bold mt-1">{{ $s['value'] }}</div>
        </div>
    @endforeach
</div>

<!-- Revenue chart -->
<div class="rz-card p-6 mb-6">
    <h2 class="text-lg font-bold mb-1">Revenue — last 12 months</h2>
    <p class="text-xs text-gray-400 mb-4">Confirmed + completed bookings only.</p>
    <canvas id="revenueChart" height="80" data-testid="revenue-chart"></canvas>
</div>

<!-- Utilization + Top customers -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Fleet utilization -->
    <div class="rz-card p-6 lg:col-span-2">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h2 class="text-lg font-bold">Fleet utilization</h2>
                <p class="text-xs text-gray-400">% of days booked between {{ $from->format('M d, Y') }} and {{ $to->format('M d, Y') }} ({{ $windowDays }} day window)</p>
            </div>
            <form method="GET" class="flex gap-2 text-sm">
                <input type="date" name="from" value="{{ $from->toDateString() }}" class="rz-input rounded-md px-2 py-1" data-testid="report-from">
                <input type="date" name="to" value="{{ $to->toDateString() }}" class="rz-input rounded-md px-2 py-1" data-testid="report-to">
                <button class="rz-btn-ghost rounded-md px-3" data-testid="report-apply">Apply</button>
            </form>
        </div>

        <table class="w-full rz-table text-sm">
            <thead><tr>
                <th class="text-left">Reg No</th><th class="text-left">Vehicle</th>
                <th class="text-left">Bookings</th><th class="text-left">Days booked</th><th class="text-left">Utilization</th>
            </tr></thead>
            <tbody>
                @forelse($utilization as $u)
                    <tr class="hover:bg-white/5">
                        <td class="font-mono rz-primary">{{ $u->registration_no }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->booking_count }}</td>
                        <td>{{ $u->booked_days }} / {{ $windowDays }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 rounded-full bg-white/10 overflow-hidden min-w-[80px]">
                                    <div class="h-full rz-bg-primary" style="width: {{ min(100, $u->utilization_pct) }}%"></div>
                                </div>
                                <span class="text-xs w-12 text-right">{{ $u->utilization_pct }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-6 text-gray-400">No vehicles yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Top customers -->
    <div class="rz-card p-6">
        <h2 class="text-lg font-bold mb-3">Top customers</h2>
        @if($topCustomers->isEmpty())
            <p class="text-sm text-gray-400">No customers yet.</p>
        @else
            <ul class="space-y-3">
                @foreach($topCustomers as $i => $c)
                    <li class="flex items-center gap-3" data-testid="top-customer-{{ $c->id }}">
                        <div class="w-8 h-8 rounded-full bg-black/40 flex items-center justify-center text-xs font-bold rz-primary">{{ $i + 1 }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold truncate">{{ $c->name }}</div>
                            <div class="text-xs text-gray-400 truncate">{{ $c->email }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold rz-primary">FJD {{ number_format($c->total_spent, 0) }}</div>
                            <div class="text-xs text-gray-400">{{ $c->bookings_count }} booking(s)</div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<!-- Bookings by category -->
<div class="rz-card p-6">
    <h2 class="text-lg font-bold mb-3">Bookings by category</h2>
    @if($byCategory->isEmpty())
        <p class="text-sm text-gray-400">No bookings yet.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <canvas id="categoryChart" height="150" data-testid="category-chart"></canvas>
            <table class="w-full rz-table text-sm">
                <thead><tr><th class="text-left">Category</th><th class="text-left">Bookings</th><th class="text-left">Revenue</th></tr></thead>
                <tbody>
                    @foreach($byCategory as $cat)
                        <tr>
                            <td>{{ $cat->name }}</td>
                            <td>{{ $cat->cnt }}</td>
                            <td class="rz-primary">FJD {{ number_format($cat->revenue, 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@push('scripts')
<script>
// Revenue line chart
const revCtx = document.getElementById('revenueChart');
if (revCtx) {
    new Chart(revCtx, {
        type: 'line',
        data: {
            labels: @json(array_column($revenue, 'month')),
            datasets: [{
                label: 'Revenue (FJD)',
                data: @json(array_column($revenue, 'total')),
                borderColor: '#D4AF37',
                backgroundColor: 'rgba(212,175,55,0.15)',
                borderWidth: 2,
                tension: 0.35,
                fill: true,
                pointBackgroundColor: '#D4AF37',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#9CA3AF' } } },
            scales: {
                x: { ticks: { color: '#9CA3AF' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                y: { ticks: { color: '#9CA3AF' }, grid: { color: 'rgba(255,255,255,0.05)' } }
            }
        }
    });
}

// Category doughnut
const catCtx = document.getElementById('categoryChart');
@if($byCategory->isNotEmpty())
if (catCtx) {
    new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: @json($byCategory->pluck('name')),
            datasets: [{
                data: @json($byCategory->pluck('cnt')),
                backgroundColor: ['#D4AF37','#60A5FA','#34D399','#F472B6','#A78BFA','#FBBF24','#F87171']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'right', labels: { color: '#F9FAFB' } } }
        }
    });
}
@endif
</script>
@endpush
@endsection
