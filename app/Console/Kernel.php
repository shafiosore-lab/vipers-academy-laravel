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
        Commands\AutoStartTrainingSessions::class,
        Commands\AiInsightsRefreshCommand::class,
        Commands\GenerateMonthlyBillings::class,
        Commands\SendMonthlyReports::class,
        Commands\SendPaymentReminders::class,
        Commands\SendWeeklyAttendanceReport::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * These schedules run frequently to ensure minimal delay in session starts.
     * The 30-second interval ensures sessions start within 30 seconds of their scheduled time.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Primary session auto-start check - runs every 30 seconds for high precision
        $schedule->command('app:auto-start-training-sessions')
            ->everyThirtySeconds()
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/session_automation.log'))
            ->onSuccess(function () {
                // Log successful run
                \Illuminate\Support\Facades\Log::channel('session_automation')->info('Session automation completed successfully');
            })
            ->onFailure(function () {
                // Log failed run
                \Illuminate\Support\Facades\Log::channel('session_automation')->error('Session automation failed');
            });

        // Recovery check - runs every 5 minutes to catch any missed sessions during downtime
        $schedule->command('app:auto-start-training-sessions --recover-missed')
            ->everyFiveMinutes()
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/session_recovery.log'))
            ->onSuccess(function () {
                \Illuminate\Support\Facades\Log::channel('session_automation')->info('Session recovery check completed');
            });

        // Weekly AI insights refresh
        $schedule->command('app:ai-insights-refresh')
            ->weekly()
            ->sundays()
            ->at('02:00')
            ->runInBackground();

        // Monthly billing generation
        $schedule->command('app:generate-monthly-billings')
            ->monthly()
            ->onFirstDayOfMonth()
            ->at('01:00')
            ->runInBackground();

        // Monthly reports
        $schedule->command('app:send-monthly-reports')
            ->monthly()
            ->onFirstDayOfMonth()
            ->at('06:00')
            ->runInBackground();

        // Payment reminders
        $schedule->command('app:send-payment-reminders')
            ->weekly()
            ->mondays()
            ->at('09:00')
            ->runInBackground();

        // Weekly attendance report
        $schedule->command('app:send-weekly-attendance-report')
            ->weekly()
            ->fridays()
            ->at('17:00')
            ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
