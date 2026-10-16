@extends('layouts.admin')
@section('page_title', 'Replacement History')

@section('content')
<div class="rz-card overflow-x-auto">
    <table class="w-full rz-table text-sm">
        <thead><tr>
            <th>Date</th><th>Booking</th><th>Customer</th>
            <th>From / To</th><th>Reason</th><th>By</th>
        </tr></thead>
        <tbody>
        @forelse($replacements as $r)
            <tr class="hover:bg-snow">
                <td class="text-xs">{{ $r->created_at->format('M d, Y H:i') }}</td>
                <td><a href="{{ route('admin.bookings.show', $r->booking_id) }}" class="rz-link">#{{ $r->booking_id }}</a></td>
                <td class="text-xs">{{ $r->booking?->user?->name }}</td>
                <td class="font-mono text-xs">{{ $r->original_registration_no }} → <span class="rz-primary">{{ $r->replacement_registration_no }}</span></td>
                <td class="text-xs">{{ $r->reason ?: '—' }}</td>
                <td class="text-xs">{{ $r->replacedBy?->name ?? '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center py-6 text-muted">No replacements recorded.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $replacements->links() }}</div>
@endsection
