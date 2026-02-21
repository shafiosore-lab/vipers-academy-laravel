<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \Laravel\Socialite\SocialiteServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/auth.php'));
        },
    )
    ->withSchedule(function ($schedule) {
        // Send monthly reports on the 4th of every month at 9 AM
        $schedule->command('app:send-monthly-reports')
            ->monthlyOn(4, '09:00');

        // Generate monthly billings on the 1st of every month at 12 AM
        $schedule->command('app:generate-monthly-billings')
            ->monthlyOn(1, '00:00');

        // Send payment reminders on the 5th and 15th of every month at 10 AM
        $schedule->command('app:send-payment-reminders')
            ->monthlyOn(5, '10:00')
            ->monthlyOn(15, '10:00');

        // Send weekly attendance report every Sunday at 7 PM
        $schedule->command('attendance:send-weekly-report')
            ->weeklyOn(0, '19:00'); // 0 = Sunday, 19:00 = 7 PM

        // Auto-start training sessions every minute
        $schedule->command('app:auto-start-training-sessions')
            ->everyMinute();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'check.status' => \App\Http\Middleware\CheckUserStatus::class,
            'admin.session' => \App\Http\Middleware\AdminSession::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'player' => \App\Http\Middleware\PlayerMiddleware::class,
            'partner' => \App\Http\Middleware\PartnerMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'tenant' => \App\Http\Middleware\TenantScope::class,
            'super.admin' => \App\Http\Middleware\CheckSuperAdmin::class,
            'feature' => \App\Http\Middleware\CheckFeature::class,
            'Socialite' => \Laravel\Socialite\Facades\Socialite::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
