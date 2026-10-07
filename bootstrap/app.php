<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // The public CBO site is the whole application. It is registered last
        // on purpose: Laravel keys its route collection by method + URI, so a
        // later registration replaces an earlier one for the same URL. Keeping
        // this group last guarantees the public pages own "/" and the other
        // marketing URLs.
        web: __DIR__.'/../routes/site.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Public site only: no tenant scoping, no subscription gates, no
        // role checks. The removed SaaS middleware (AdminMiddleware,
        // CheckSubscriptionAccess, TenantScope, â€¦) went with the dashboards.
        $middleware->validateCsrfTokens(except: []);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Form validation must keep Laravel's default behaviour: redirect back
        // with the error bag and old input. Without this, a catch-all
        // \Throwable handler would intercept ValidationException first and every
        // failed contact submission would render a 500 page instead of showing
        // the validation messages.
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
            }

            return back()
                ->withInput($request->except(['password', 'password_confirmation', 'current_password']))
                ->withErrors($e->errors());
        });
    })->create();
