<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * CSV exports for the lecturer to inspect raw data in Excel.
 * Uses StreamedResponse so even large datasets stay memory-efficient.
 */
class CsvExportController extends Controller
{
    /** All bookings with user + vehicle + billing joined. */
    public function bookings(Request $request): StreamedResponse
    {
        $filename = 'rakz-rental-bookings-'.now()->format('Y-m-d-His').'.csv';

        $headers = [
            'Booking ID', 'Status', 'Customer Name', 'Customer Email', 'Customer Phone',
            'Vehicle Reg No', 'Original Reg No', 'Vehicle Name',
            'Pickup Date', 'Return Date', 'Days',
            'Daily Rate (FJD)', 'Total Amount (FJD)',
            'Payment Status', 'Paid At', 'Was Replaced?',
            'Created At',
        ];

        return $this->streamCsv($filename, $headers, function ($out) use ($request) {
            $query = Booking::with(['user', 'vehicle', 'billing']);
            if ($status = $request->input('status')) {
                $query->where('status', $status);
            }
            $query->orderBy('id')->chunk(500, function ($chunk) use ($out) {
                foreach ($chunk as $b) {
                    fputcsv($out, [
                        $b->id,
                        $b->status,
                        $b->user?->name,
                        $b->user?->email,
                        $b->user?->phone,
                        $b->vehicle_registration_no,
                        $b->original_vehicle_registration_no,
                        $b->vehicle?->name,
                        $b->pickup_date?->toDateString(),
                        $b->return_date?->toDateString(),
                        $b->total_days,
                        number_format((float) $b->daily_rate_snapshot, 2, '.', ''),
                        number_format((float) $b->total_amount, 2, '.', ''),
                        $b->billing?->status,
                        $b->billing?->paid_at?->toDateTimeString(),
                        $b->wasReplaced() ? 'YES' : 'NO',
                        $b->created_at?->toDateTimeString(),
                    ]);
                }
            });
        });
    }

    /** Monthly revenue — the same data as the chart on /admin/reports. */
    public function revenue(): StreamedResponse
    {
        $filename = 'rakz-rental-revenue-'.now()->format('Y-m-d').'.csv';
        $headers = ['Month', 'Bookings Count', 'Revenue (FJD)'];

        $since = now()->subMonths(11)->startOfMonth();
        $rows = Booking::query()
            ->whereIn('status', ['confirmed', 'completed'])
            ->where('created_at', '>=', $since)
            ->get(['created_at', 'total_amount'])
            ->groupBy(fn ($b) => $b->created_at->format('Y-m'));

        return $this->streamCsv($filename, $headers, function ($out) use ($rows) {
            for ($i = 11; $i >= 0; $i--) {
                $key = now()->subMonths($i)->format('Y-m');
                $group = $rows->get($key);
                fputcsv($out, [
                    now()->subMonths($i)->format('M Y'),
                    (int) ($group?->count() ?? 0),
                    number_format((float) ($group?->sum('total_amount') ?? 0), 2, '.', ''),
                ]);
            }
        });
    }

    /** Fleet utilization within the admin-selected window. */
    public function utilization(Request $request): StreamedResponse
    {
        $filename = 'rakz-rental-utilization-'.now()->format('Y-m-d').'.csv';
        $from = $request->input('from') ? \Carbon\Carbon::parse($request->input('from'))->startOfDay() : now()->subDays(89)->startOfDay();
        $to = $request->input('to') ? \Carbon\Carbon::parse($request->input('to'))->endOfDay() : now()->endOfDay();
        $windowDays = max(1, $from->diffInDays($to) + 1);

        $headers = ['Registration No', 'Vehicle', 'Daily Rate (FJD)', 'Bookings in Window', 'Days Booked', 'Window Days', 'Utilization %'];

        $vehicles = Vehicle::with(['bookings' => function ($q) use ($from, $to) {
            $q->whereIn('status', ['confirmed', 'completed'])
                ->where('pickup_date', '<=', $to->toDateString())
                ->where('return_date', '>=', $from->toDateString());
        }])->get();

        return $this->streamCsv($filename, $headers, function ($out) use ($vehicles, $from, $to, $windowDays) {
            foreach ($vehicles as $v) {
                $bookedDays = 0;
                foreach ($v->bookings as $b) {
                    $start = $b->pickup_date->greaterThan($from) ? $b->pickup_date : $from;
                    $end = $b->return_date->lessThan($to) ? $b->return_date : $to;
                    $bookedDays += max(0, $start->diffInDays($end) + 1);
                }
                $util = $windowDays > 0 ? round(($bookedDays / $windowDays) * 100, 1) : 0;
                fputcsv($out, [
                    $v->registration_no,
                    $v->name,
                    number_format((float) $v->daily_rate, 2, '.', ''),
                    $v->bookings->count(),
                    $bookedDays,
                    $windowDays,
                    $util,
                ]);
            }
        });
    }

    /** Top customers by spend. */
    public function customers(): StreamedResponse
    {
        $filename = 'rakz-rental-top-customers-'.now()->format('Y-m-d').'.csv';
        $headers = ['Customer ID', 'Name', 'Email', 'Phone', 'Bookings', 'Total Spent (FJD)'];

        $rows = User::query()
            ->where('role', 'customer')
            ->leftJoin('bookings', 'bookings.user_id', '=', 'users.id')
            ->selectRaw("users.id, users.name, users.email, users.phone,
                COUNT(bookings.id) as bookings_count,
                COALESCE(SUM(CASE WHEN bookings.status IN ('confirmed','completed') THEN bookings.total_amount ELSE 0 END), 0) as total_spent")
            ->groupBy('users.id', 'users.name', 'users.email', 'users.phone')
            ->orderByDesc('total_spent')
            ->get();

        return $this->streamCsv($filename, $headers, function ($out) use ($rows) {
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->id, $r->name, $r->email, $r->phone,
                    (int) $r->bookings_count,
                    number_format((float) $r->total_spent, 2, '.', ''),
                ]);
            }
        });
    }

    /**
     * Full audit log — respects the same filters as the audit log viewer page
     * (q, action, role, from, to) so admins can archive a precise security
     * trail for the lecturer.
     */
    public function auditLogs(Request $request): StreamedResponse
    {
        $filename = 'rakz-rental-audit-log-'.now()->format('Y-m-d-His').'.csv';
        $headers = [
            'Timestamp', 'Actor Name', 'Actor Role', 'Action',
            'Target Type', 'Target ID', 'Description', 'IP Address', 'User Agent',
        ];

        return $this->streamCsv($filename, $headers, function ($out) use ($request) {
            $query = AuditLog::query()->orderBy('id');

            if ($action = $request->input('action')) {
                $query->where('action', 'like', "%{$action}%");
            }
            if ($role = $request->input('role')) {
                $query->where('actor_role', $role);
            }
            if ($keyword = trim((string) $request->input('q'))) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('description', 'like', "%{$keyword}%")
                      ->orWhere('actor_name', 'like', "%{$keyword}%")
                      ->orWhere('target_id', 'like', "%{$keyword}%");
                });
            }
            if ($from = $request->input('from')) {
                $query->whereDate('created_at', '>=', $from);
            }
            if ($to = $request->input('to')) {
                $query->whereDate('created_at', '<=', $to);
            }

            $query->chunk(500, function ($chunk) use ($out) {
                foreach ($chunk as $log) {
                    fputcsv($out, [
                        $log->created_at?->toDateTimeString(),
                        $log->actor_name,
                        $log->actor_role,
                        $log->action,
                        $log->target_type ? class_basename($log->target_type) : null,
                        $log->target_id,
                        $log->description,
                        $log->ip_address,
                        $log->user_agent,
                    ]);
                }
            });
        });
    }

    /** Shared helper — stream a CSV with a BOM so Excel reads UTF-8 correctly. */
    private function streamCsv(string $filename, array $headers, \Closure $writer): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $writer) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility with special characters.
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $headers);
            $writer($out);
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
