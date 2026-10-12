@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<h1 class="text-3xl font-bold mb-6">Notifications</h1>
@if($notifications->isEmpty())
    <div class="rz-card p-10 text-center text-gray-400">No notifications yet.</div>
@else
    <div class="space-y-3">
        @foreach($notifications as $n)
            <div class="rz-card p-5 flex items-start gap-4 {{ $n->isUnread() ? 'border-l-4 border-[color:var(--rz-primary)]' : '' }}" data-testid="notif-{{ $n->id }}">
                <div><i class="fa-solid {{ $n->type === 'replacement' ? 'fa-shuffle' : 'fa-bell' }} rz-primary text-xl"></i></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between gap-2">
                        <h3 class="font-semibold">{{ $n->title }}</h3>
                        <span class="text-xs text-gray-400">{{ $n->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-300 mt-1">{{ $n->message }}</p>
                    @if($n->isUnread())
                        <form method="POST" action="{{ route('customer.notifications.read', $n) }}" class="mt-2">
                            @csrf
                            <button class="text-xs rz-primary" data-testid="notif-read-{{ $n->id }}">Mark as read</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $notifications->links() }}</div>
@endif
@endsection
