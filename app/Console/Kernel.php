<?php

namespace App\Console;

use App\Console\Commands\Test;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */

    protected $commands = [
        commands\HealthCheck::class,
        Test::class
    ];


    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('health-check')->everyMinute();
        $schedule->call(DB::table('notifications')
            ->orderBy('id', 'desc')
            ->take(5)
            ->skip(0)->get())->everyFiveMinutes();




//        $schedule->command('Test:change')->dailyAt('17:49')->runInBackground();
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
