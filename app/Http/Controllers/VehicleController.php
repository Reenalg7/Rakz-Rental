<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::query();

        // Search by vehicle name, registration or category
        if ($request->filled('search')) {
    $search = $request->search;

    $query->where(function ($q) use ($search) {
        $q->where('name', 'like', $search . '%')
          ->orWhere('registration', 'like', $search . '%')
          ->orWhere('category', 'like', $search . '%');
    });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $vehicles = $query->get();

        $categories = Vehicle::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('vehicles.index', compact(
            'vehicles',
            'categories'
        ));
    }
}