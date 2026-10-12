<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Notification;
use App\Support\AuditLogger;
use Illuminate\Console\Command;

/**
 * php artisan bookings:mark-completed
 * ---------------------------------------------------------------------------
 * Scheduled daily. Marks any `confirmed` booking whose `return_date` is in
 * the past as `completed`, and creates a notification for the customer.
 *
 * `pending` bookings are left alone — those still need admin review.
 * `cancelled` and already-`completed` bookings are untouched.
 */
class MarkBookingsCompleted extends Command
{
    protected $signature = 'bookings:mark-completed {--dry : List what would be updated without changing anything}';
    protected $description = 'Mark confirmed bookings whose return date has passed as completed';

    public function handle(): int
    {
        $today = now()->toDateString();

        $query = Booking::where('status', 'confirmed')
            ->whereDate('return_date', '<', $today);

        $count = $query->count();

        if ($count === 0) {
            $this->info('No bookings need to be marked as completed.');
            return self::SUCCESS;
        }

        if ($this->option('dry')) {
            $this->warn("Dry run — would mark {$count} booking(s) as completed:");
            $query->get(['id', 'user_id', 'vehicle_registration_no', 'return_date'])
                ->each(fn ($b) => $this->line(" - #{$b->id} vehicle {$b->vehicle_registration_no} returned {$b->return_date->toDateString()}"));
            return self::SUCCESS;
        }

        $bookings = $query->get();

        foreach ($bookings as $booking) {
            $booking->update(['status' => 'completed']);

            Notification::create([
                'user_id' => $booking->user_id,
                'title' => 'Booking #'.$booking->id.' Completed',
                'message' => "Your rental of vehicle {$booking->vehicle_registration_no} ended on {$booking->return_date->toDateString()}. We hope you enjoyed your trip!",
                'type' => 'success',
            ]);
        }

        $this->info("Marked {$count} booking(s) as completed.");
        AuditLogger::log('scheduler.mark_completed', "Scheduler auto-completed {$count} booking(s)");
        return self::SUCCESS;
    }
}
