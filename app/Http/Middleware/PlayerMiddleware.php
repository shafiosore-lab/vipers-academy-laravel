<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PlayerMiddleware
{
    /**
     * Roles and types that bypass the player-specific approval check.
     * Approval status is handled upstream by CheckUserStatus.
     */
    private const BYPASS_ROLES = [
        'partner', 'parent', 'partner-marketing', 'partner-scouting', 'partner-operations',
    ];

    private const BYPASS_TYPES = ['partner', 'staff'];

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this area.');
        }

        $user = Auth::user();

        // Super-admins bypass all checks
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Partner/staff users pass through without player-specific checks
        if (in_array($user->user_type, self::BYPASS_TYPES) || $user->hasAnyRole(self::BYPASS_ROLES)) {
            return $next($request);
        }

        if (!$user->hasRole('player') && !$user->isPlayer()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access that area.');
        }

        // Player with no linked record — allow access with potentially limited functionality
        $player = $user->player;

        if (!$player) {
            return $next($request);
        }

        if (!$player->isApproved()) {
            return redirect('/')->with('error', 'Your player profile is pending approval. Please contact the academy administration.');
        }

        return $next($request);
    }
}
