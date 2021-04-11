<?php

namespace App\Console;

use App\Services\BaseConstService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\BackupDatabase::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('telescope:prune --hours=24')->daily()->onOneServer()->emailOutputTo(config('tms.admin_email'));
        $schedule->command('db:backup')->dailyAt('1:00')->onOneServer()->emailOutputTo(config('tms.admin_email'));
        $schedule->command('route:retry')->cron('*/'.BaseConstService::ROUTE_RETRY_INTERVAL_TIME.' * * * *')->onOneServer();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
