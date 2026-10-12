<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\VehicleReplaced;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Vehicle;
use App\Models\VehicleReplacement;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * Admin Vehicle Replacement
 * ---------------------------------------------------------------------------
 * Allows an admin to swap a vehicle on an existing booking when the original
 * is unavailable (accident, breakdown, maintenance, etc.).
 *
 * We:
 *   1. Verify the replacement vehicle is actually free for the booking's dates.
 *   2. Create a VehicleReplacement audit row (original -> replacement).
 *   3. Update the booking.vehicle_registration_no to the new reg_no.
 *   4. Preserve original_vehicle_registration_no so history is never lost.
 *   5. Create a Notification for the customer.
 */
class ReplacementController extends Controller
{
    public function create(Booking $booking): View
    {
        $booking->load('vehicle.category', 'user');

        // Suggest only vehicles that are available for the booking's date range.
        $candidates = Vehicle::where('status', 'available')
            ->where('registration_no', '!=', $booking->vehicle_registration_no)
            ->whereDoesntHave('bookings', function ($q) use ($booking) {
                $q->whereIn('status', ['pending', 'confirmed'])
                    ->where('id', '!=', $booking->id)
                    ->where('pickup_date', '<=', $booking->return_date)
                    ->where('return_date', '>=', $booking->pickup_date);
            })
            ->orderBy('daily_rate')
            ->get();

        return view('admin.replacements.create', compact('booking', 'candidates'));
    }

    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'replacement_registration_no' => ['required', 'exists:vehicles,registration_no'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        if ($data['replacement_registration_no'] === $booking->vehicle_registration_no) {
            return back()->withErrors(['replacement_registration_no' => 'Replacement must be a different vehicle.']);
        }

        $replacementVehicle = Vehicle::findOrFail($data['replacement_registration_no']);

        // Re-check availability inside a transaction + row lock.
        DB::transaction(function () use ($booking, $replacementVehicle, $data) {
            $overlap = Booking::where('vehicle_registration_no', $replacementVehicle->registration_no)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('id', '!=', $booking->id)
                ->where('pickup_date', '<=', $booking->return_date)
                ->where('return_date', '>=', $booking->pickup_date)
                ->lockForUpdate()
                ->exists();

            if ($overlap || $replacementVehicle->status !== 'available') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'replacement_registration_no' => 'Replacement vehicle is not available for these dates.',
                ]);
            }

            $originalReg = $booking->vehicle_registration_no;
            $originalVehicle = Vehicle::findOrFail($originalReg);

            // Preserve original if this is the first replacement.
            if (is_null($booking->original_vehicle_registration_no)) {
                $booking->original_vehicle_registration_no = $originalReg;
            }
            $booking->vehicle_registration_no = $replacementVehicle->registration_no;
            $booking->save();

            VehicleReplacement::create([
                'booking_id' => $booking->id,
                'original_registration_no' => $originalReg,
                'replacement_registration_no' => $replacementVehicle->registration_no,
                'reason' => $data['reason'] ?? null,
                'replaced_by_user_id' => Auth::id(),
            ]);

            Notification::create([
                'user_id' => $booking->user_id,
                'title' => 'Vehicle Replaced on Booking #'.$booking->id,
                'message' => "Your vehicle was changed from {$originalReg} to {$replacementVehicle->registration_no} ({$replacementVehicle->name}). Reason: ".($data['reason'] ?? 'Not specified'),
                'type' => 'replacement',
            ]);

            // Email the customer. Guard against mail failures with try/catch.
            try {
                $booking->loadMissing('user');
                Mail::to($booking->user->email)->send(
                    new VehicleReplaced($booking, $originalVehicle, $replacementVehicle, $data['reason'] ?? null)
                );
            } catch (\Throwable $e) {
                Log::warning('Replacement email failed: '.$e->getMessage());
            }
        });

        AuditLogger::log(
            'vehicle.replace',
            "Replaced vehicle on booking #{$booking->id}: {$booking->original_vehicle_registration_no} → {$booking->vehicle_registration_no}".
                ($data['reason'] ? " · {$data['reason']}" : ''),
            Booking::class, (string) $booking->id
        );

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Vehicle replaced successfully.');
    }

    public function index(): View
    {
        $replacements = VehicleReplacement::with(['booking.user', 'originalVehicle', 'replacementVehicle', 'replacedBy'])
            ->latest()
            ->paginate(15);
        return view('admin.replacements.index', compact('replacements'));
    }
}
