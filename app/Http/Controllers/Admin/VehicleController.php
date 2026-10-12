<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Vehicle;
use App\Support\AuditLogger;
use App\Support\QrCodeGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Admin Vehicle Management.
 * Registration number is the primary key, so we never auto-generate it:
 * admins type the actual plate (e.g. "LR3180").
 */
class VehicleController extends Controller
{
    public function index(Request $request): View
    {
        $q = Vehicle::with('category');

        if ($status = $request->input('status')) {
            $q->where('status', $status);
        }
        if ($keyword = trim((string) $request->input('q'))) {
            $q->where(function ($qq) use ($keyword) {
                $qq->where('registration_no', 'like', "%{$keyword}%")
                    ->orWhere('name', 'like', "%{$keyword}%")
                    ->orWhere('make', 'like', "%{$keyword}%")
                    ->orWhere('model', 'like', "%{$keyword}%");
            });
        }

        $vehicles = $q->orderBy('registration_no')->paginate(15)->withQueryString();
        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.vehicles.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        Vehicle::create($data);
        AuditLogger::log('vehicle.create', "Vehicle {$data['registration_no']} ({$data['name']}) created",
            Vehicle::class, $data['registration_no']);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle '.$data['registration_no'].' added.');
    }

    public function edit(Vehicle $vehicle): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.vehicles.edit', compact('vehicle', 'categories'));
    }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        // Registration number is immutable (it IS the primary key / plate).
        $data = $this->validatedData($request, $vehicle);
        unset($data['registration_no']);

        $vehicle->update($data);
        AuditLogger::log('vehicle.update', "Vehicle {$vehicle->registration_no} updated",
            Vehicle::class, $vehicle->registration_no);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle '.$vehicle->registration_no.' updated.');
    }

    /**
     * We don't hard-delete vehicles with history. Instead we "retire" them so
     * past booking and replacement records keep their FK integrity.
     */
    public function retire(Vehicle $vehicle): RedirectResponse
    {
        $vehicle->update(['status' => 'retired']);
        AuditLogger::log('vehicle.retire', "Vehicle {$vehicle->registration_no} retired",
            Vehicle::class, $vehicle->registration_no);
        return back()->with('success', 'Vehicle retired. Historical records are preserved.');
    }

    public function restore(Vehicle $vehicle): RedirectResponse
    {
        $vehicle->update(['status' => 'available']);
        AuditLogger::log('vehicle.restore', "Vehicle {$vehicle->registration_no} restored",
            Vehicle::class, $vehicle->registration_no);
        return back()->with('success', 'Vehicle restored to available fleet.');
    }

    /**
     * Printable one-page PDF datasheet (specs + photo + QR + daily rate).
     * Admins clip this to the physical key for the vehicle at the counter.
     */
    public function datasheet(Vehicle $vehicle)
    {
        $vehicle->load('category');
        $vehicleUrl = route('customer.vehicles.show', $vehicle->registration_no);
        $qrDataUri = QrCodeGenerator::dataUri($vehicleUrl, 180);

        $pdf = Pdf::loadView('vehicles.datasheet', [
                'vehicle' => $vehicle,
                'qrDataUri' => $qrDataUri,
            ])->setPaper('a4');

        return $pdf->download("rakz-rental-datasheet-{$vehicle->registration_no}.pdf");
    }

    private function validatedData(Request $request, ?Vehicle $vehicle = null): array
    {
        $regRule = ['required', 'string', 'max:20', 'regex:/^[A-Z0-9\-]+$/i'];
        if (!$vehicle) {
            $regRule[] = 'unique:vehicles,registration_no';
        } else {
            $regRule[] = Rule::unique('vehicles', 'registration_no')->ignore($vehicle->registration_no, 'registration_no');
        }

        return $request->validate([
            'registration_no' => $regRule,
            'name' => ['required', 'string', 'max:120'],
            'make' => ['required', 'string', 'max:60'],
            'model' => ['required', 'string', 'max:60'],
            'year' => ['required', 'integer', 'between:1990,2100'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'seats' => ['required', 'integer', 'min:1', 'max:60'],
            'transmission' => ['required', Rule::in(['manual', 'automatic'])],
            'fuel_type' => ['required', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'status' => ['required', Rule::in(['available', 'maintenance', 'retired'])],
        ]);
    }
}
