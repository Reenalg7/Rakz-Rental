<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * VehicleController (customer-facing)
 * ---------------------------------------------------------------------------
 *   - Keyword search across name, make, model, registration_no
 *   - Category filter
 *   - Transmission filter
 *   - Price range filter
 *   - Date-aware availability: when pickup_date & return_date are provided
 *     we exclude vehicles that already have an overlapping booking.
 */
class VehicleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Vehicle::query()
            ->with('category')
            ->where('status', 'available');

        // Keyword search – includes registration number.
        if ($keyword = trim((string) $request->input('q'))) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('make', 'like', "%{$keyword}%")
                    ->orWhere('model', 'like', "%{$keyword}%")
                    ->orWhere('registration_no', 'like', "%{$keyword}%");
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($transmission = $request->input('transmission')) {
            $query->where('transmission', $transmission);
        }

        if ($minPrice = $request->input('min_price')) {
            $query->where('daily_rate', '>=', (float) $minPrice);
        }
        if ($maxPrice = $request->input('max_price')) {
            $query->where('daily_rate', '<=', (float) $maxPrice);
        }

        // Date-aware availability check.
        $pickup = $request->input('pickup_date');
        $return = $request->input('return_date');

        if ($pickup && $return && $pickup <= $return) {
            // Exclude vehicles that overlap with an existing pending/confirmed booking.
            $query->whereDoesntHave('bookings', function ($q) use ($pickup, $return) {
                $q->whereIn('status', ['pending', 'confirmed'])
                    ->where('pickup_date', '<=', $return)
                    ->where('return_date', '>=', $pickup);
            });
        }

        $vehicles = $query->orderBy('daily_rate')->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('customer.vehicles.index', compact('vehicles', 'categories'));
    }

    public function show(Vehicle $vehicle): View
    {
        $vehicle->load('category');
        return view('customer.vehicles.show', compact('vehicle'));
    }
}
