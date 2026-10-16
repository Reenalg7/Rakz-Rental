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
        Revenue CSV
    </a>
    <a href="{{ route('admin.export.utilization', ['from' => $from->toDateString(), 'to' => $to->toDateString()]) }}"
       class="rz-btn-ghost rounded-md px-3 py-1.5 text-sm" data-testid="export-utilization-csv">
        Utilization CSV
    </a>
    <a href="{{ route('admin.export.customers') }}" class="rz-btn-ghost rounded-md px-3 py-1.5 text-sm" data-testid="export-customers-csv">
        Top Customers CSV
    </a>
    <a href="{{ route('admin.export.bookings') }}" class="rz-btn-ghost rounded-md px-3 py-1.5 text-sm" data-testid="export-bookings-csv-all">
        All Bookings CSV
    </a>
</div>

<!-- Summary -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
    @foreach([
        ['label'=>'Revenue (12m)','value'=>'FJD '.number_format($summary['total_revenue_12m'],0)],
        ['label'=>'Bookings (12m)','value'=>$summary['bookings_12m']],
        ['label'=>'Fleet Size','value'=>$summary['fleet_size']],
        ['label'=>'Avg Utilization','value'=>$summary['avg_utilization'].'%'],
        ['label'=>'Replacements','value'=>$summary['replacements']],
        ['label'=>'Customers','value'=>$summary['customers']],
    ] as $s)
        <div class="rz-card p-4">
            <div class="text-sm text-muted">{{ $s['label'] }}</div>
            <div class="text-2xl font-semibold mt-1">{{ $s['value'] }}</div>
        </div>
    @endforeach
</div>

<!-- Revenue chart -->
<div class="rz-card p-6 mb-6">
    <h2 class="text-lg font-semibold mb-1">Revenue, last 12 months</h2>
    <p class="text-sm text-muted mb-4">Confirmed and completed bookings only.</p>
    <canvas id="revenueChart" height="80" data-testid="revenue-chart"></canvas>
</div>

<!-- Utilization + Top customers -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Fleet utilization -->
    <div class="rz-card p-6 lg:col-span-2">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div>
                <h2 class="text-lg font-semibold">Fleet utilization</h2>
                <p class="text-xs text-muted">% of days booked between {{ $from->format('M d, Y') }} and {{ $to->format('M d, Y') }} ({{ $windowDays }} day window)</p>
            </div>
            <form method="GET" class="flex flex-wrap gap-2 text-sm">
                <input type="date" name="from" value="{{ $from->toDateString() }}" class="rz-input rounded-md px-2 py-1" data-testid="report-from">
                <input type="date" name="to" value="{{ $to->toDateString() }}" class="rz-input rounded-md px-2 py-1" data-testid="report-to">
                <button class="rz-btn px-4" data-testid="report-apply">Apply</button>
            </form>
        </div>

        <div class="overflow-x-auto"><table class="w-full rz-table text-sm">
            <thead><tr>
                <th>Reg no</th><th>Vehicle</th>
                <th>Bookings</th><th>Days booked</th><th>Utilization</th>
            </tr></thead>
            <tbody>
                @forelse($utilization as $u)
                    <tr class="hover:bg-snow">
                        <td><span class="rz-plate">{{ $u->registration_no }}</span></td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->booking_count }}</td>
                        <td>{{ $u->booked_days }} / {{ $windowDays }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 rounded-full bg-tint overflow-hidden min-w-[80px]">
                                    <div class="h-full rz-bg-primary" style="width: {{ min(100, $u->utilization_pct) }}%"></div>
                                </div>
                                <span class="text-xs w-12 text-right">{{ $u->utilization_pct }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-6 text-muted">No vehicles yet.</td></tr>
                @endforelse
            </tbody>
        </table></div>
    </div>

    <!-- Top customers -->
    <div class="rz-card p-6">
        <h2 class="text-lg font-semibold mb-3">Top customers</h2>
        @if($topCustomers->isEmpty())
            <p class="text-sm text-muted">No customers yet.</p>
        @else
            <ul class="space-y-3">
                @foreach($topCustomers as $i => $c)
                    <li class="flex items-center gap-3" data-testid="top-customer-{{ $c->id }}">
                        <div class="w-8 h-8 rounded-full bg-tint flex items-center justify-center text-xs font-semibold text-brand-dark">{{ $i + 1 }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold truncate">{{ $c->name }}</div>
                            <div class="text-xs text-muted truncate">{{ $c->email }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold rz-primary">FJD {{ number_format($c->total_spent, 0) }}</div>
                            <div class="text-xs text-muted">{{ $c->bookings_count }} booking(s)</div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<!-- Bookings by category -->
<div class="rz-card p-6">
    <h2 class="text-lg font-semibold mb-3">Bookings by category</h2>
    @if($byCategory->isEmpty())
        <p class="text-sm text-muted">No bookings yet.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <canvas id="categoryChart" height="150" data-testid="category-chart"></canvas>
            <table class="w-full rz-table text-sm">
                <thead><tr><th>Category</th><th>Bookings</th><th>Revenue</th></tr></thead>
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
                borderColor: '#005691',
                backgroundColor: 'rgba(0,86,145,0.08)',
                borderWidth: 2,
                tension: 0.35,
                fill: true,
                pointBackgroundColor: '#005691',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#506576' } } },
            scales: {
                x: { ticks: { color: '#506576' }, grid: { color: '#E8F1F5' } },
                y: { ticks: { color: '#506576' }, grid: { color: '#E8F1F5' } }
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
                backgroundColor: ['#004A7C','#005691','#2F7BAE','#6BA3CB','#A5C8DF','#CFE0EA','#7A94A6'],
                borderColor: '#FFFFFF',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'right', labels: { color: '#14232F' } } }
        }
    });
}
@endif
</script>
@endpush
@endsection
