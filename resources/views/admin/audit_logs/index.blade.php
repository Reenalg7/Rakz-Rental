@extends('layouts.admin')
@section('page_title', 'Audit Log')
@section('page_subtitle', 'Every tracked administrator action — security traceability')

@section('content')
<div class="flex justify-end mb-3">
    <a href="{{ route('admin.export.audit_logs', request()->only('q','action','role','from','to')) }}"
       class="rz-btn-ghost rounded-md px-3 py-1.5 text-sm" data-testid="export-audit-csv">
        Export CSV (current filters)
    </a>
</div>

<form method="GET" class="rz-card p-4 mb-4 grid grid-cols-1 md:grid-cols-6 gap-3">
    <div>
        <label class="rz-label">Keyword</label>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="name / target / details"
            class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="audit-search-keyword">
    </div>
    <div>
        <label class="rz-label">Action</label>
        <select name="action" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="audit-filter-action">
            <option value="">All actions</option>
            @foreach($actions as $a)
                <option value="{{ $a }}" @selected(request('action') === $a)>{{ $a }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="rz-label">Role</label>
        <select name="role" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="audit-filter-role">
            <option value="">All</option>
            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
            <option value="customer" @selected(request('role') === 'customer')>Customer</option>
        </select>
    </div>
    <div>
        <label class="rz-label">From</label>
        <input type="date" name="from" value="{{ request('from') }}" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="audit-filter-from">
    </div>
    <div>
        <label class="rz-label">To</label>
        <input type="date" name="to" value="{{ request('to') }}" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="audit-filter-to">
    </div>
    <div class="flex items-end gap-2">
        <button class="rz-btn rounded-md px-4 py-2" data-testid="audit-apply">Apply</button>
        <a href="{{ route('admin.audit_logs.index') }}" class="text-xs text-muted self-center" data-testid="audit-reset">Reset</a>
    </div>
</form>

<div class="rz-card overflow-x-auto">
    <table class="w-full rz-table text-sm">
        <thead><tr>
            <th>When</th>
            <th>Actor</th>
            <th>Action</th>
            <th>Target</th>
            <th>Details</th>
            <th>IP</th>
        </tr></thead>
        <tbody>
            @forelse($logs as $log)
                @php
                    $palette = [
                        'login'      => 'bg-emerald-50 text-emerald-800',
                        'logout'     => 'bg-gray-100 text-gray-700',
                        'register'   => 'bg-tint text-brand-dark',
                        'booking.create'   => 'bg-tint text-brand-dark',
                        'booking.cancel'   => 'bg-red-50 text-red-800',
                        'booking.status.update' => 'bg-amber-50 text-amber-800',
                        'booking.paid'     => 'bg-emerald-50 text-emerald-800',
                        'vehicle.create'   => 'bg-tint text-brand-dark',
                        'vehicle.update'   => 'bg-amber-50 text-amber-800',
                        'vehicle.retire'   => 'bg-red-50 text-red-800',
                        'vehicle.restore'  => 'bg-emerald-50 text-emerald-800',
                        'vehicle.replace'  => 'bg-tint text-brand-dark',
                        'scheduler.mark_completed' => 'bg-tint text-brand-dark',
                    ];
                    $cls = $palette[$log->action] ?? 'bg-tint';
                @endphp
                <tr class="hover:bg-snow" data-testid="audit-row-{{ $log->id }}">
                    <td class="text-xs whitespace-nowrap">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                    <td class="text-xs">
                        <div class="font-semibold">{{ $log->actor_name ?: 'System' }}</div>
                        <div class="text-muted">{{ ucfirst($log->actor_role ?: 'system') }}</div>
                    </td>
                    <td><span class="rz-badge {{ $cls }}">{{ $log->action }}</span></td>
                    <td class="text-xs font-mono">
                        @if($log->target_type)
                            {{ class_basename($log->target_type) }}:{{ $log->target_id }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="text-xs max-w-md truncate" title="{{ $log->description }}">{{ $log->description }}</td>
                    <td class="text-xs text-muted font-mono">{{ $log->ip_address ?: '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-10 text-muted">No audit log entries yet. Perform an admin action and refresh.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $logs->links() }}</div>
@endsection
