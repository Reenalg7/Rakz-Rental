@extends('layouts.admin')
@section('page_title', 'Vehicles')
@section('page_subtitle', 'Fleet keyed by registration number')

@section('content')
<div class="flex items-center justify-between mb-4">
    <form method="GET" class="flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by reg no / name"
            class="rz-input rounded-md px-3 py-2 w-64" data-testid="admin-vehicles-search">
        <select name="status" class="rz-input rounded-md px-3 py-2" data-testid="admin-vehicles-status">
            <option value="">All status</option>
            @foreach(['available','maintenance','retired'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="rz-btn-ghost rounded-md px-4" data-testid="admin-vehicles-search-btn">Search</button>
    </form>
    <a href="{{ route('admin.vehicles.create') }}" class="rz-btn rounded-md px-4 py-2 text-sm" data-testid="admin-add-vehicle">
        <i class="fa-solid fa-plus mr-1"></i>Add vehicle
    </a>
</div>

<div class="rz-card overflow-hidden">
    <table class="w-full rz-table text-sm">
        <thead><tr>
            <th class="text-left">Reg No (PK)</th><th class="text-left">Vehicle</th><th class="text-left">Category</th>
            <th class="text-left">Daily Rate</th><th class="text-left">Status</th><th class="text-left">Actions</th>
        </tr></thead>
        <tbody>
            @forelse($vehicles as $v)
                <tr class="hover:bg-white/5" data-testid="vehicle-row-{{ $v->registration_no }}">
                    <td class="font-mono rz-primary">{{ $v->registration_no }}</td>
                    <td>{{ $v->name }}<br><span class="text-xs text-gray-400">{{ $v->make }} · {{ $v->year }}</span></td>
                    <td>{{ $v->category?->name ?? '-' }}</td>
                    <td>FJD {{ number_format($v->daily_rate, 2) }}</td>
                    <td>
                        @php $colors = ['available'=>'bg-emerald-400/20 text-emerald-200','maintenance'=>'bg-amber-400/20 text-amber-200','retired'=>'bg-red-400/20 text-red-200']; @endphp
                        <span class="rz-badge {{ $colors[$v->status] ?? '' }}">{{ ucfirst($v->status) }}</span>
                    </td>
                    <td class="flex gap-2">
                        <a href="{{ route('admin.vehicles.datasheet', $v->registration_no) }}" target="_blank"
                           class="rz-btn-ghost rounded px-2 py-1 text-xs" data-testid="datasheet-{{ $v->registration_no }}" title="Printable datasheet">
                            <i class="fa-solid fa-print"></i>
                        </a>
                        <a href="{{ route('admin.vehicles.edit', $v->registration_no) }}" class="rz-btn-ghost rounded px-2 py-1 text-xs" data-testid="edit-vehicle-{{ $v->registration_no }}">Edit</a>
                        @if($v->status !== 'retired')
                            <form method="POST" action="{{ route('admin.vehicles.retire', $v->registration_no) }}" onsubmit="return confirm('Retire this vehicle? History will be preserved.');">
                                @csrf
                                <button class="rz-btn-ghost rounded px-2 py-1 text-xs text-red-300" data-testid="retire-vehicle-{{ $v->registration_no }}">Retire</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.vehicles.restore', $v->registration_no) }}">
                                @csrf
                                <button class="rz-btn-ghost rounded px-2 py-1 text-xs text-emerald-300" data-testid="restore-vehicle-{{ $v->registration_no }}">Restore</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-6 text-gray-400">No vehicles.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $vehicles->links() }}</div>
@endsection
