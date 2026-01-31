<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * AI Insights Scheduled Commands
 *
 * The AI Insights refresh command runs automatically based on configured schedule.
 * Default: Every Friday at 2:00 AM server time.
 *
 * To modify the schedule, edit the Schedule::command() lines below
 * or configure via environment variable AI_INSIGHTS_REFRESH_PATTERN.
 */

// Schedule AI Insights refresh - runs every Friday at 2:00 AM
Schedule::command('ai:insights:refresh')
    ->weekly()
    ->fridays()
    ->at('2:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/ai-insights-schedule.log'))
    ->onOneServer();

// Alternative schedules (commented out - uncomment to use)
/*
// Daily at 2:00 AM
Schedule::command('ai:insights:refresh')
    ->dailyAt('2:00')
    ->withoutOverlapping()
    ->runInBackground();

// Weekly on Monday at 6:00 AM
Schedule::command('ai:insights:refresh')
    ->weekly()
    ->mondays()
    ->at('6:00')
    ->withoutOverlapping()
    ->runInBackground();
*/
