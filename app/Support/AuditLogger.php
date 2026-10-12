<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * AuditLogger — thin static helper for writing audit log rows.
 * Keeps controllers readable: `AuditLogger::log('booking.replace', ...)`.
 *
 * All failures are swallowed so an audit-log problem can never block the
 * real action (e.g. you can still log in even if the audit table is missing).
 */
class AuditLogger
{
    public static function log(
        string $action,
        ?string $description = null,
        ?string $targetType = null,
        ?string $targetId = null
    ): void {
        try {
            $user = Auth::user();
            AuditLog::create([
                'user_id' => $user?->id,
                'actor_name' => $user?->name,
                'actor_role' => $user?->role,
                'action' => $action,
                'target_type' => $targetType,
                'target_id' => $targetId,
                'description' => $description,
                'ip_address' => Request::ip(),
                'user_agent' => substr((string) Request::userAgent(), 0, 255),
            ]);
        } catch (\Throwable $e) {
            // Intentionally silent — audit must never break the main flow.
        }
    }
}
