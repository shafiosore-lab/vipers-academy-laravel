<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organization;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules;

class RegistrationController extends Controller
{
    /**
     * Display the unified registration form.
     */
    public function index(Request $request)
    {
        // Show the new unified signup page
        return view('auth.signup');
    }

    /**
     * Handle the unified registration request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'account_type' => ['required', 'in:player,organization,coach,team_manager,partner'],
            'organization_name' => ['required_if:account_type,organization,partner', 'nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        // Determine account type based on input
        $accountType = $request->input('account_type');

        // Map account type to role and user_type
        $roleMapping = [
            'player' => [
                'role' => 'player',
                'user_type' => 'player',
            ],
            'organization' => [
                'role' => 'org-admin',
                'user_type' => 'staff',
            ],
            'coach' => [
                'role' => 'coach',
                'user_type' => 'staff',
            ],
            'team_manager' => [
                'role' => 'team-manager',
                'user_type' => 'staff',
            ],
            'partner' => [
                'role' => 'partner',
                'user_type' => 'partner',
            ],
        ];

        $roleInfo = $roleMapping[$accountType] ?? ['role' => 'general', 'user_type' => 'general'];

        // Create organization if account_type is organization or partner
        $organizationId = null;
        if (in_array($accountType, ['organization', 'partner']) && $request->filled('organization_name')) {
            try {
                // Use the model's generateSlug method to handle duplicates
                $slug = Organization::generateSlug($request->organization_name);

                $organization = Organization::create([
                    'name' => $request->organization_name,
                    'slug' => $slug,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'type' => $accountType === 'organization' ? 'football_academy' : 'partner',
                    'status' => 'active',
                ]);
                $organizationId = $organization->id;
            } catch (\Illuminate\Database\QueryException $e) {
                // If organization creation fails (duplicate), try to find existing
                $existingOrg = Organization::where('email', $request->email)
                    ->orWhere('name', $request->organization_name)
                    ->first();
                if ($existingOrg) {
                    $organizationId = $existingOrg->id;
                }
            }
        }

        // Create user
        $userData = [
            'name' => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'user_type' => $roleInfo['user_type'],
            'organization_id' => $organizationId,
            'approval_status' => 'approved',
            'approved_at' => now(),
            'status' => 'active',
        ];

        // Add trial fields ONLY if they exist in the database
        if (Schema::hasColumn('users', 'is_on_trial')) {
            $userData['is_on_trial'] = true;
        }
        if (Schema::hasColumn('users', 'trial_ends_at')) {
            $userData['trial_ends_at'] = now()->addDays(10);
        }
        if (Schema::hasColumn('users', 'trial_type')) {
            $userData['trial_type'] = $accountType;
        }
        if (Schema::hasColumn('users', 'trial_auto_activated')) {
            $userData['trial_auto_activated'] = true;
        }

        $user = User::create($userData);

        // Assign role to user
        $role = Role::where('slug', $roleInfo['role'])->first();

        // If role not found by slug, try alternative lookups
        if (!$role) {
            $roleName = ucwords(str_replace('-', ' ', $roleInfo['role']));
            $role = Role::whereRaw('LOWER(name) = ?', [strtolower($roleName)])->first();

            if (!$role) {
                $role = Role::first();
            }
        }

        if ($role) {
            $user->roles()->attach($role->id);
        }

        // Log in the user
        Auth::login($user);

        // Redirect based on account type
        $dashboardRoute = $this->getDashboardRoute($accountType);

        // Get trial end date if available
        $trialEndsAt = $user->trial_ends_at ?? now()->addDays(10);

        return redirect()->route($dashboardRoute)->with([
            'success' => 'Welcome to GameSuite! Your account has been created successfully.',
            'trial_active' => true,
            'trial_ends_at' => $trialEndsAt,
        ]);
    }

    /**
     * Get the appropriate dashboard route based on account type.
     */
    private function getDashboardRoute(string $accountType): string
    {
        return match($accountType) {
            'player' => 'player.portal.dashboard',
            'organization' => 'organization.dashboard',
            'coach' => 'coach.dashboard',
            'team_manager' => 'manager.dashboard',
            'partner' => 'partner.dashboard',
            default => 'dashboard',
        };
    }
}
