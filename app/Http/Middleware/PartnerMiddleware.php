<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PartnerMiddleware
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
        \Log::info('PartnerMiddleware: User details', [
            'user_id' => $user->id,
            'email' => $user->email,
            'user_type' => $user->user_type,
            'is_partner' => $user->isPartner(),
            'is_approved' => $user->isApproved(),
            'roles' => $user->roles->pluck('slug')->toArray(),
        ]);

        // Check if user has any partner-related role (using SLUGS)
        $partnerRoles = ['partner', 'parent', 'partner-marketing', 'partner-scouting', 'partner-operations'];
        $hasPartnerRole = $user->hasAnyRole($partnerRoles);

        // Also check for coach roles to allow coaches to access partner features
        $coachRoles = ['coach', 'assistant-coach', 'head-coach'];
        $hasCoachRole = $user->hasAnyRole($coachRoles);

        // Check user_type for partner
        $isPartnerType = $user->user_type === 'partner';

        // Check user_type for staff (staff can also access certain partner features)
        $isStaffType = $user->user_type === 'staff';

        \Log::info('PartnerMiddleware: Role check', [
            'user_id' => $user->id,
            'user_type' => $user->user_type,
            'user_roles' => $user->roles->pluck('slug')->toArray(),
            'required_partner_roles' => $partnerRoles,
            'has_partner_role' => $hasPartnerRole,
            'has_coach_role' => $hasCoachRole,
            'is_partner_type' => $isPartnerType,
            'is_staff_type' => $isStaffType,
        ]);

        // Allow users with partner user_type, partner roles, coach roles, or staff user_type
        // Priority: partner user_type > partner roles > coach roles > staff
        if ($isPartnerType || $hasPartnerRole || $hasCoachRole || $isStaffType) {
            // Check if user is approved
            if (!$user->isApproved()) {
                return redirect('/')->with('error', 'Your account is pending approval. Please contact the academy administration.');
            }

            \Log::info('PartnerMiddleware: Access granted', ['user_id' => $user->id]);
            return $next($request);
        }

        // If we get here, user doesn't have partner access - check if they have player access
        // but DON'T automatically block them - let them try their appropriate dashboard
        \Log::warning('PartnerMiddleware: Access denied - user does not have partner role or type', [
            'user_id' => $user->id,
            'user_type' => $user->user_type,
            'user_roles' => $user->roles->pluck('slug')->toArray(),
        ]);
        abort(403, 'Access denied. Partner privileges required.');
    }
}
