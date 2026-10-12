<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('customer.notifications.index', compact('notifications'));
    }

    public function markRead(Notification $notification): RedirectResponse
    {
        abort_if($notification->user_id !== Auth::id(), 403);
        $notification->update(['read_at' => now()]);
        return back();
    }
}
