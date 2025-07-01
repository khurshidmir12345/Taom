<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            
            // Schedule daily reminders at 10:00 AM every day
            $schedule->command('reminders:send-daily')
                ->dailyAt('10:00')
                ->withoutOverlapping()
                ->runInBackground();
        });
    }
}
