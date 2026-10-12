<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Favorites — customer-managed list of preferred vehicles for one-click
 * rebooking. Toggle action is idempotent and silently handles duplicates
 * via the (user_id, vehicle_registration_no) unique index.
 */
class FavoriteController extends Controller
{
    public function index(): View
    {
        $favorites = Favorite::with(['vehicle.category'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->filter(fn ($f) => $f->vehicle !== null);  // skip rows whose vehicle was deleted

        return view('customer.favorites.index', compact('favorites'));
    }

    public function toggle(Vehicle $vehicle): RedirectResponse
    {
        $existing = Favorite::where('user_id', Auth::id())
            ->where('vehicle_registration_no', $vehicle->registration_no)
            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', "Removed {$vehicle->registration_no} from your favorites.");
        }

        Favorite::create([
            'user_id' => Auth::id(),
            'vehicle_registration_no' => $vehicle->registration_no,
        ]);

        return back()->with('success', "Added {$vehicle->registration_no} to your favorites.");
    }
}
