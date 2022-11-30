<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('telescope:prune --hours=62')
            ->dailyAt('00:00')->withoutOverlapping();

        $schedule->command('queue:prune-failed --hours=62')
            ->dailyAt('00:00')->withoutOverlapping();

        $schedule->command('passport:purge')
            ->dailyAt('00:00')->withoutOverlapping();

        $schedule->command('queue:retry all')
            ->dailyAt('00:00')->withoutOverlapping();

        $schedule->command('notification:expired')
            ->dailyAt('00:00')->withoutOverlapping()->appendOutputTo(storage_path('logs/cronLogs.log'));

        $schedule->command('backup:clean')
            ->weeklyOn(1, '00:30')->withoutOverlapping();

        $schedule->command('backup:run')
            ->weeklyOn(1, '01:00')->withoutOverlapping();

        $schedule->command('logs:clear')
            ->weeklyOn(1, '01:30')->withoutOverlapping()->appendOutputTo(storage_path('logs/cronLogs.log'));
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
