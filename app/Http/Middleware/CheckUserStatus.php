<?php

namespace App\Http\Middleware;

use App\Models\Player;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Routes a player with an expired temporary approval may still visit.
     */
    private const PLAYER_GRACE_ROUTES = [
        'player.portal.dashboard',
        'profile.edit',
        'enrollments',
    ];

    /**
     * Enforce user approval, suspension, and trial status on every web request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Admins and super-admins have unrestricted access
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return $next($request);
        }

        // ── Organization checks ───────────────────────────────────────────
        if ($user->organization_id) {
            $org = $user->organization;

            if ($org?->isSuspended()) {
                return $this->logoutAndRedirect('Your organization\'s subscription has been suspended. Please contact support.');
            }

            if ($org?->isSpam()) {
                return $this->logoutAndRedirect('Your organization has been flagged as spam and suspended.');
            }

            if ($org?->trial_ends_at && $org->isTrialExpired()) {
                return $this->logoutAndRedirect('Your organization\'s trial period has expired. Please upgrade to continue.');
            }
        }

        // ── Personal trial expiration ─────────────────────────────────────
        if ($user->is_on_trial && $user->trial_ends_at && $user->isTrialExpired()) {
            return $this->logoutAndRedirect('Your free trial has expired. Please upgrade to continue.');
        }

        // ── Player temporary approval ─────────────────────────────────────
        if ($user->isPlayer()) {
            try {
                $player = Player::where('email', $user->email)->first();

                if ($player?->isTemporaryApprovalExpired()) {
                    $player->update(['status' => 'pending']);

                    if (!$request->routeIs(self::PLAYER_GRACE_ROUTES)) {
                        return redirect()->route('player.portal.dashboard')
                            ->with('warning', 'Your temporary approval has expired. Please complete all required documents to regain full access.');
                    }
                }
            } catch (\Exception) {
                // Column may not exist yet — skip silently
            }
        }

        // ── Account-level status ──────────────────────────────────────────
        if ($user->status === 'suspended') {
            return $this->logoutAndRedirect('Your account has been suspended. Please contact support.');
        }

        // Pending and approved users pass through; trial/org/staff users also pass through
        if (
            $user->status === 'pending'
            || $user->isApproved()
            || !empty($user->organization_id)
            || $user->user_type === 'staff'
            || ($user->is_on_trial && $user->trial_ends_at && now()->lt($user->trial_ends_at))
        ) {
            return $next($request);
        }

        // Non-player staff without approval — allow with a warning
        if (!$user->isPlayer()) {
            session()->flash('warning', 'Your account is pending approval. Some features may be limited.');
            return $next($request);
        }

        return redirect('/login');
    }

    /**
     * Log the current user out and redirect to home with an error message.
     */
    private function logoutAndRedirect(string $message): Response
    {
        Auth::logout();

        return redirect('/')->with('error', $message);
    }
}
