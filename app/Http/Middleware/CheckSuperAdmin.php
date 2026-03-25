<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSuperAdmin
{
    /**
     * Restrict access to super-admins only.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this area.');
        }

        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access that area.');
        }

        return $next($request);
    }
}
