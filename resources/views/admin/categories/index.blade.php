@extends('layouts.admin')
@section('page_title', 'Categories')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 rz-card overflow-hidden">
        <table class="w-full rz-table text-sm">
            <thead><tr><th class="text-left">Name</th><th class="text-left">Description</th><th class="text-left">Vehicles</th><th></th></tr></thead>
            <tbody>
            @forelse($categories as $cat)
                <tr class="hover:bg-white/5">
                    <td class="font-semibold">{{ $cat->name }}</td>
                    <td class="text-xs text-gray-400">{{ $cat->description ?: '—' }}</td>
                    <td>{{ $cat->vehicles_count }}</td>
                    <td class="flex gap-2">
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete category?');">
                            @csrf @method('DELETE')
                            <button class="rz-btn-ghost rounded px-2 py-1 text-xs text-red-300" data-testid="delete-cat-{{ $cat->id }}">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-6 text-gray-400">No categories.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="rz-card p-6">
        <h3 class="font-bold mb-3">Add category</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-3">
            @csrf
            <input type="text" name="name" placeholder="Name (e.g. Pickup)" required class="rz-input w-full rounded-md px-3 py-2" data-testid="cat-name">
            <input type="text" name="description" placeholder="Description" class="rz-input w-full rounded-md px-3 py-2" data-testid="cat-description">
            <button class="rz-btn w-full rounded-md py-2" data-testid="cat-add">Add</button>
        </form>
    </div>
</div>
@endsection
