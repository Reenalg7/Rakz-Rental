@extends('layouts.admin')
@section('page_title', 'Customers')

@section('content')
<form method="GET" class="flex gap-2 mb-4">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name / email / phone" class="rz-input rounded-md px-3 py-2 w-72" data-testid="admin-customers-search">
    <button class="rz-btn-ghost rounded-md px-4">Search</button>
</form>

<div class="rz-card overflow-hidden">
    <table class="w-full rz-table text-sm">
        <thead><tr><th class="text-left">Name</th><th class="text-left">Email</th><th class="text-left">Phone</th><th class="text-left">Bookings</th><th></th></tr></thead>
        <tbody>
        @forelse($customers as $c)
            <tr class="hover:bg-white/5">
                <td>{{ $c->name }}</td>
                <td class="text-xs">{{ $c->email }}</td>
                <td class="text-xs">{{ $c->phone ?: '—' }}</td>
                <td>{{ $c->bookings_count }}</td>
                <td><a href="{{ route('admin.customers.show', $c) }}" class="rz-primary text-sm">View →</a></td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center py-6 text-gray-400">No customers.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $customers->links() }}</div>
@endsection
