<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

use App\Subscription;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // 
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        if(Schema::hasTable('subscriptions')) {
            $now = Carbon::Now('utc');
            $activeSubscription = Subscription::whereIn('status', ['ACTIVE', 'ON-HOLD'])->whereDate('end_date', '>', $now)->orWhere('end_date', null)->get();
            foreach($activeSubscription as $subscription) {
                $schedule->call(function() use ($subscription){
                    // charge people subscription amount
                    $subscription->chargeSubscriptionPrice('background');
                })->cron($subscription->cronFrequency());
            }
        }

        //Upcoming webinar reminder email
        $schedule->command('webinar:reminder')->everyMinute();
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
