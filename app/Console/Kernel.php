<?php

namespace App\Console;

use App\Jobs\PingAllCustomersJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Generate monthly invoices on the 1st day of each month at 02:00
        $schedule->command('invoices:generate')->monthlyOn(1, '02:00');

        // Sync account receivables daily at 01:30
        $schedule->command('receivables:sync')->dailyAt('01:30');

        // $schedule->job(new PingAllCustomersJob)->everyTenMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
