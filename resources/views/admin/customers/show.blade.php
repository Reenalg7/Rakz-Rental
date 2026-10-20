@extends('layouts.admin')
@section('page_title', $customer->name)

@section('content')
<a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-400 mb-4 inline-block"><i class="fa-solid fa-arrow-left mr-1"></i>Back</a>

<div class="rz-card p-6 mb-6 flex items-start justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold">{{ $customer->name }}</h2>
        <div class="text-sm text-gray-400">{{ $customer->email }} · {{ $customer->phone ?: 'No phone' }}</div>
        <div class="text-xs text-gray-400 mt-1">NIC: {{ $customer->nic ?: '—' }} · License: {{ $customer->driver_license ?: '—' }}</div>
        <div class="text-xs text-gray-400 mt-1">Address: {{ $customer->address ?: '—' }}</div>
    </div>
    <div class="flex items-center gap-2 shrink-0">
        <a href="{{ route('admin.customers.edit', $customer) }}" class="rz-btn rounded-md px-4 py-2 text-sm" data-testid="edit-customer">
            <i class="fa-solid fa-pen mr-1"></i>Edit
        </a>
        @if($customer->bookings->count() > 0)
            <button type="button" disabled class="rz-btn-ghost rounded-md px-4 py-2 text-sm text-red-300 opacity-40 cursor-not-allowed"
                title="Cannot delete: this customer has booking history" data-testid="delete-customer">
                <i class="fa-solid fa-trash mr-1"></i>Delete
            </button>
        @else
            <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}"
                onsubmit="return confirm(@js('Delete '.$customer->name.'? This cannot be undone.'));">
                @csrf
                @method('DELETE')
                <button class="rz-btn-ghost rounded-md px-4 py-2 text-sm text-red-300" data-testid="delete-customer">
                    <i class="fa-solid fa-trash mr-1"></i>Delete
                </button>
            </form>
        @endif
    </div>
</div>
@if($customer->bookings->count() > 0)
    <p class="text-xs text-gray-400 -mt-3 mb-6">This customer has booking history, so the account cannot be deleted (records and revenue reports are kept intact).</p>
@endif

<div class="rz-card overflow-hidden">
    <table class="w-full rz-table text-sm">
        <thead><tr><th class="text-left">#</th><th class="text-left">Vehicle</th><th class="text-left">Dates</th><th class="text-left">Total</th><th class="text-left">Status</th></tr></thead>
        <tbody>
        @forelse($customer->bookings as $b)
            <tr class="hover:bg-white/5">
                <td><a href="{{ route('admin.bookings.show', $b) }}" class="rz-primary">#{{ $b->id }}</a></td>
                <td class="font-mono text-xs">{{ $b->vehicle_registration_no }}</td>
                <td class="text-xs">{{ $b->pickup_date->format('M d') }} → {{ $b->return_date->format('M d') }}</td>
                <td>FJD {{ number_format($b->total_amount, 0) }}</td>
                <td>{{ ucfirst($b->status) }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center py-6 text-gray-400">No bookings.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
