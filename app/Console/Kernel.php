<?php

namespace App\Console;

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
         Commands\NewsletterCron::class,
         Commands\VideoConverterCron::class,
         Commands\SchedulePostCron::class,
         Commands\QueueWorkCron::class,
         Commands\RssImportCron::class,
         Commands\AllClear::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('optimize:clear')
        //         ->emailOutputTo('ex4useonly@gmail.com');
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->command('newsletter:cron')->withoutOverlapping()->everyFiveMinutes();
        $schedule->command('videoConverter:cron')->withoutOverlapping()->everyThirtyMinutes();
        $schedule->command('rssImport:cron')->withoutOverlapping()->daily();
        $schedule->command('schedulepost:cron')->withoutOverlapping()->everyMinute();
        //$schedule->command('queue:cron')->withoutOverlapping()->everyMinute();
        // */5	*	*	*	* /usr/local/bin/php /home/project_path/artisan newsletter:cron >> /dev/null 2>&1
        // 0,30	*	*	*	* /usr/local/bin/php /home/project_path/artisan videoConverter:cron >> /dev/null 2>&1
        // *	*	*	*	* /usr/local/bin/php /home/project_path/artisan schedulepost:cron >> /dev/null 2>&1

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
