<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PlayerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this area.');
        }

        $user = Auth::user();

        // Check if user has player role
        if (!$user->hasRole('player')) {
            abort(403, 'Access denied. Player privileges required.');
        }

        // Check if user is approved
        if (!$user->isApproved()) {
            return redirect('/')->with('error', 'Your account is pending approval. Please contact the academy administration.');
        }

        // Check if player exists and is approved
        $player = $user->player;
        if (!$player || !$player->isApproved()) {
            return redirect('/')->with('error', 'Your player profile is pending approval. Please contact the academy administration.');
        }

        return $next($request);
    }
}
