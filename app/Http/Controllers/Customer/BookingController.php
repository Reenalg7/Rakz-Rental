<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmed;
use App\Models\Booking;
use App\Models\Billing;
use App\Models\Vehicle;
use App\Support\AuditLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * BookingController (customer-facing)
 * ---------------------------------------------------------------------------
 *   Select Vehicle -> Pick Dates -> Check Availability -> Calculate Price
 *   -> Confirm -> Store Booking -> Show Confirmation
 *
 * Overlap prevention is enforced in the backend inside a DB transaction with
 * a row lock on existing bookings for that vehicle. This is a university
 * project, so we keep the lock simple (SELECT ... FOR UPDATE on the overlap
 * query) rather than a full queueing system.
 */
class BookingController extends Controller
{
    /**  1: show the booking form for a given vehicle. */
    public function create(Vehicle $vehicle): View
    {
        abort_if($vehicle->status !== 'available', 404);
        return view('customer.bookings.create', compact('vehicle'));
    }

    /** AJAX endpoint: check availability + return calculated price. */
    public function checkAvailability(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'pickup_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['required', 'date', 'after:pickup_date'],
        ]);

        $pickup = Carbon::parse($data['pickup_date']);
        $return = Carbon::parse($data['return_date']);
        $days = $pickup->diffInDays($return) + 1; // inclusive

        $available = $vehicle->isAvailableBetween($data['pickup_date'], $data['return_date']);

        return response()->json([
            'available' => $available,
            'total_days' => $days,
            'daily_rate' => (float) $vehicle->daily_rate,
            'total_amount' => round($days * (float) $vehicle->daily_rate, 2),
            'message' => $available
                ? 'This vehicle is available for the selected dates.'
                : 'Sorry, this vehicle is already booked for part of your selected period.',
        ]);
    }

    /**  store the booking (final submit). */
    public function store(Request $request, Vehicle $vehicle): RedirectResponse
    {
        abort_if($vehicle->status !== 'available', 404);

        $data = $request->validate([
            'pickup_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['required', 'date', 'after:pickup_date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $pickup = Carbon::parse($data['pickup_date']);
        $return = Carbon::parse($data['return_date']);
        $days = $pickup->diffInDays($return) + 1;
        $total = round($days * (float) $vehicle->daily_rate, 2);

        // DB-level availability check inside a transaction – prevents race
        // conditions where two customers submit for the same vehicle+dates.
        $booking = DB::transaction(function () use ($vehicle, $data, $days, $total) {
            // Lock overlapping booking rows for this vehicle so a concurrent
            // insert cannot sneak in between our check and our insert.
            $overlap = Booking::where('vehicle_registration_no', $vehicle->registration_no)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('pickup_date', '<=', $data['return_date'])
                ->where('return_date', '>=', $data['pickup_date'])
                ->lockForUpdate()
                ->exists();

            if ($overlap) {
                // Throw a ValidationException so Laravel redirects back with the error.
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'pickup_date' => 'This vehicle is no longer available for the selected dates.',
                ]);
            }

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'vehicle_registration_no' => $vehicle->registration_no,
                'pickup_date' => $data['pickup_date'],
                'return_date' => $data['return_date'],
                'total_days' => $days,
                'daily_rate_snapshot' => $vehicle->daily_rate,
                'total_amount' => $total,
                'status' => 'confirmed',
                'notes' => $data['notes'] ?? null,
            ]);

            // Internal billing record (no real payment gateway).
            Billing::create([
                'booking_id' => $booking->id,
                'amount' => $total,
                'status' => 'unpaid',
            ]);

            return $booking;
        });

        // Email the customer that we received their booking.
        // We wrap in try/catch so a mail failure never blocks the booking.
        try {
            Mail::to($booking->user->email)->send(new BookingConfirmed($booking, 'confirmed'));
        } catch (\Throwable $e) {
            Log::warning('Booking email failed: '.$e->getMessage());
        }

        AuditLogger::log('booking.create',
            "Customer created booking #{$booking->id} for vehicle {$booking->vehicle_registration_no} ({$booking->pickup_date->toDateString()} to {$booking->return_date->toDateString()}, FJD {$booking->total_amount})",
            Booking::class, (string) $booking->id);

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'Booking confirmed successfully. Reference #'.$booking->id);
    }

    /** Customer sees only their own bookings. */
    public function index(): View
    {
        $bookings = Booking::with(['vehicle.category', 'billing', 'replacements.replacementVehicle'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        // Authorization: customer may only see their own booking.
        abort_if($booking->user_id !== Auth::id(), 403);

        $booking->load(['vehicle.category', 'originalVehicle', 'billing', 'replacements.replacementVehicle']);
        return view('customer.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Only pending or confirmed bookings can be cancelled.');
        }

       $booking->update(['status' => 'cancelled']);

       if ($booking->billing && $booking->billing->status === 'unpaid') {
        $booking->billing->update(['status' => 'refunded']);
}

        AuditLogger::log('booking.cancel', "Customer cancelled booking #{$booking->id}",
            Booking::class, (string) $booking->id);

        return back()->with('success', 'Booking cancelled.');
    }
}
