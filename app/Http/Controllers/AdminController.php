<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;

class AdminController extends Controller
{
    public function dashboard()
    {
        // =========================
        // BOOKING ANALYTICS
        // =========================

        $totalBookings = Booking::count();

        $confirmedBookings = Booking::where('status', 'confirmed')->count();

        $cancelledBookings = Booking::where('status', 'cancelled')->count();


        // =========================
        // VEHICLE ANALYTICS
        // =========================

        $totalVehicles = Vehicle::count();

        $availableVehicles = Vehicle::where('status', 'available')->count();

        $unavailableVehicles = Vehicle::where('status', 'unavailable')->count();


        // =========================
        // BOOKING CHART DATA
        // =========================

        $bookingChart = [
            'confirmed' => $confirmedBookings,
            'cancelled' => $cancelledBookings,
        ];


        // =========================
        // RECENT BOOKINGS
        // =========================

        $recentBookings = Booking::with('vehicle', 'user')
            ->latest()
            ->take(5)
            ->get();


        // =========================
        // SEND DATA TO DASHBOARD
        // =========================

        return view('admin.dashboard', compact(
            'totalBookings',
            'confirmedBookings',
            'cancelledBookings',
            'totalVehicles',
            'availableVehicles',
            'unavailableVehicles',
            'bookingChart',
            'recentBookings'
        ));
    }
}