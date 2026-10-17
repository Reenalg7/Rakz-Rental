<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmed;
use App\Models\Booking;
use App\Models\Notification;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $q = Booking::with(['user', 'vehicle', 'billing']);

        if ($status = $request->input('status')) {
            $q->where('status', $status);
        }
        if ($keyword = trim((string) $request->input('q'))) {
            $q->where(function ($qq) use ($keyword) {
                $qq->where('vehicle_registration_no', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($u) use ($keyword) {
                        $u->where('name', 'like', "%{$keyword}%")
                          ->orWhere('email', 'like', "%{$keyword}%");
                    });
            });
        }

        $bookings = $q->latest()->paginate(15)->withQueryString();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        $booking->load(['user', 'vehicle.category', 'originalVehicle', 'billing', 'replacements.replacementVehicle', 'replacements.replacedBy']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled,completed'],
        ]);

            // Prevent completing a booking before payment is recorded.
        if (
            $data['status'] === 'completed'
            && (!$booking->billing || $booking->billing->status !== 'paid')
        ) {
            return back()->with(
                'error',
                'This booking cannot be completed until payment has been recorded.'
            );
        }

        $old = $booking->status;
        $booking->update(['status' => $data['status']]);

        AuditLogger::log(
            'booking.status.update',
            "Booking #{$booking->id} status changed from {$old} to {$data['status']}",
            Booking::class, (string) $booking->id
        );

        // Keep billing status in sync for cancellations.
        if ($data['status'] === 'cancelled' && $booking->billing && $booking->billing->status === 'unpaid') {
            $booking->billing->update(['status' => 'refunded']);
        }

        // Notify the customer of status change.
        Notification::create([
            'user_id' => $booking->user_id,
            'title' => 'Booking '.ucfirst($data['status']),
            'message' => "Your booking #{$booking->id} status changed from {$old} to {$data['status']}.",
            'type' => $data['status'] === 'confirmed' ? 'success' : 'info',
        ]);

        // Also email the customer when the admin confirms the booking.
        if ($data['status'] === 'confirmed' && $old !== 'confirmed') {
            try {
                Mail::to($booking->user->email)->send(new BookingConfirmed($booking, 'confirmed'));
            } catch (\Throwable $e) {
                Log::warning('Booking confirmation email failed: '.$e->getMessage());
            }
        }

        return back()->with('success', 'Booking status updated to '.$data['status'].'.');
    }

    
public function markPaid(Booking $booking): RedirectResponse
{
    // Make sure this booking has a billing record.
    $billing = $booking->billing;

    if (!$billing) {
        return back()->with('error', 'No billing record exists for this booking.');
    }

    // Do not accept payment for a cancelled booking.
    if ($booking->status === 'cancelled') {
        return back()->with('error', 'Payment cannot be recorded for a cancelled booking.');
    }

    // Prevent recording the same payment twice.
    if ($billing->status === 'paid') {
        return back()->with('error', 'This booking has already been paid.');
    }

    // Record the in-person cash payment.
    $billing->update([
        'status' => 'paid',
        'paid_at' => now(),
        'payment_method' => 'cash',
        'reference_no' => 'CASH-' . strtoupper(\Illuminate\Support\Str::random(10)),
    ]);

    // Record the action in the audit log.
    AuditLogger::log(
        'booking.paid',
        "Cash payment recorded for booking #{$booking->id} — FJD {$billing->amount}",
        Booking::class,
        (string) $booking->id
    );

    return back()->with('success', 'Cash payment recorded successfully.');
}

}
