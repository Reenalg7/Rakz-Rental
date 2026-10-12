<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Nightly at 00:30: auto-complete any confirmed booking whose return
        // date has passed. Enable in production by running:
        //   * * * * * cd /path-to-app && php artisan schedule:run >> /dev/null 2>&1
        $schedule->command('bookings:mark-completed')
            ->dailyAt('00:30')
            ->timezone(config('app.timezone'));

        // Each morning at 08:00: email customers whose return date is tomorrow.
        $schedule->command('bookings:send-return-reminders')
            ->dailyAt('08:00')
            ->timezone(config('app.timezone'));
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
