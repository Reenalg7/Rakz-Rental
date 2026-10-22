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
        // Ensure customers can only update their own notifications.
        abort_if((int) $notification->user_id !== (int) Auth::id(), 403);

        // Mark the notification as read.
        $notification->update([
            'read_at' => now(),
        ]);

        // Return to the notifications page.
        return redirect()->route('customer.notifications.index');
    }
}
