<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin Audit Log viewer — read-only, filterable list of every tracked
 * administrator action (login/logout, booking status changes, vehicle
 * CRUD, replacements, scheduler runs, etc.).
 */
class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::with('user')->latest();

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

        $logs = $query->paginate(25)->withQueryString();
        $actions = AuditLog::query()->distinct()->orderBy('action')->pluck('action');

        return view('admin.audit_logs.index', compact('logs', 'actions'));
    }
}
