<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Billing;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Show checkout for the customer's own booking.
     */
    public function checkout(Booking $booking): View
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $booking->load('billing');

        abort_if(!$booking->billing, 404);

        return view('customer.payments.checkout', compact('booking'));
    }

    /**
     * Simulate a test payment.
     * This does NOT process real money.
     */
    public function process(Request $request, Booking $booking): RedirectResponse
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'result' => ['required', 'in:success,failed'],
        ]);

        $result = DB::transaction(function () use ($booking, $data) {
            $billing = Billing::where('booking_id', $booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($billing->status === 'paid') {
                return 'already_paid';
            }

            if ($booking->status === 'cancelled') {
                return 'cancelled';
            }

            if ($data['result'] === 'failed') {
                $billing->update([
                    'payment_method' => 'simulated_test',
                    'reference_no' => 'TEST-FAIL-' . Str::upper(Str::random(10)),
                ]);

                return 'failed';
            }

            $billing->update([
                'status' => 'paid',
                'payment_method' => 'simulated_test',
                'reference_no' => 'TEST-' . Str::upper(Str::random(10)),
                'paid_at' => now(),
            ]);

            return 'success';
        });

        if ($result === 'success') {
            AuditLogger::log(
                'payment.test_success',
                "Simulated test payment completed for booking #{$booking->id}",
                Booking::class,
                (string) $booking->id
            );

            return redirect()
                ->route('customer.bookings.show', $booking)
                ->with('success', 'Test payment successful. No real money was charged.');
        }

        if ($result === 'failed') {
            return back()->with('error', 'Test payment failed. Your bill remains unpaid.');
        }

        if ($result === 'cancelled') {
            return back()->with('error', 'Cancelled bookings cannot be paid.');
        }

        return redirect()
            ->route('customer.bookings.show', $booking)
            ->with('success', 'This booking has already been paid.');
    }
}
