<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrganizationManagementController extends Controller
{
    /**
     * Display the unified organization management dashboard.
     * Combines analytics dashboard with management interface.
     */
    public function index(Request $request)
    {
        // Get dashboard statistics
        $stats = $this->getDashboardStats();

        // Get analytics data for charts
        $analytics = $this->getAnalyticsData();

        // Get recent organizations
        $recentOrganizations = $this->getRecentOrganizations();

        // Get organizations with advanced filtering
        $query = Organization::with(['subscription.plan', 'users']);

        // Advanced filtering
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('plan')) {
            $query->whereHas('subscription.plan', function($q) use ($request) {
                $q->where('id', $request->input('plan'));
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $organizations = $query->paginate(15);

        // Get filter options
        $statuses = Organization::select('status')->distinct()->pluck('status');
        $plans = SubscriptionPlan::all();

        return view('super-admin.organizations.index', compact('organizations', 'statuses', 'plans', 'stats', 'analytics', 'recentOrganizations'));
    }

    /**
     * Display the organization dashboard page (analytics overview).
     */
    public function dashboard()
    {
        // Get dashboard statistics
        $stats = $this->getDashboardStats();

        // Get analytics data for charts
        $analytics = $this->getAnalyticsData();

        return view('super-admin.organizations.dashboard', compact('stats', 'analytics'));
    }

    /**
     * Display the recent organizations page.
     */
    public function recent()
    {
        // Get recent organizations
        $recentOrganizations = $this->getRecentOrganizations();

        return view('super-admin.organizations.recent', compact('recentOrganizations'));
    }

    /**
     * Show the form for creating a new organization.
     */
    public function create()
    {
        $plans = SubscriptionPlan::all();
        return view('super-admin.organizations.create', compact('plans'));
    }

    /**
     * Store a newly created organization in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:organizations',
            'email' => 'required|email|unique:organizations',
            'domain' => 'required|string|max:255|unique:organizations',
            'address' => 'required|string|max:255',
            'status' => 'required|in:active,trial,suspended',
            'plan_id' => 'required|exists:subscription_plans,id',
            'billing_cycle' => 'required|in:monthly,annual',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($request) {
            // Create organization
            $organization = Organization::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'domain' => $request->input('domain'),
                'address' => $request->input('address'),
                'status' => $request->input('status'),
                'created_by' => auth()->id(),
            ]);

            // Create subscription
            Subscription::create([
                'organization_id' => $organization->id,
                'plan_id' => $request->input('plan_id'),
                'status' => $request->input('status') === 'active' ? 'active' : 'pending',
                'billing_cycle' => $request->input('billing_cycle'),
                'amount' => $request->input('amount'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()->route('super-admin.organizations.index')
            ->with('success', 'Organization created successfully.');
    }

    /**
     * Display the specified organization.
     */
    public function show(Organization $organization)
    {
        $organization->load(['subscription.plan', 'users', 'documents', 'letterheads']);
        return view('super-admin.organizations.show', compact('organization'));
    }

    /**
     * Show the form for editing the specified organization.
     */
    public function edit(Organization $organization)
    {
        $plans = SubscriptionPlan::all();
        return view('super-admin.organizations.edit', compact('organization', 'plans'));
    }

    /**
     * Update the specified organization in storage.
     */
    public function update(Request $request, Organization $organization)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:organizations,name,' . $organization->id,
            'email' => 'required|email|unique:organizations,email,' . $organization->id,
            'domain' => 'required|string|max:255|unique:organizations,domain,' . $organization->id,
            'address' => 'required|string|max:255',
            'status' => 'required|in:active,trial,suspended',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $organization->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'domain' => $request->input('domain'),
            'address' => $request->input('address'),
            'status' => $request->input('status'),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('super-admin.organizations.index')
            ->with('success', 'Organization updated successfully.');
    }

    /**
     * Remove the specified organization from storage.
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();

        return redirect()->route('super-admin.organizations.index')
            ->with('success', 'Organization deleted successfully.');
    }

    /**
     * Toggle organization status.
     */
    public function toggleStatus(Organization $organization)
    {
        $organization->update([
            'status' => $organization->status === 'active' ? 'suspended' : 'active',
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Organization status updated successfully.');
    }

    /**
     * Bulk operations on organizations.
     */
    public function bulkOperations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'organization_ids' => 'required|array',
            'organization_ids.*' => 'exists:organizations,id',
            'action' => 'required|in:activate,suspend,delete,change-plan',
            'plan_id' => 'nullable|exists:subscription_plans,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $organizationIds = $request->input('organization_ids');
        $action = $request->input('action');

        DB::transaction(function () use ($organizationIds, $action, $request) {
            $organizations = Organization::whereIn('id', $organizationIds)->get();

            foreach ($organizations as $organization) {
                switch ($action) {
                    case 'activate':
                        $organization->update(['status' => 'active', 'updated_by' => auth()->id()]);
                        if ($organization->subscription) {
                            $organization->subscription->update(['status' => 'active']);
                        }
                        break;

                    case 'suspend':
                        $organization->update(['status' => 'suspended', 'updated_by' => auth()->id()]);
                        if ($organization->subscription) {
                            $organization->subscription->update(['status' => 'suspended']);
                        }
                        break;

                    case 'delete':
                        $organization->delete();
                        break;

                    case 'change-plan':
                        if ($request->filled('plan_id') && $organization->subscription) {
                            $organization->subscription->update([
                                'plan_id' => $request->input('plan_id'),
                                'updated_by' => auth()->id(),
                            ]);
                        }
                        break;
                }
            }
        });

        return redirect()->back()
            ->with('success', 'Bulk operation completed successfully.');
    }

    /**
     * Export organizations data.
     */
    public function export(Request $request)
    {
        $query = Organization::with(['subscription.plan']);

        if ($request->filled('format') && $request->input('format') === 'csv') {
            return $this->exportToCSV($query);
        }

        return $this->exportToExcel($query);
    }

    /**
     * Export to CSV format.
     */
    private function exportToCSV($query)
    {
        $organizations = $query->get();

        $filename = 'organizations_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($organizations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Domain', 'Address', 'Status', 'Plan', 'Amount', 'Billing Cycle', 'Created At']);

            foreach ($organizations as $organization) {
                fputcsv($file, [
                    $organization->id,
                    $organization->name,
                    $organization->email,
                    $organization->domain,
                    $organization->address,
                    $organization->status,
                    $organization->subscription && $organization->subscription->plan ? $organization->subscription->plan->name : 'No Plan',
                    $organization->subscription ? $organization->subscription->amount : 0,
                    $organization->subscription ? $organization->subscription->billing_cycle : 'N/A',
                    $organization->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to Excel format.
     */
    private function exportToExcel($query)
    {
        // This would require the Maatwebsite Excel package
        // For now, we'll return a CSV as fallback
        return $this->exportToCSV($query);
    }

    /**
     * Get dashboard statistics with caching.
     */
    private function getDashboardStats()
    {
        return Cache::remember('organization_dashboard_stats', 300, function () {
            $total = Organization::count();
            $active = Organization::where('status', 'active')->count();
            $trial = Organization::where('status', 'trial')->count();
            $suspended = Organization::where('status', 'suspended')->count();

            // Subscription revenue
            $monthlyRevenue = Subscription::where('status', 'active')
                ->with('plan')
                ->get()
                ->sum(function($subscription) {
                    if ($subscription->plan) {
                        $price = $subscription->plan->price;
                        return $subscription->billing_cycle === 'annual' ? $price / 12 : $price;
                    }
                    return 0;
                });
            $annualRevenue = $monthlyRevenue * 12;

            // Growth calculation (last 30 days)
            $growth = Organization::where('created_at', '>=', now()->subDays(30))->count();

            // System health
            $apiUsage = $this->calculateApiUsage();
            $storageUsed = $this->calculateStorageUsage();
            $activeUsers = User::where('last_login_at', '>=', now()->subDay())->count();

            return [
                'total_organizations' => $total,
                'active_organizations' => $active,
                'trial_organizations' => $trial,
                'suspended_organizations' => $suspended,
                'monthly_revenue' => $monthlyRevenue,
                'annual_revenue' => $annualRevenue,
                'growth_30_days' => $growth,
                'api_usage' => $apiUsage,
                'storage_used' => $storageUsed,
                'active_users' => $activeUsers,
                'system_status' => 'healthy'
            ];
        });
    }

    /**
     * Get analytics data for charts.
     */
    private function getAnalyticsData()
    {
        return Cache::remember('organization_analytics', 600, function () {
            // Organization growth over last 12 months
            $growthData = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $count = Organization::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
                $growthData[] = [
                    'month' => $month->format('M Y'),
                    'count' => $count
                ];
            }

            // Subscription distribution
            $subscriptionDistribution = Subscription::select('plan_id', DB::raw('count(*) as count'))
                ->groupBy('plan_id')
                ->with('plan')
                ->get();

            // Geographic distribution (not available - using address field instead)
            $geographicData = collect();

            return [
                'growth_data' => $growthData,
                'subscription_distribution' => $subscriptionDistribution,
                'geographic_data' => $geographicData
            ];
        });
    }

    /**
     * Get recent organizations.
     */
    private function getRecentOrganizations()
    {
        return Organization::with('subscription.plan')
            ->latest()
            ->limit(10)
            ->get();
    }

    /**
     * Calculate API usage percentage.
     */
    private function calculateApiUsage()
    {
        // This would depend on your API usage tracking implementation
        // For now, return a mock value
        return 85;
    }

    /**
     * Calculate storage usage percentage.
     */
    private function calculateStorageUsage()
    {
        // This would depend on your storage tracking implementation
        // For now, return a mock value
        return 45;
    }

    /**
     * Get organization statistics for dashboard.
     */
    public function statistics()
    {
        $stats = [
            'total' => Organization::count(),
            'active' => Organization::where('status', 'active')->count(),
            'trial' => Organization::where('status', 'trial')->count(),
            'suspended' => Organization::where('status', 'suspended')->count(),
            'revenue' => Subscription::where('status', 'active')->sum('amount'),
            'avg_revenue' => Subscription::where('status', 'active')->avg('amount'),
        ];

        return response()->json($stats);
    }
}
