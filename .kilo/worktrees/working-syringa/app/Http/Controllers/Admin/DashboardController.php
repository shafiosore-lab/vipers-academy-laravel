<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\WebsitePlayer;
use App\Models\Program;
use App\Models\Blog;
use App\Models\User;
use App\Models\Document;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Organization;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
        // Authorization check - only admins and higher can access dashboard
        $user = auth()->user();
        $permissionService = new PermissionService();

        if (!$permissionService->hasRoleOrHigher($user, 'admin')) {
            abort(403, 'Unauthorized access to dashboard');
        }

        // Cache dashboard data for 5 minutes to improve performance
        $cacheKey = 'admin_dashboard_' . auth()->id();
        $cacheTime = 300; // 5 minutes

        // Check if cache tagging is supported (requires Redis/Memcached)
        $useTags = false;
        try {
            $driver = config('cache.default');
            $supportedDrivers = ['redis', 'memcached', 'dynamodb', 'octane'];
            if (in_array($driver, $supportedDrivers)) {
                $useTags = true;
            }
        } catch (\Exception $e) {
            $useTags = false;
        }

        if ($useTags) {
            $data = \Cache::tags(['admin_dashboard'])->remember($cacheKey, $cacheTime, function () {
                return $this->getDashboardData();
            });
        } else {
            // Fallback: use simple cache without tags (works with file driver)
            $data = \Cache::remember($cacheKey, $cacheTime, function () {
                return $this->getDashboardData();
            });
        }

        return view('admin.dashboard.index', $data);
    }

    /**
     * Gather all dashboard data
     */
    private function getDashboardData()
    {
        // Player counts
        $mainPlayersCount = Player::count();
        $websitePlayersCount = WebsitePlayer::count();
        $totalPlayers = $mainPlayersCount + $websitePlayersCount;

        $mainApprovedPlayers = Player::where('registration_status', 'Approved')->count();
        $mainPendingPlayers = Player::where('registration_status', 'Pending')->count();
        $mainTemporaryPlayers = Player::where('approval_type', 'temporary')->count();

        $websiteOrphanedPlayers = WebsitePlayer::whereNull('player_id')->count();
        $websiteLinkedPlayers = $websitePlayersCount - $websiteOrphanedPlayers;

        $newPlayersThisMonth = Player::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $newWebsitePlayersThisMonth = WebsitePlayer::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $totalNewPlayers = $newPlayersThisMonth + $newWebsitePlayersThisMonth;

        $playersLastMonth = Player::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $websitePlayersLastMonth = WebsitePlayer::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $totalPlayersLastMonth = $playersLastMonth + $websitePlayersLastMonth;
        $playerGrowth = $totalPlayersLastMonth > 0 ? round((($totalNewPlayers - $totalPlayersLastMonth) / $totalPlayersLastMonth) * 100, 1) : 0;

        $playersWithContracts = Player::where('has_professional_contract', true)->count();
        $internationalPlayers = Player::where('international_eligible', true)->count();
        $playersNeedingAttention = Player::where('needs_attention', true)->count();

        $excellentAcademic = Player::where('academic_performance', 'Excellent')->count();
        $averageAcademicGPA = Player::whereNotNull('academic_gpa')->avg('academic_gpa');

        $totalGoals = Player::sum('goals_scored') ?: 0;
        $totalAssists = Player::sum('assists') ?: 0;
        $highPerformers = Player::where('performance_rating', '>=', 8.0)->count();
        $totalMatches = Player::sum('matches_played') ?: 0;

        $schools = Player::whereNotNull('school_name')
            ->select('school_name')
            ->selectRaw('COUNT(*) as student_count')
            ->groupBy('school_name')
            ->orderBy('student_count', 'desc')
            ->take(5)
            ->get();

        $developmentStages = Player::select('development_stage')
            ->whereNotNull('development_stage')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('development_stage')
            ->get();

        $recentFollowUps = Player::where('needs_attention', true)
            ->orWhere('last_follow_up', '<', now()->subDays(30))
            ->take(5)
            ->get();

        $topPerformers = Player::whereNotNull('performance_rating')
            ->orderBy('performance_rating', 'desc')
            ->take(5)
            ->get();

        $totalPartners = User::where('user_type', 'partner')->count();
        $activePartners = User::where('user_type', 'partner')->where('approval_status', 'approved')->count();
        $pendingPartners = User::where('user_type', 'partner')->where('approval_status', 'pending')->count();

        $totalPrograms = Program::count();
        $newProgramsThisMonth = Program::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $programsLastMonth = Program::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $programGrowth = $programsLastMonth > 0 ? round((($newProgramsThisMonth - $programsLastMonth) / $programsLastMonth) * 100, 1) : 0;

        $totalNews = Blog::count();
        $newsThisWeek = Blog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $newsLastWeek = Blog::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();
        $newsGrowth = $newsLastWeek > 0 ? round((($newsThisWeek - $newsLastWeek) / $newsLastWeek) * 100, 1) : 0;

        $recentPartners = User::where('user_type', 'partner')->latest()->take(3)->get();
        $recentPlayers = Player::latest()->take(3)->get();

        // Generate AI insights
        $aiInsights = $this->generateAiInsights($totalPlayers, $highPerformers, $playersNeedingAttention);

        // Chart data
        $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $playerRegistrations = [12, 19, 3, 5, 2, 3, 9, 14, 7, 8, 15, 10]; // Sample data
        $programCreations = [2, 4, 1, 3, 1, 2, 5, 8, 3, 4, 6, 3]; // Sample data

        // Stats for revenue and users
        $stats = [
            'total_revenue' => \App\Models\Payment::completed()->sum('amount'),
            'total_users' => User::count(),
            'total_players' => $totalPlayers,
        ];

        // Recent activity for compact analytics
        $recentActivity = [];

        // Get recent players
        foreach (Player::latest()->take(3)->get() as $player) {
            $recentActivity[] = [
                'icon' => '👤',
                'type' => 'Player',
                'description' => $player->first_name . ' ' . $player->last_name . ' registered',
                'date' => $player->created_at->diffForHumans(),
                'status' => $player->registration_status ?? 'new',
            ];
        }

        // Get recent partners
        foreach (User::where('user_type', 'partner')->latest()->take(2)->get() as $partner) {
            $recentActivity[] = [
                'icon' => '🤝',
                'type' => 'Partner',
                'description' => $partner->name . ' joined as partner',
                'date' => $partner->created_at->diffForHumans(),
                'status' => $partner->approval_status ?? 'pending',
            ];
        }

        // Metrics for compact analytics
        $metrics = [
            'total_enrollments' => $totalPlayers,
            'total_revenue' => $stats['total_revenue'],
            'active_programs' => $totalPrograms,
            'attendance_rate' => 85,
            'recent_activity' => $recentActivity,
        ];

        // Document statistics for compact analytics
        $documentStats = $this->getDocumentStats();
        $metrics['document_stats'] = $documentStats;

        // Subscription statistics for system status
        $subscriptionStats = $this->getSubscriptionStats();

        return compact(
            'totalPlayers', 'newPlayersThisMonth', 'playerGrowth', 'playersWithContracts',
            'internationalPlayers', 'playersNeedingAttention', 'excellentAcademic',
            'averageAcademicGPA', 'totalGoals', 'totalAssists', 'highPerformers',
            'totalMatches', 'schools', 'developmentStages', 'recentFollowUps',
            'topPerformers', 'totalPartners', 'activePartners', 'pendingPartners',
            'totalPrograms', 'newProgramsThisMonth', 'programGrowth', 'totalNews',
            'newsThisWeek', 'newsGrowth', 'recentPartners', 'recentPlayers', 'aiInsights',
            'monthLabels', 'playerRegistrations', 'programCreations',
            // WebsitePlayer counts
            'mainPlayersCount', 'websitePlayersCount',
            'mainApprovedPlayers', 'mainPendingPlayers', 'mainTemporaryPlayers',
            'websiteOrphanedPlayers', 'websiteLinkedPlayers',
            'stats',
            'metrics',
            'subscriptionStats'
        );
    }

    /**
     * Get subscription statistics for system status - OPTIMIZED
     */
    private function getSubscriptionStats()
    {
        $allSubscriptions = Subscription::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $trialingSubscriptions = Subscription::where('status', 'trialing')->count();
        $canceledSubscriptions = Subscription::where('status', 'canceled')->count();
        $pastDueSubscriptions = Subscription::where('status', 'past_due')->count();

        $totalOrganizations = Organization::count();
        $activeOrganizations = Organization::where('status', 'active')->count();
        $trialOrganizations = Organization::where('status', 'trial')->count();
        $suspendedOrganizations = Organization::where('status', 'suspended')->count();

        $totalPlans = SubscriptionPlan::count();
        $activePlans = SubscriptionPlan::where('is_active', true)->count();

        $subscriptionsEndingSoon = Subscription::where('status', 'active')
            ->whereNotNull('ends_at')
            ->whereBetween('ends_at', [now(), now()->addDays(7)])
            ->count();

        $trialsEndingSoon = Subscription::where('status', 'trialing')
            ->whereNotNull('trial_ends_at')
            ->whereBetween('trial_ends_at', [now(), now()->addDays(3)])
            ->count();

        $mrr = Subscription::where('status', 'active')
            ->with('plan')
            ->get()
            ->sum(function ($sub) {
                if ($sub->plan) {
                    return match($sub->plan->billing_cycle) {
                        'yearly' => $sub->plan->price / 12,
                        'monthly' => $sub->plan->price,
                        default => $sub->plan->price,
                    };
                }
                return 0;
            });

        $plans = SubscriptionPlan::active()->get();
        $planPermissions = [];

        foreach ($plans as $plan) {
            $planPermissions[$plan->slug] = [
                'name' => $plan->name,
                'modules' => $plan->getAvailableModules(),
                'module_count' => count($plan->getAvailableModules()),
            ];
        }

        return [
            'total_subscriptions' => $allSubscriptions,
            'active_subscriptions' => $activeSubscriptions,
            'trialing_subscriptions' => $trialingSubscriptions,
            'canceled_subscriptions' => $canceledSubscriptions,
            'past_due_subscriptions' => $pastDueSubscriptions,
            'total_organizations' => $totalOrganizations,
            'active_organizations' => $activeOrganizations,
            'trial_organizations' => $trialOrganizations,
            'suspended_organizations' => $suspendedOrganizations,
            'total_plans' => $totalPlans,
            'active_plans' => $activePlans,
            'subscriptions_ending_soon' => $subscriptionsEndingSoon,
            'trials_ending_soon' => $trialsEndingSoon,
            'mrr' => $mrr,
            'plan_permissions' => $planPermissions,
        ];
    }

    public function performanceOverview()
    {
        // Authorization check - only admins and higher can access performance overview
        $user = auth()->user();
        $permissionService = new PermissionService();

        if (!$permissionService->hasRoleOrHigher($user, 'admin')) {
            abort(403, 'Unauthorized access to performance overview');
        }

        // ===== PLAYER METRICS =====
        // Use only Player table as it has complete data structure
        $totalPlayers = Player::count();
        $activePlayers = Player::where('registration_status', 'Active')->count();
        $inactivePlayers = Player::where('registration_status', 'Inactive')->count();
        $pendingPlayers = Player::where('registration_status', 'Pending')->count();

        // Age group distribution
        $youthPlayers = Player::where('age_group', 'like', 'U-%')->count();
        $seniorPlayers = Player::where('age_group', 'Senior')->count();

        // Gender distribution
        $malePlayers = Player::where('gender', 'Male')->count();
        $femalePlayers = Player::where('gender', 'Female')->count();

        // ===== PERFORMANCE METRICS =====
        $totalGoals = Player::sum('goals_scored') ?: 0;
        $totalAssists = Player::sum('assists') ?: 0;
        $totalMatches = Player::sum('matches_played') ?: 0;

        // Performance ratings
        $highPerformers = Player::where('performance_rating', '>=', 8.0)->count();
        $averageRating = Player::whereNotNull('performance_rating')->avg('performance_rating');
        $topPerformers = Player::whereNotNull('performance_rating')
            ->orderBy('performance_rating', 'desc')
            ->take(5)
            ->get();

        // ===== ACADEMIC METRICS =====
        $excellentAcademic = Player::where('academic_performance', 'Excellent')->count();
        $goodAcademic = Player::where('academic_performance', 'Good')->count();
        $averageAcademic = Player::where('academic_performance', 'Average')->count();
        $poorAcademic = Player::where('academic_performance', 'Poor')->count();
        $averageAcademicGPA = Player::whereNotNull('academic_gpa')->avg('academic_gpa');

        // ===== CONTRACT & CAREER METRICS =====
        $playersWithContracts = Player::where('has_professional_contract', true)->count();
        $nationalContracts = Player::where('contract_type', 'National')->count();
        $internationalContracts = Player::where('contract_type', 'International')->count();

        // ===== INTERNATIONAL & ELIGIBILITY =====
        $internationalPlayers = Player::where('international_eligible', true)->count();
        $fifaRegistered = Player::whereNotNull('fifa_registration_number')->count();

        // ===== ATTENTION & FOLLOW-UP =====
        $playersNeedingAttention = Player::where('needs_attention', true)->count();
        $overdueFollowUps = Player::where('last_follow_up', '<', now()->subDays(30))->count();

        // ===== PARTNERSHIP METRICS =====
        $totalPartners = User::where('user_type', 'partner')->count();
        $activePartners = User::where('user_type', 'partner')->where('approval_status', 'approved')->count();
        $pendingPartners = User::where('user_type', 'partner')->where('approval_status', 'pending')->count();
        $inactivePartners = User::where('user_type', 'partner')->where('approval_status', 'rejected')->count();

        // ===== PROGRAM METRICS =====
        $totalPrograms = \App\Models\Program::count();
        $activePrograms = \App\Models\Program::where('status', 'active')->count();
        $upcomingPrograms = \App\Models\Program::where('start_date', '>', now())->count();

        // ===== FINANCIAL METRICS =====
        $totalRevenue = \App\Models\Payment::completed()->sum('amount');
        $monthlyRevenue = \App\Models\Payment::completed()
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');
        $pendingPayments = \App\Models\Payment::pending()->sum('amount');

        // ===== NEWS & CONTENT METRICS =====
        $totalNews = \App\Models\Blog::count();
        $publishedNews = \App\Models\Blog::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();

        // ===== SYSTEM HEALTH =====
        $totalOrders = \App\Models\Order::count();
        $pendingOrders = \App\Models\Order::where('order_status', 'pending')->count();
        $completedOrders = \App\Models\Order::where('order_status', 'delivered')->count();

        return view('admin.performance-overview', compact(
            // Player Metrics
            'totalPlayers', 'activePlayers', 'inactivePlayers', 'pendingPlayers',
            'youthPlayers', 'seniorPlayers', 'malePlayers', 'femalePlayers',

            // Performance Metrics
            'totalGoals', 'totalAssists', 'totalMatches',
            'highPerformers', 'averageRating', 'topPerformers',

            // Academic Metrics
            'excellentAcademic', 'goodAcademic', 'averageAcademic', 'poorAcademic', 'averageAcademicGPA',

            // Contract & Career
            'playersWithContracts', 'nationalContracts', 'internationalContracts',

            // International & Eligibility
            'internationalPlayers', 'fifaRegistered',

            // Attention & Follow-up
            'playersNeedingAttention', 'overdueFollowUps',

            // Partnership Metrics
            'totalPartners', 'activePartners', 'pendingPartners', 'inactivePartners',

            // Program Metrics
            'totalPrograms', 'activePrograms', 'upcomingPrograms',

            // Financial Metrics
            'totalRevenue', 'monthlyRevenue', 'pendingPayments',

            // Content Metrics
            'totalNews', 'publishedNews',

            // System Health
            'totalOrders', 'pendingOrders', 'completedOrders'
        ));
    }

    public function complianceReport()
    {
        // Authorization check - only admins and higher can access compliance report
        $user = auth()->user();
        $permissionService = new PermissionService();

        if (!$permissionService->hasRoleOrHigher($user, 'admin')) {
            abort(403, 'Unauthorized access to compliance report');
        }

        // Get compliance data
        $totalPlayers = Player::count();
        $fifaRegistered = Player::whereNotNull('fifa_registration_number')->count();
        $safeguardingCompliant = Player::where('safeguarding_policy_acknowledged', true)->count();
        $medicalCertificates = Player::whereNotNull('medical_certificate')->count();
        $guardianConsents = Player::whereNotNull('guardian_consent_form')->count();

        // Age group distribution
        $youthPlayers = Player::where('age_group', 'like', 'U-%')->count();
        $seniorPlayers = Player::where('age_group', 'Senior')->count();

        // Recent compliance activities
        $recentRegistrations = Player::latest()->take(10)->get();

        // Generate PDF
        $pdf = Pdf::loadView('admin.compliance.report', compact(
            'totalPlayers',
            'fifaRegistered',
            'safeguardingCompliant',
            'medicalCertificates',
            'guardianConsents',
            'youthPlayers',
            'seniorPlayers',
            'recentRegistrations'
        ));

        $filename = 'fifa-compliance-report-' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    private function generateAiInsights($totalPlayers, $highPerformers, $playersNeedingAttention)
    {
        // Simulate AI-generated insights
        $insights = [];

        // Performance prediction
        if ($highPerformers > 0) {
            $insights[] = [
                'type' => 'success',
                'icon' => 'fas fa-brain',
                'title' => 'Performance Prediction',
                'message' => "AI predicts {$highPerformers} players have potential for professional contracts within 2 years based on current performance metrics."
            ];
        }

        // Recruitment recommendation
        if ($totalPlayers < 50) {
            $insights[] = [
                'type' => 'info',
                'icon' => 'fas fa-users-cog',
                'title' => 'Recruitment Strategy',
                'message' => 'AI recommends increasing recruitment efforts. Current academy size is below optimal capacity for sustainable development.'
            ];
        }

        // Attention alerts
        if ($playersNeedingAttention > 5) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'fas fa-exclamation-triangle',
                'title' => 'Player Development Alert',
                'message' => "{$playersNeedingAttention} players require immediate attention. AI suggests prioritizing follow-up sessions and performance reviews."
            ];
        }

        // Academic correlation
        $insights[] = [
            'type' => 'primary',
            'icon' => 'fas fa-chart-line',
            'title' => 'Academic-Performance Correlation',
            'message' => 'AI analysis shows strong correlation between academic performance and on-field success. Consider integrated development programs.'
        ];

        // Default insights if none generated
        if (empty($insights)) {
            $insights[] = [
                'type' => 'secondary',
                'icon' => 'fas fa-robot',
                'title' => 'AI Monitoring Active',
                'message' => 'Academy performance is stable. AI continues to monitor player development and provide recommendations.'
            ];
        }

        return $insights;
    }

    /**
     * Get document statistics for compact analytics
     */
    private function getDocumentStats()
    {
        $totalDocuments = Document::count();
        $activeDocuments = Document::where('is_active', true)->count();

        // Document categories breakdown
        $categoryBreakdown = Document::select('category')
            ->selectRaw('COUNT(*) as count')
            ->whereNotNull('category')
            ->groupBy('category')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category,
                    'count' => $item->count,
                    'display_name' => match($item->category) {
                        'codes_of_conduct' => 'Codes of Conduct',
                        'safety_protection' => 'Safety & Protection',
                        'academy_policies' => 'Academy Policies',
                        'contracts_agreements' => 'Contracts & Agreements',
                        'academy_information' => 'Academy Information',
                        'administrative' => 'Administrative',
                        default => ucfirst(str_replace('_', ' ', $item->category))
                    }
                ];
            });

        // Recently uploaded documents
        $recentDocuments = Document::latest()
            ->take(5)
            ->get()
            ->map(function ($doc) {
                return [
                    'icon' => $doc->isPdf() ? '📄' : '📎',
                    'type' => 'Document',
                    'title' => $doc->title,
                    'category' => $doc->category,
                    'date' => $doc->created_at->diffForHumans(),
                    'status' => $doc->is_active ? 'Active' : 'Inactive',
                ];
            });

        // Documents this month
        $documentsThisMonth = Document::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();

        return [
            'total_documents' => $totalDocuments,
            'active_documents' => $activeDocuments,
            'documents_this_month' => $documentsThisMonth,
            'category_breakdown' => $categoryBreakdown,
            'recent_documents' => $recentDocuments,
        ];
    }
}
