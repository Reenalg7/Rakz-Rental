<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create(Vehicle $vehicle)
    {
        return view('bookings.create', compact('vehicle'));
    }

    public function store(Request $request, Vehicle $vehicle)
    {
        // Validate booking details
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'pickup_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:pickup_date',
        ]);

        // Check if the vehicle is already booked
        $alreadyBooked = Booking::where('vehicle_id', $vehicle->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($request) {
                $query->where('pickup_date', '<', $request->return_date)
                      ->where('return_date', '>', $request->pickup_date);
            })
            ->exists();

        // If vehicle is unavailable
        if ($alreadyBooked) {
            return back()
                ->withInput()
                ->with('error', 'Dates Unavailable. Please choose different dates.');
        }

        // Calculate number of rental days
        $days = Carbon::parse($request->pickup_date)
            ->diffInDays(
                Carbon::parse($request->return_date)
            );

        // Calculate total cost
        $total = $days * $vehicle->daily_rate;

        // Create booking
        $booking = Booking::create([
            'vehicle_id' => $vehicle->id,
            'customer_name' => $request->customer_name,
            'pickup_date' => $request->pickup_date,
            'return_date' => $request->return_date,
            'total_amount' => $total,
            'status' => 'pending',
        ]);

        // Show booking confirmation
        return view('bookings.confirmation', compact('booking'));
    }

    public function index()
    {
        $bookings = Booking::with('vehicle')
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    public function adminIndex()
{
     $bookings = Booking::with('vehicle')
        ->latest()
        ->get();

    $totalBookings = Booking::count();

    $pendingBookings = Booking::where('status', 'pending')->count();

    $confirmedBookings = Booking::where('status', 'confirmed')->count();

    $cancelledBookings = Booking::where('status', 'cancelled')->count();

    return view('admin.bookings', compact(
        'bookings',
        'totalBookings',
        'pendingBookings',
        'confirmedBookings',
        'cancelledBookings'
    ));
}

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