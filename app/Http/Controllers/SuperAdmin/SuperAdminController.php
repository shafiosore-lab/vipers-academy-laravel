<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SuperAdminController extends Controller
{
    /**
     * Display the Super Admin dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_organizations' => Organization::count(),
            'active_organizations' => Organization::active()->count(),
            'trial_organizations' => Organization::onTrial()->count(),
            'total_users' => User::whereNotNull('organization_id')->count(),
            'total_players' => \App\Models\Player::count(),
            'total_revenue' => $this->calculateTotalRevenue(),
            'active_subscriptions' => Subscription::active()->count(),
        ];

        $recentOrganizations = Organization::latest()->take(10)->get();
        $recentSubscriptions = Subscription::with('organization', 'plan')
            ->latest()
            ->take(10)
            ->get();

        return view('super-admin.dashboard', compact('stats', 'recentOrganizations', 'recentSubscriptions'));
    }

    /**
     * List all organizations.
     */
    public function organizations(Request $request)
    {
        $query = Organization::with('subscriptionPlan', 'subscription');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $organizations = $query->latest()->paginate(20);

        return view('super-admin.organizations.index', compact('organizations'));
    }

    /**
     * Show organization details.
     */
    public function showOrganization(Organization $organization)
    {
        $organization->load('subscriptionPlan', 'subscription', 'users', 'createdBy');

        $stats = [
            'total_users' => $organization->users()->count(),
            'total_players' => $organization->players()->count(),
            'active_users' => $organization->users()->where('approval_status', 'approved')->count(),
        ];

        return view('super-admin.organizations.show', compact('organization', 'stats'));
    }

    /**
     * Show create organization form.
     */
    public function createOrganization()
    {
        $plans = SubscriptionPlan::active()->ordered()->get();
        return view('super-admin.organizations.create', compact('plans'));
    }

    /**
     * Store new organization.
     */
    public function storeOrganization(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:organizations,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'plan_id' => 'nullable|exists:subscription_plans,id',
            'max_users' => 'nullable|integer|min:1',
            'max_players' => 'nullable|integer|min:1',
            'admin_email' => 'required|email',
            'admin_password' => 'required|string|min:8',
            'admin_name' => 'required|string|max:255',
        ]);

        // Create organization
        $organization = Organization::create([
            'name' => $request->name,
            'slug' => Organization::generateSlug($request->name),
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'description' => $request->description,
            'status' => 'active',
            'subscription_plan_id' => $request->plan_id,
            'max_users' => $request->max_users ?? 10,
            'max_players' => $request->max_players ?? 100,
            'billing_cycle' => 'monthly',
            'created_by' => auth()->id(),
        ]);

        // Create organization admin user
        $adminUser = User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'user_type' => 'admin',
            'approval_status' => 'approved',
            'status' => 'active',
            'organization_id' => $organization->id,
        ]);

        // Assign org_admin role
        $adminUser->assignRole('org-admin');

        // Create subscription if plan selected
        if ($request->plan_id) {
            $plan = SubscriptionPlan::find($request->plan_id);

            Subscription::create([
                'organization_id' => $organization->id,
                'plan_id' => $request->plan_id,
                'name' => 'default',
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
            ]);

            // Update organization limits based on plan
            $organization->update([
                'max_users' => $plan->max_users,
                'max_players' => $plan->max_players,
            ]);
        }

        Log::info('Super Admin: Created organization', [
            'organization_id' => $organization->id,
            'organization_name' => $organization->name,
            'admin_user_id' => $adminUser->id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('super-admin.organizations.show', $organization)
            ->with('success', "Organization '{$organization->name}' has been created successfully.");
    }

    /**
     * Show edit organization form.
     */
    public function editOrganization(Organization $organization)
    {
        $plans = SubscriptionPlan::active()->ordered()->get();
        $organization->load('subscriptionPlan', 'subscription');
        return view('super-admin.organizations.edit', compact('organization', 'plans'));
    }

    /**
     * Update organization.
     */
    public function updateOrganization(Request $request, Organization $organization)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('organizations')->ignore($organization->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended,trial,pending',
            'plan_id' => 'nullable|exists:subscription_plans,id',
            'max_users' => 'nullable|integer|min:1',
            'max_players' => 'nullable|integer|min:1',
        ]);

        $organization->update($request->only([
            'name', 'email', 'phone', 'address', 'description', 'status',
            'subscription_plan_id', 'max_users', 'max_players'
        ]));

        Log::info('Super Admin: Updated organization', [
            'organization_id' => $organization->id,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('super-admin.organizations.show', $organization)
            ->with('success', 'Organization updated successfully.');
    }

    /**
     * Activate/Deactivate organization.
     */
    public function toggleOrganizationStatus(Organization $organization)
    {
        $newStatus = $organization->status === 'active' ? 'suspended' : 'active';

        $organization->update(['status' => $newStatus]);

        Log::info('Super Admin: Toggled organization status', [
            'organization_id' => $organization->id,
            'new_status' => $newStatus,
            'updated_by' => auth()->id(),
        ]);

        return back()->with('success', "Organization status changed to '{$newStatus}'.");
    }

    /**
     * List subscription plans.
     */
    public function plans()
    {
        $plans = SubscriptionPlan::active()->ordered()->get();
        return view('super-admin.plans.index', compact('plans'));
    }

    /**
     * Show create plan form.
     */
    public function createPlan()
    {
        return view('super-admin.plans.create');
    }

    /**
     * Store subscription plan.
     */
    public function storePlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:subscription_plans,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'max_users' => 'nullable|integer',
            'max_players' => 'nullable|integer',
            'max_staff' => 'nullable|integer',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
        ]);

        $features = $request->features ?? [];

        // Convert checkbox values to boolean
        foreach ($features as $key => $value) {
            $features[$key] = $value === 'on' || $value === true;
        }

        SubscriptionPlan::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'price' => $request->price,
            'billing_cycle' => $request->billing_cycle,
            'max_users' => $request->max_users ?? -1,
            'max_players' => $request->max_players ?? -1,
            'max_staff' => $request->max_staff ?? -1,
            'features' => $features,
            'is_active' => $request->is_active ?? true,
            'is_popular' => $request->is_popular ?? false,
        ]);

        return redirect()->route('super-admin.plans.index')
            ->with('success', 'Subscription plan created successfully.');
    }

    /**
     * Show edit plan form.
     */
    public function editPlan(SubscriptionPlan $plan)
    {
        return view('super-admin.plans.edit', compact('plan'));
    }

    /**
     * Update subscription plan.
     */
    public function updatePlan(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', Rule::unique('subscription_plans')->ignore($plan->id)],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'max_users' => 'nullable|integer',
            'max_players' => 'nullable|integer',
            'max_staff' => 'nullable|integer',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
        ]);

        $features = $request->features ?? [];

        // Convert checkbox values to boolean
        foreach ($features as $key => $value) {
            $features[$key] = $value === 'on' || $value === true;
        }

        $plan->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'price' => $request->price,
            'billing_cycle' => $request->billing_cycle,
            'max_users' => $request->max_users ?? -1,
            'max_players' => $request->max_players ?? -1,
            'max_staff' => $request->max_staff ?? -1,
            'features' => $features,
            'is_active' => $request->is_active ?? true,
            'is_popular' => $request->is_popular ?? false,
        ]);

        return redirect()->route('super-admin.plans.index')
            ->with('success', 'Subscription plan updated successfully.');
    }

    /**
     * List all users (global).
     */
    public function users(Request $request)
    {
        $query = User::with('roles', 'organization');

        if ($request->has('organization_id') && $request->organization_id) {
            $query->where('organization_id', $request->organization_id);
        }

        if ($request->has('role') && $request->role) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('slug', $request->role);
            });
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);
        $organizations = Organization::active()->get();
        $roles = \App\Models\Role::all();

        return view('super-admin.users.index', compact('users', 'organizations', 'roles'));
    }

    /**
     * Calculate total revenue from active subscriptions.
     */
    private function calculateTotalRevenue(): float
    {
        return Subscription::active()
            ->with('plan')
            ->get()
            ->sum(function ($subscription) {
                return $subscription->plan ? $subscription->plan->price : 0;
            });
    }

    /**
     * Display subscription analytics.
     */
    public function analytics()
    {
        $subscriptionStats = [
            'active' => Subscription::active()->count(),
            'trialing' => Subscription::onTrial()->count(),
            'canceled' => Subscription::canceled()->count(),
        ];

        $planDistribution = Subscription::with('plan')
            ->get()
            ->groupBy('plan_id')
            ->map(function ($subscriptions) {
                return $subscriptions->count();
            });

        $monthlyRevenue = $this->calculateMonthlyRevenue();

        return view('super-admin.analytics', compact('subscriptionStats', 'planDistribution', 'monthlyRevenue'));
    }

    /**
     * Calculate monthly revenue trend.
     */
    private function calculateMonthlyRevenue(): array
    {
        $revenue = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            $monthlySubscriptions = Subscription::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->with('plan')
                ->get();

            $revenue[] = [
                'month' => $month->format('M Y'),
                'amount' => $monthlySubscriptions->sum(function ($sub) {
                    return $sub->plan ? $sub->plan->price : 0;
                }),
            ];
        }

        return $revenue;
    }
}
