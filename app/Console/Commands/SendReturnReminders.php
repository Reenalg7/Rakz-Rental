<?php

namespace App\Console\Commands;

use App\Mail\ReturnDateReminder;
use App\Models\Booking;
use App\Models\Notification;
use App\Support\AuditLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * php artisan bookings:send-return-reminders
 * ---------------------------------------------------------------------------
 * Scheduled daily (08:00). For every confirmed booking whose return_date is
 * TOMORROW, emails the customer a friendly reminder and creates an in-app
 * notification. Keeps a `reminded_at` style de-dupe check out of scope — the
 * scheduler only runs once per day so it cannot double-fire naturally.
 */
class SendReturnReminders extends Command
{
    protected $signature = 'bookings:send-return-reminders {--dry : List what would be sent without emailing}';
    protected $description = 'Email customers whose booking return date is tomorrow';

    public function handle(): int
    {
        $tomorrow = now()->addDay()->toDateString();

        $bookings = Booking::with(['user', 'vehicle'])
            ->where('status', 'confirmed')
            ->whereDate('return_date', $tomorrow)
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('No return reminders to send today.');
            return self::SUCCESS;
        }

        if ($this->option('dry')) {
            $this->warn("Dry run — would send {$bookings->count()} reminder(s):");
            foreach ($bookings as $b) {
                $this->line(" - #{$b->id} to {$b->user?->email} (vehicle {$b->vehicle_registration_no})");
            }
            return self::SUCCESS;
        }

        $sent = 0;
        foreach ($bookings as $b) {
            if (!$b->user?->email) {
                continue;
            }
            try {
                Mail::to($b->user->email)->send(new ReturnDateReminder($b));

                Notification::create([
                    'user_id' => $b->user_id,
                    'title' => 'Return reminder: Booking #'.$b->id,
                    'message' => "Please return vehicle {$b->vehicle_registration_no} tomorrow ({$b->return_date->toDateString()}).",
                    'type' => 'warning',
                ]);

                $sent++;
            } catch (\Throwable $e) {
                Log::warning("Return reminder failed for booking #{$b->id}: ".$e->getMessage());
            }
        }

        $this->info("Sent {$sent} return reminder(s).");
        AuditLogger::log('scheduler.return_reminders', "Scheduler sent {$sent} return reminder(s)");
        return self::SUCCESS;
    }
}
