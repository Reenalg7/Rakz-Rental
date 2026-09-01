<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;

class VehicleController extends Controller
{
     public function index()
    {
        $vehicles = Vehicle::where('status', 'available')->get();

        return view('vehicles.index', compact('vehicles'));
    }
}
