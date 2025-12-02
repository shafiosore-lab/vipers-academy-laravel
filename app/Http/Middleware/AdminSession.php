<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is admin
        if (Auth::check() && Auth::user()->isAdmin()) {
            // Extend session lifetime for admin users
            $request->session()->put('admin_last_activity', now());

            // Ensure admin users are redirected to admin panel if they try to access public routes
            $currentRoute = $request->route();
            if ($currentRoute && !str_starts_with($currentRoute->getPrefix(), '/admin')) {
                // Check if this is not an API or asset request
                if (!$this->isPublicAsset($request)) {
                    // Redirect to admin dashboard
                    return redirect()->route('admin.dashboard');
                }
            }

            // Add admin context to all admin routes
            if (str_starts_with($request->getPathInfo(), '/admin')) {
                // Ensure session doesn't expire for admin users
                $request->session()->put('_admin_token', md5(Auth::id() . config('app.key')));
            }
        }

        return $next($request);
    }

    /**
     * Check if the request is for a public asset that shouldn't redirect
     */
    private function isPublicAsset(Request $request): bool
    {
        $path = $request->getPathInfo();

        // Allow access to public assets
        return str_starts_with($path, '/css/') ||
               str_starts_with($path, '/js/') ||
               str_starts_with($path, '/images/') ||
               str_starts_with($path, '/assets/') ||
               str_starts_with($path, '/favicon') ||
               str_starts_with($path, '/storage/') ||
               str_starts_with($path, '/_debugbar/') || // Debugbar
               $path === '/login' ||
               $path === '/register' ||
               str_starts_with($path, '/password/') ||
               $path === '/';
    }
}
