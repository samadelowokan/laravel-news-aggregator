<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Services\ArticleFetchingService;
use App\Console\Commands\FetchStoreArticles;

class Kernel extends ConsoleKernel{
     /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')->everyMinute()->sendOutputTo('task-output.log');

        $schedule->command('app:fetch-store-articles')->hourly()->sendOutputTo('task-output.log');

        $schedule->call(function () {
            Log::info('Fetching Articles...');
            app(ArticleFetchingService::class)->fetchArticles();
        })->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

}