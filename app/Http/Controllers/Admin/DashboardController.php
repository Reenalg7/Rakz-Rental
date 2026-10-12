<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleReplacement;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_vehicles' => Vehicle::count(),
            'available_vehicles' => Vehicle::where('status', 'available')->count(),
            'maintenance_vehicles' => Vehicle::where('status', 'maintenance')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'total_replacements' => VehicleReplacement::count(),
            'revenue' => (float) Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_amount'),
        ];

        $recentBookings = Booking::with(['user', 'vehicle'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
}
