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

        // DIAGNOSTIC LOGGING
        \Log::info('PlayerMiddleware: User details', [
            'user_id' => $user->id,
            'email' => $user->email,
            'user_type' => $user->user_type,
            'is_player' => $user->isPlayer(),
            'is_approved' => $user->isApproved(),
            'has_player_role' => $user->hasRole('player'),
            'has_player_record' => $user->player ? true : false,
            'roles' => $user->roles->pluck('slug')->toArray(),
        ]);

        // Check if user has player role (using SLUG)
        // Allow access if user has player role OR user_type = player
        $hasPlayerRole = $user->hasRole('player');
        $isPlayerType = $user->isPlayer();

        // Also check if user has partner roles (they might have both roles)
        $partnerRoles = ['partner', 'parent', 'partner-marketing', 'partner-scouting', 'partner-operations'];
        $hasPartnerRole = $user->hasAnyRole($partnerRoles);
        $isPartnerType = $user->user_type === 'partner';
        $isStaffType = $user->user_type === 'staff';

        // If user has partner roles/type, allow access even if they also have player role
        if ($hasPartnerRole || $isPartnerType || $isStaffType) {
            \Log::info('PlayerMiddleware: User has partner access, allowing despite player role', [
                'user_id' => $user->id,
                'user_type' => $user->user_type,
                'user_roles' => $user->roles->pluck('slug')->toArray(),
            ]);
            return $next($request);
        }

        if (!$hasPlayerRole && !$isPlayerType) {
            \Log::warning('PlayerMiddleware: Access denied - missing player role', [
                'user_id' => $user->id,
                'user_type' => $user->user_type,
                'user_roles' => $user->roles->pluck('slug')->toArray(),
            ]);
            abort(403, 'Access denied. Player privileges required.');
        }

        // Check if user is approved
        if (!$user->isApproved()) {
            return redirect('/')->with('error', 'Your account is pending approval. Please contact the academy administration.');
        }

        // For users with player role but no player record, allow access to player portal
        // They can view their dashboard but may have limited functionality
        if (!$user->player) {
            \Log::info('PlayerMiddleware: User has player role but no player record - allowing access', ['user_id' => $user->id]);
            return $next($request);
        }

        // Check if player exists and is approved
        $player = $user->player;
        if (!$player || !$player->isApproved()) {
            return redirect('/')->with('error', 'Your player profile is pending approval. Please contact the academy administration.');
        }

        \Log::info('PlayerMiddleware: Access granted', ['user_id' => $user->id]);
        return $next($request);
    }
}
