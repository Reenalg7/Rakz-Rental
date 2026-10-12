@extends('layouts.admin')
@section('page_title', 'Audit Log')
@section('page_subtitle', 'Every tracked administrator action — security traceability')

@section('content')
<div class="flex justify-end mb-3">
    <a href="{{ route('admin.export.audit_logs', request()->only('q','action','role','from','to')) }}"
       class="rz-btn-ghost rounded-md px-3 py-1.5 text-sm" data-testid="export-audit-csv">
        <i class="fa-solid fa-file-csv mr-1"></i>Export CSV (current filters)
    </a>
</div>

<form method="GET" class="rz-card p-4 mb-4 grid grid-cols-1 md:grid-cols-6 gap-3">
    <div>
        <label class="text-xs text-gray-400">Keyword</label>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="name / target / details"
            class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="audit-search-keyword">
    </div>
    <div>
        <label class="text-xs text-gray-400">Action</label>
        <select name="action" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="audit-filter-action">
            <option value="">All actions</option>
            @foreach($actions as $a)
                <option value="{{ $a }}" @selected(request('action') === $a)>{{ $a }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-xs text-gray-400">Role</label>
        <select name="role" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="audit-filter-role">
            <option value="">All</option>
            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
            <option value="customer" @selected(request('role') === 'customer')>Customer</option>
        </select>
    </div>
    <div>
        <label class="text-xs text-gray-400">From</label>
        <input type="date" name="from" value="{{ request('from') }}" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="audit-filter-from">
    </div>
    <div>
        <label class="text-xs text-gray-400">To</label>
        <input type="date" name="to" value="{{ request('to') }}" class="rz-input w-full rounded-md px-3 py-2 mt-1" data-testid="audit-filter-to">
    </div>
    <div class="flex items-end gap-2">
        <button class="rz-btn rounded-md px-4 py-2" data-testid="audit-apply">Apply</button>
        <a href="{{ route('admin.audit_logs.index') }}" class="text-xs text-gray-400 self-center" data-testid="audit-reset">Reset</a>
    </div>
</form>

<div class="rz-card overflow-hidden">
    <table class="w-full rz-table text-sm">
        <thead><tr>
            <th class="text-left">When</th>
            <th class="text-left">Actor</th>
            <th class="text-left">Action</th>
            <th class="text-left">Target</th>
            <th class="text-left">Details</th>
            <th class="text-left">IP</th>
        </tr></thead>
        <tbody>
            @forelse($logs as $log)
                @php
                    $palette = [
                        'login'      => 'bg-emerald-400/20 text-emerald-200',
                        'logout'     => 'bg-gray-400/20 text-gray-200',
                        'register'   => 'bg-sky-400/20 text-sky-200',
                        'booking.create'   => 'bg-sky-400/20 text-sky-200',
                        'booking.cancel'   => 'bg-red-400/20 text-red-200',
                        'booking.status.update' => 'bg-amber-400/20 text-amber-200',
                        'booking.paid'     => 'bg-emerald-400/20 text-emerald-200',
                        'vehicle.create'   => 'bg-sky-400/20 text-sky-200',
                        'vehicle.update'   => 'bg-amber-400/20 text-amber-200',
                        'vehicle.retire'   => 'bg-red-400/20 text-red-200',
                        'vehicle.restore'  => 'bg-emerald-400/20 text-emerald-200',
                        'vehicle.replace'  => 'bg-[color:var(--rz-primary)]/20 text-[color:var(--rz-primary)]',
                        'scheduler.mark_completed' => 'bg-violet-400/20 text-violet-200',
                    ];
                    $cls = $palette[$log->action] ?? 'bg-white/10';
                @endphp
                <tr class="hover:bg-white/5" data-testid="audit-row-{{ $log->id }}">
                    <td class="text-xs whitespace-nowrap">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                    <td class="text-xs">
                        <div class="font-semibold">{{ $log->actor_name ?: 'System' }}</div>
                        <div class="text-gray-400">{{ ucfirst($log->actor_role ?: 'system') }}</div>
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
                    <td class="text-xs text-gray-400 font-mono">{{ $log->ip_address ?: '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-10 text-gray-400">No audit log entries yet. Perform an admin action and refresh.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $logs->links() }}</div>
@endsection
