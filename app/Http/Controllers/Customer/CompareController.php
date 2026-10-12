<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Compare up to 3 vehicles side-by-side. Accepts registration numbers via
 * the `v[]` query param (?v=LR3180&v=LR3181). Silently caps at 3 and
 * de-duplicates so the UI never has to worry about it.
 */
class CompareController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        $regNos = collect((array) $request->input('v', []))
            ->map(fn ($r) => strtoupper(trim((string) $r)))
            ->filter()
            ->unique()
            ->take(3)
            ->values();

        if ($regNos->count() < 2) {
            return redirect()->route('customer.favorites.index')
                ->with('error', 'Please select at least 2 vehicles to compare.');
        }

        $vehicles = Vehicle::with('category')
            ->whereIn('registration_no', $regNos)
            ->get()
            // Preserve the order the user clicked them in.
            ->sortBy(fn ($v) => $regNos->search($v->registration_no))
            ->values();

        if ($vehicles->count() < 2) {
            return redirect()->route('customer.favorites.index')
                ->with('error', 'Not enough valid vehicles to compare.');
        }

        return view('customer.compare.show', compact('vehicles'));
    }
}
