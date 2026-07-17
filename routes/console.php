<?php

use App\Console\Commands\SendReviewReminders;
use App\Console\Commands\UpdateEventStatuses;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * Run daily at 09:00 server time.
 * Sends in-app review reminder notifications to attendees of events
 * that ended the day before.
 *
 * To run manually:    php artisan review:remind
 * To preview (safe):  php artisan review:remind --dry-run
 *
 * To activate the scheduler on XAMPP, add this cron entry on Mac/Linux:
 *   * * * * * cd /Applications/XAMPP/xamppfiles/htdocs/24.12.3280_P3_AmikomHub/laravel-app && php artisan schedule:run >> /dev/null 2>&1
 */
Schedule::command(SendReviewReminders::class)
    ->dailyAt('09:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/review-reminders.log'));

Schedule::command(UpdateEventStatuses::class)
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command(\App\Console\Commands\CheckAbandonedCarts::class)
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground();
