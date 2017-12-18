<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Psy\Command\Command;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
      Commands\GetYoutubeVideos::class,
      Commands\VoteReset::class,
      Commands\RefundV8::class,
      Commands\DiscordVoucher::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      $schedule->command('youtube:videos')->cron('0 */5 * * *');
      $schedule->command('vote:reset')->monthlyOn(1, '00:00');
      $schedule->command('bot:discord-voucher')->dailyAt('18:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
