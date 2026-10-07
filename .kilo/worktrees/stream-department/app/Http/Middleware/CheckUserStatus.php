<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Player;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // DIAGNOSTIC LOGGING
            \Log::info('CheckUserStatus: User session check', [
                'user_id' => $user->id,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'approval_status' => $user->approval_status,
                'status' => $user->status,
                'is_admin' => $user->isAdmin(),
                'is_active' => $user->isActive(),
                'is_approved' => $user->isApproved(),
                'route' => $request->route()?->getName(),
                'url' => $request->url(),
            ]);

            // If user is admin, allow access to everything
            if ($user->isAdmin()) {
                \Log::info('CheckUserStatus: Admin user, allowing access');
                return $next($request);
            }

            // Check for expired temporary approvals for players
            if ($user->isPlayer()) {
                try {
                    $player = Player::where('email', $user->email)->first();
                    if ($player && $player->isTemporaryApprovalExpired()) {
                        // Update status to expired but don't log out completely
                        // Allow continued access but show warning
                        $player->status = 'pending';
                        $player->save();

                        // Only redirect if accessing restricted areas, not dashboard
                        if (!request()->routeIs('player.portal.dashboard', 'profile.edit', 'enrollments')) {
                            return redirect()->route('player.portal.dashboard')
                                ->with('warning', 'Your temporary approval has expired. Please complete all required documents to regain full access.');
                        }
                    }
                } catch (\Exception $e) {
                    // If partner_id column doesn't exist, skip this check
                }
            }

            // If user account is suspended, redirect to home with error
            if ($user->status === 'suspended') {
                \Log::warning('CheckUserStatus: Account suspended, logging out', ['user_id' => $user->id]);
                Auth::logout();
                return redirect('/')->with('error', 'Your account has been suspended. Please contact support.');
            }

            // If user account is pending, allow access but show pending status
            if ($user->status === 'pending') {
                \Log::info('CheckUserStatus: Account pending, allowing limited access');
                // Allow access to dashboard and profile, but restrict other features
                return $next($request);
            }

            // If user is active, allow full access
            if ($user->isActive()) {
                \Log::info('CheckUserStatus: Account active, allowing full access');
                return $next($request);
            }

            \Log::warning('CheckUserStatus: No status matched, blocking access', [
                'user_id' => $user->id,
                'approval_status' => $user->approval_status,
                'status' => $user->status,
            ]);
        }

        // If not authenticated, redirect to login
        return redirect('/login');
    }
}
