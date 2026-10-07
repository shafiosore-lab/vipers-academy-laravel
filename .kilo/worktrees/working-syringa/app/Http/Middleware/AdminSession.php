<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminSession
{
    /**
     * Paths that should never trigger an admin redirect.
     */
    private const BYPASS_PREFIXES = [
        '/css/', '/js/', '/images/', '/assets/',
        '/favicon', '/storage/', '/_debugbar/',
        '/login', '/register', '/password/', '/logout',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return $next($request);
        }

        $path = $request->getPathInfo();

        // Track last activity for admin session management
        $request->session()->put('admin_last_activity', now());

        // On admin routes, stamp a lightweight session token
        if (str_starts_with($path, '/admin')) {
            $request->session()->put(
                '_admin_token',
                md5(Auth::id() . config('app.key'))
            );
        }

        // Redirect admins away from non-admin, non-asset routes
        if (!str_starts_with($path, '/admin') && !$this->isBypassedPath($path)) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }

    /**
     * Determine whether the path should bypass admin redirection.
     */
    private function isBypassedPath(string $path): bool
    {
        foreach (self::BYPASS_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
