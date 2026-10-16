@extends('layouts.admin')
@section('page_title', 'Vehicles')
@section('page_subtitle', 'Fleet keyed by registration number')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <form method="GET" class="flex flex-wrap gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by reg no / name"
            class="rz-input rounded-md px-3 py-2 w-full sm:w-64" data-testid="admin-vehicles-search">
        <select name="status" class="rz-input rounded-md px-3 py-2" data-testid="admin-vehicles-status">
            <option value="">All status</option>
            @foreach(['available','maintenance','retired'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="rz-btn px-4" data-testid="admin-vehicles-search-btn">Search</button>
    </form>
    <a href="{{ route('admin.vehicles.create') }}" class="rz-btn rounded-md px-4 py-2 text-sm" data-testid="admin-add-vehicle">
        Add vehicle
    </a>
</div>

<div class="rz-card overflow-x-auto">
    <table class="w-full rz-table text-sm">
        <thead><tr>
            <th>Reg no</th><th>Vehicle</th><th>Category</th>
            <th>Daily Rate</th><th>Status</th><th>Actions</th>
        </tr></thead>
        <tbody>
            @forelse($vehicles as $v)
                <tr class="hover:bg-snow" data-testid="vehicle-row-{{ $v->registration_no }}">
                    <td><span class="rz-plate">{{ $v->registration_no }}</span></td>
                    <td>{{ $v->name }}<br><span class="text-xs text-muted">{{ $v->make }} · {{ $v->year }}</span></td>
                    <td>{{ $v->category?->name ?? '-' }}</td>
                    <td>FJD {{ number_format($v->daily_rate, 2) }}</td>
                    <td>
                        @php $colors = ['available'=>'bg-emerald-50 text-emerald-800','maintenance'=>'bg-amber-50 text-amber-800','retired'=>'bg-red-50 text-red-800']; @endphp
                        <span class="rz-badge {{ $colors[$v->status] ?? '' }}">{{ ucfirst($v->status) }}</span>
                    </td>
                    <td><div class="flex gap-2 items-center">
                        <a href="{{ route('admin.vehicles.datasheet', $v->registration_no) }}" target="_blank"
                           class="rz-btn-ghost rounded px-2.5 py-1 text-xs" data-testid="datasheet-{{ $v->registration_no }}" title="Printable datasheet">Datasheet</a>
                        <a href="{{ route('admin.vehicles.edit', $v->registration_no) }}" class="rz-btn-ghost rounded px-2.5 py-1 text-xs" data-testid="edit-vehicle-{{ $v->registration_no }}">Edit</a>
                        @if($v->status !== 'retired')
                            <form method="POST" action="{{ route('admin.vehicles.retire', $v->registration_no) }}" onsubmit="return confirm('Retire this vehicle? History will be preserved.');">
                                @csrf
                                <button class="rz-btn-ghost rounded px-2.5 py-1 text-xs text-red-700" data-testid="retire-vehicle-{{ $v->registration_no }}">Retire</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.vehicles.restore', $v->registration_no) }}">
                                @csrf
                                <button class="rz-btn-ghost rounded px-2.5 py-1 text-xs text-emerald-700" data-testid="restore-vehicle-{{ $v->registration_no }}">Restore</button>
                            </form>
                        @endif
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-6 text-muted">No vehicles.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $vehicles->links() }}</div>
@endsection
