<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     * Ensures only Super Admins can access the route.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this area.');
        }

        if (!$user->isSuperAdmin()) {
            abort(403, 'Access denied. Super Admin privileges required.');
        }

        return $next($request);
    }
}
