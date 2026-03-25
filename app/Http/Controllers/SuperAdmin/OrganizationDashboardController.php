<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrganizationDashboardController extends Controller
{
    /**
     * Display the organization management dashboard.
     */
    public function index()
    {
        $stats = $this->getDashboardStats();
        $analytics = $this->getAnalyticsData();
        $recentOrganizations = $this->getRecentOrganizations();

        return view('super-admin.organizations.dashboard', compact('stats', 'analytics', 'recentOrganizations'));
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
                ->sum(DB::raw('amount / billing_cycle'));
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

            // Geographic distribution (assuming organizations have country field)
            $geographicData = Organization::select('country', DB::raw('count(*) as count'))
                ->whereNotNull('country')
                ->groupBy('country')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();

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
     * Get dashboard data for API.
     */
    public function apiData()
    {
        return response()->json([
            'stats' => $this->getDashboardStats(),
            'analytics' => $this->getAnalyticsData(),
            'recent_organizations' => $this->getRecentOrganizations()
        ]);
    }
}
