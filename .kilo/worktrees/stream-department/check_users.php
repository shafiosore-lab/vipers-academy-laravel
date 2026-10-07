<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// Boot the application
$app->boot();

// Check the users
$users = App\Models\User::whereIn('email', ['admin@mumiasvipers.com', 'coach@mumiasvipers.com'])
    ->select('email', 'user_type', 'approval_status')
    ->get();

echo "Users found:\n";
foreach ($users as $user) {
    echo "Email: {$user->email}\n";
    echo "User Type: {$user->user_type}\n";
    echo "Approval Status: {$user->approval_status}\n";
    echo "---\n";
}
