@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<h1 class="text-3xl font-semibold mb-6">Notifications</h1>
@if($notifications->isEmpty())
    <div class="rz-card p-10 text-center text-muted">No notifications yet.</div>
@else
    <div class="space-y-3">
        @foreach($notifications as $n)
            <div class="rz-card p-5 flex items-start gap-4 {{ $n->isUnread() ? 'border-l-4 border-[color:var(--rz-primary)]' : '' }}" data-testid="notif-{{ $n->id }}">
                <div class="flex-1">
                    <div class="flex items-center justify-between gap-2">
                        <h3 class="font-semibold">{{ $n->title }} @if($n->isUnread())<span class="rz-badge ml-2 align-middle">New</span>@endif</h3>
                        <span class="text-xs text-muted">{{ $n->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-ink mt-1">{{ $n->message }}</p>
                    @if($n->isUnread())
                        <form method="POST" action="{{ route('customer.notifications.read', $n) }}" class="mt-2">
                            @csrf
                            <button class="text-sm rz-link" data-testid="notif-read-{{ $n->id }}">Mark as read</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $notifications->links() }}</div>
@endif
@endsection
