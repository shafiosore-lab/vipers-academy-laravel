<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use App\Models\Player;

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
        Log::info('pdo_sqlite extension loaded: ' . (extension_loaded('pdo_sqlite') ? 'yes' : 'no'));
        Log::info('pdo_mysql extension loaded: ' . (extension_loaded('pdo_mysql') ? 'yes' : 'no'));
        Log::info('Database path: ' . database_path('database.sqlite'));
        Log::info('DB_CONNECTION: ' . env('DB_CONNECTION'));
        Log::info('DB_HOST: ' . env('DB_HOST'));
        Log::info('DB_PORT: ' . env('DB_PORT'));
        Log::info('DB_DATABASE: ' . env('DB_DATABASE'));
        // Make random players available globally for mega menu
        View::composer('*', function ($view) {
            try {
                $view->with('randomPlayers', Player::inRandomOrder()->take(4)->get());
            } catch (\Exception $e) {
                Log::error('Error fetching random players: ' . $e->getMessage());
                $view->with('randomPlayers', collect());
            }
        });
    }
}
