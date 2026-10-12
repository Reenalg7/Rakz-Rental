<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleReplacement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Admin Reports
 * ---------------------------------------------------------------------------
 * - Revenue over the last 12 months (confirmed + completed only).
 * - Fleet utilization: % of days each vehicle was booked in the window.
 * - Top customers by number of bookings and total spend.
 * - Bookings grouped by category.
 *
 * Everything is read-only. Date window can be narrowed via `?from=&to=` query
 * params; defaults to the last 90 days for utilization and 12 months for revenue.
 */
class ReportController extends Controller
{
    public function index(Request $request): View
    {
        // --- 1. Revenue by month (last 12 months) ----------------------------
        // Pull raw rows then group in PHP so the query is DB-agnostic (MySQL/SQLite).
        $since = now()->subMonths(11)->startOfMonth();
        $rawRows = Booking::query()
            ->whereIn('status', ['confirmed', 'completed'])
            ->where('created_at', '>=', $since)
            ->get(['created_at', 'total_amount']);

        $grouped = $rawRows->groupBy(fn ($b) => $b->created_at->format('Y-m'));

        $revenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $group = $grouped->get($key);
            $revenue[] = [
                'month' => now()->subMonths($i)->format('M Y'),
                'total' => (float) ($group?->sum('total_amount') ?? 0),
                'count' => (int) ($group?->count() ?? 0),
            ];
        }
        $totalRevenue = array_sum(array_column($revenue, 'total'));

        // --- 2. Fleet utilization (admin-selectable window) -----------------
        $from = $request->input('from') ? Carbon::parse($request->input('from'))->startOfDay() : now()->subDays(89)->startOfDay();
        $to = $request->input('to') ? Carbon::parse($request->input('to'))->endOfDay() : now()->endOfDay();
        $windowDays = max(1, $from->diffInDays($to) + 1);

        // Join vehicles -> bookings (in window) then compute overlap days in PHP.
        $vehicles = Vehicle::with(['bookings' => function ($q) use ($from, $to) {
            $q->whereIn('status', ['confirmed', 'completed'])
                ->where('pickup_date', '<=', $to->toDateString())
                ->where('return_date', '>=', $from->toDateString());
        }])->get();

        $utilization = $vehicles->map(function ($v) use ($from, $to, $windowDays) {
            $bookedDays = 0;
            foreach ($v->bookings as $b) {
                $start = $b->pickup_date->greaterThan($from) ? $b->pickup_date : $from;
                $end = $b->return_date->lessThan($to) ? $b->return_date : $to;
                $bookedDays += max(0, $start->diffInDays($end) + 1);
            }
            return (object) [
                'registration_no' => $v->registration_no,
                'name' => $v->name,
                'daily_rate' => $v->daily_rate,
                'booking_count' => $v->bookings->count(),
                'booked_days' => $bookedDays,
                'utilization_pct' => $windowDays > 0 ? round(($bookedDays / $windowDays) * 100, 1) : 0,
            ];
        })->sortByDesc('booked_days')->values();

        // --- 3. Top customers ----------------------------------------------
        $topCustomers = User::query()
            ->where('role', 'customer')
            ->leftJoin('bookings', 'bookings.user_id', '=', 'users.id')
            ->selectRaw("users.id, users.name, users.email,
                COUNT(bookings.id) as bookings_count,
                COALESCE(SUM(CASE WHEN bookings.status IN ('confirmed','completed') THEN bookings.total_amount ELSE 0 END), 0) as total_spent")
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->orderByDesc('bookings_count')
            ->limit(10)
            ->get();

        // --- 4. Bookings by category ---------------------------------------
        $byCategory = DB::table('bookings')
            ->join('vehicles', 'vehicles.registration_no', '=', 'bookings.vehicle_registration_no')
            ->leftJoin('categories', 'categories.id', '=', 'vehicles.category_id')
            ->selectRaw("COALESCE(categories.name, 'Uncategorized') as category_name,
                COUNT(bookings.id) as cnt,
                COALESCE(SUM(bookings.total_amount), 0) as revenue")
            ->groupBy('category_name')
            ->orderByDesc('revenue')
            ->get()
            ->map(fn ($r) => (object) ['name' => $r->category_name, 'cnt' => $r->cnt, 'revenue' => $r->revenue]);

        // --- 5. Summary cards ---------------------------------------------
        $summary = [
            'total_revenue_12m' => $totalRevenue,
            'bookings_12m' => array_sum(array_column($revenue, 'count')),
            'fleet_size' => Vehicle::count(),
            'avg_utilization' => round($utilization->avg('utilization_pct') ?? 0, 1),
            'replacements' => VehicleReplacement::count(),
            'customers' => User::where('role', 'customer')->count(),
        ];

        return view('admin.reports.index', compact(
            'revenue', 'utilization', 'topCustomers', 'byCategory',
            'summary', 'from', 'to', 'windowDays'
        ));
    }
}
