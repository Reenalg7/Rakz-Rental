<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Public landing / vehicle browsing.
 * Available to guests and customers alike.
 */
class HomeController extends Controller
{
    public function index(): View
    {
        $featured = Vehicle::where('status', 'available')
            ->with('category')
            ->latest('updated_at')
            ->take(6)
            ->get();

        $categories = Category::withCount('vehicles')->get();

        return view('home', compact('featured', 'categories'));
    }
}
