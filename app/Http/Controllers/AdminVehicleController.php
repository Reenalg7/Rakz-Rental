<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleReplacement;
use Illuminate\Http\Request;

class AdminVehicleController extends Controller
{
    /**
     * Display all vehicles.
     */
    public function index()
    {
        $vehicles = Vehicle::latest()->get();

        return view('admin.vehicles', compact('vehicles'));
    }

    /**
     * Show create vehicle form.
     */
    public function create()
    {
        return view('admin.vehicles-create');
    }

    /**
     * Store a new vehicle.
     */
    public function store(Request $request)
    {
        $request->validate([
            'registration' => 'required|string|max:255|unique:vehicles,registration',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'daily_rate' => 'required|numeric|min:0',
            'status' => 'required|in:available,unavailable',
        ]);

        Vehicle::create([
            'registration' => $request->registration,
            'name' => $request->name,
            'category' => $request->category,
            'daily_rate' => $request->daily_rate,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.vehicles')
            ->with('success', 'Vehicle added successfully.');
    }

    /**
     * Show edit vehicle form.
     */
    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles-edit', compact('vehicle'));
    }

    /**
     * Update a vehicle.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'registration' => 'required|string|max:255|unique:vehicles,registration,' . $vehicle->id,
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'daily_rate' => 'required|numeric|min:0',
            'status' => 'required|in:available,unavailable',
        ]);

        $vehicle->update([
            'registration' => $request->registration,
            'name' => $request->name,
            'category' => $request->category,
            'daily_rate' => $request->daily_rate,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.vehicles')
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Show vehicle replacement form.
     */
    public function replacement(Vehicle $vehicle)
    {
        return view('admin.vehicles-replace', compact('vehicle'));
    }

    /**
     * Replace a vehicle.
     */
    public function replace(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'registration' => 'required|string|max:255|unique:vehicles,registration',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'daily_rate' => 'required|numeric|min:0',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Mark old vehicle as unavailable
        |--------------------------------------------------------------------------
        |
        | The old vehicle is not deleted. This preserves its database
        | record and any booking history associated with it.
        |
        */

        $vehicle->update([
            'status' => 'unavailable',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create replacement vehicle
        |--------------------------------------------------------------------------
        */

        $replacementVehicle = Vehicle::create([
            'registration' => $request->registration,
            'name' => $request->name,
            'category' => $request->category,
            'daily_rate' => $request->daily_rate,
            'status' => 'available',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Record replacement history
        |--------------------------------------------------------------------------
        |
        | This records which vehicle was replaced by which new vehicle
        | and which administrator performed the replacement.
        |
        */

        VehicleReplacement::create([
            'old_vehicle_id' => $vehicle->id,
            'new_vehicle_id' => $replacementVehicle->id,
            'admin_id' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.vehicles')
            ->with(
                'success',
                'Vehicle replaced successfully. The old vehicle has been marked unavailable and the replacement vehicle is now available.'
            );
    }

    /**
     * Retire a vehicle without deleting its database record.
     */
    public function destroy(Vehicle $vehicle)
    {
        /*
        |--------------------------------------------------------------------------
        | Retire vehicle
        |--------------------------------------------------------------------------
        |
        | The vehicle is not physically deleted from the database.
        | Its record and booking history are preserved.
        |
        */

        $vehicle->update([
            'status' => 'unavailable',
        ]);

        return redirect()
            ->route('admin.vehicles')
            ->with(
                'success',
                'Vehicle retired successfully. The vehicle record and booking history have been preserved.'
            );
    }
}