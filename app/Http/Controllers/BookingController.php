<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Notification;

class BookingController extends Controller
{
    /**
     * Show the booking form for a vehicle.
     */
    public function create(Vehicle $vehicle)
    {
        return view('bookings.create', compact('vehicle'));
    }

    /**
     * Store a new booking.
     */
    public function store(Request $request, Vehicle $vehicle)
    {
        // Validate booking dates
        $request->validate([
            'pickup_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:pickup_date',
        ], [
            'pickup_date.required' => 'Please select a pickup date.',
            'pickup_date.date' => 'Please enter a valid pickup date.',
            'pickup_date.after_or_equal' => 'Pickup date cannot be in the past.',

            'return_date.required' => 'Please select a return date.',
            'return_date.date' => 'Please enter a valid return date.',
            'return_date.after' => 'Return date must be after the pickup date.',
        ]);

        // Prevent booking of unavailable or retired vehicles
        if ($vehicle->status !== 'available') {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'This vehicle is currently unavailable and cannot be booked.'
                );
        }

        // Check if the vehicle is already booked for the selected dates
        $alreadyBooked = Booking::where('vehicle_id', $vehicle->id)
            ->where('status', 'confirmed')
            ->where(function ($query) use ($request) {
                $query->where('pickup_date', '<', $request->return_date)
                      ->where('return_date', '>', $request->pickup_date);
            })
            ->exists();

        // If the dates are unavailable, show an error message
        if ($alreadyBooked) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'These dates are unavailable for this vehicle. Please choose different dates.'
                );
        }

        // Calculate number of rental days
        $days = Carbon::parse($request->pickup_date)
            ->diffInDays(
                Carbon::parse($request->return_date)
            );

        // Calculate total rental cost
        $total = $days * $vehicle->daily_rate;

        // Create the booking
        // The booking is automatically confirmed when the dates are available.
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'vehicle_id' => $vehicle->id,
            'customer_name' => auth()->user()->name,
            'pickup_date' => $request->pickup_date,
            'return_date' => $request->return_date,
            'total_amount' => $total,
            'status' => 'confirmed',
        ]);

        // Show booking confirmation page
        return view('bookings.confirmation', compact('booking'));
    }

    /**
     * Show the logged-in customer's booking history.
     */
    public function index()
    {
    $bookings = Booking::with('vehicle', 'replacementVehicle')
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

    $notifications = Notification::where('user_id', auth()->id())
        ->latest()
        ->get();

    return view(
        'bookings.index',
        compact('bookings', 'notifications')
    );
    }

    /**
     * Cancel a customer's booking.
     */
    public function cancel(Booking $booking)
    {
        // Customer can only cancel their own booking
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Only confirmed bookings can be cancelled
        if ($booking->status !== 'confirmed') {
            return back()->with(
                'error',
                'This booking cannot be cancelled.'
            );
        }

        // Change booking status to cancelled
        $booking->update([
            'status' => 'cancelled',
        ]);

        return back()->with(
            'success',
            'Booking cancelled successfully.'
        );
    }

    /**
     * Show all bookings to the admin.
     */
    public function adminIndex()
    {
        // Admin can see all bookings
        $bookings = Booking::with('vehicle', 'user')
            ->latest()
            ->get();

        // Booking statistics
        $totalBookings = Booking::count();

        $confirmedBookings = Booking::where('status', 'confirmed')
            ->count();

        $cancelledBookings = Booking::where('status', 'cancelled')
            ->count();

        return view('admin.bookings', compact(
            'bookings',
            'totalBookings',
            'confirmedBookings',
            'cancelledBookings'
        ));
    }

    public function replacement(Booking $booking)
    {
    $booking->load('vehicle', 'user');

    $replacementVehicles = Vehicle::where('id', '!=', $booking->vehicle_id)
        ->where('status', 'available')
        ->whereDoesntHave('bookings', function ($query) use ($booking) {
            $query->where('status', 'confirmed')
                ->where(function ($q) use ($booking) {
                    $q->where('pickup_date', '<', $booking->return_date)
                      ->where('return_date', '>', $booking->pickup_date);
                });
        })
        ->get();

    return view(
        'admin.booking-replacement',
        compact('booking', 'replacementVehicles')
    );
    }

    public function replaceVehicle(Request $request, Booking $booking)
    {
    $request->validate([
        'replacement_vehicle_id' => 'required|exists:vehicles,id',
    ]);

    $replacementVehicle = Vehicle::findOrFail(
        $request->replacement_vehicle_id
    );

    if ($replacementVehicle->status !== 'available') {
        return back()->with(
            'error',
            'The selected vehicle is currently unavailable.'
        );
    }

    $alreadyBooked = Booking::where(
            'vehicle_id',
            $replacementVehicle->id
        )
        ->where('status', 'confirmed')
        ->where('id', '!=', $booking->id)
        ->where(function ($query) use ($booking) {
            $query->where('pickup_date', '<', $booking->return_date)
                  ->where('return_date', '>', $booking->pickup_date);
        })
        ->exists();

    if ($alreadyBooked) {
        return back()->with(
            'error',
            'The selected vehicle is already booked for these dates.'
        );
    }

    $booking->update([
        'replacement_vehicle_id' => $replacementVehicle->id,
    ]);
   
    Notification::create([
    'user_id' => $booking->user_id,
    'booking_id' => $booking->id,
    'title' => 'Vehicle Replacement Notice',
    'message' => 'Your vehicle for Booking #' . $booking->id .
        ' has been replaced from ' .
        $booking->vehicle->registration . ' - ' .
        $booking->vehicle->name . ' to ' .
        $replacementVehicle->registration . ' - ' .
        $replacementVehicle->name .
        '. Your booking dates and total amount remain unchanged.',
    'is_read' => false,
    ]);

    return redirect()
        ->route('admin.bookings')
        ->with(
            'success',
            'Vehicle replaced successfully for this booking.'
        );
    }

    /**
     * Allow admin to change booking status.
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);

        $booking->update([
            'status' => $request->status,
        ]);

        return back()->with(
            'success',
            'Booking status updated successfully.'
        );
    }
}