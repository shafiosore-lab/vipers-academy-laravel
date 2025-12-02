<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Program;
use App\Models\News;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
        // Player insights data
        $totalPlayers = Player::count();
        $newPlayersThisMonth = Player::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $playersLastMonth = Player::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $playerGrowth = $playersLastMonth > 0 ? round((($newPlayersThisMonth - $playersLastMonth) / $playersLastMonth) * 100, 1) : 0;
        $playersWithContracts = Player::where('has_professional_contract', true)->count();
        $internationalPlayers = Player::where('international_eligible', true)->count();
        $playersNeedingAttention = Player::where('needs_attention', true)->count();

        // Academic insights
        $excellentAcademic = Player::where('academic_performance', 'Excellent')->count();
        $averageAcademicGPA = Player::whereNotNull('academic_gpa')->avg('academic_gpa');

        // Performance insights
        $totalGoals = Player::sum('goals_scored');
        $totalAssists = Player::sum('assists');
        $highPerformers = Player::where('performance_rating', '>=', 8.0)->count();
        $totalMatches = Player::sum('matches_played');

        // School distribution
        $schools = Player::whereNotNull('school_name')
            ->select('school_name')
            ->selectRaw('COUNT(*) as student_count')
            ->groupBy('school_name')
            ->orderBy('student_count', 'desc')
            ->take(5)
            ->get();

        // Development stages
        $developmentStages = Player::select('development_stage')
            ->whereNotNull('development_stage')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('development_stage')
            ->get();

        // Recent follow-ups needed
        $recentFollowUps = Player::where('needs_attention', true)
            ->orWhere('last_follow_up', '<', now()->subDays(30))
            ->take(5)
            ->get();

        // Top performers
        $topPerformers = Player::whereNotNull('performance_rating')
            ->orderBy('performance_rating', 'desc')
            ->take(5)
            ->get();

        // Partner insights data
        $totalPartners = User::where('user_type', 'partner')->count();
        $activePartners = User::where('user_type', 'partner')->where('status', 'active')->count();
        $pendingPartners = User::where('user_type', 'partner')->where('status', 'pending')->count();

        // Additional metrics for dashboard
        $totalPrograms = Program::count();
        $newProgramsThisMonth = Program::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $programsLastMonth = Program::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $programGrowth = $programsLastMonth > 0 ? round((($newProgramsThisMonth - $programsLastMonth) / $programsLastMonth) * 100, 1) : 0;

        $totalNews = News::count();
        $newsThisWeek = News::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $newsLastWeek = News::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();
        $newsGrowth = $newsLastWeek > 0 ? round((($newsThisWeek - $newsLastWeek) / $newsLastWeek) * 100, 1) : 0;

        // Recent partner registrations
        $recentPartners = User::where('user_type', 'partner')
            ->latest()
            ->take(3)
            ->get();

        // Recent player registrations (for activity feed)
        $recentPlayers = Player::latest()->take(3)->get();

        // AI Insights
        $aiInsights = $this->generateAiInsights($totalPlayers, $highPerformers, $playersNeedingAttention);

        return view('admin.dashboard', compact(
            'totalPlayers',
            'newPlayersThisMonth',
            'playerGrowth',
            'playersWithContracts',
            'internationalPlayers',
            'playersNeedingAttention',
            'excellentAcademic',
            'averageAcademicGPA',
            'totalGoals',
            'totalAssists',
            'highPerformers',
            'totalMatches',
            'schools',
            'developmentStages',
            'recentFollowUps',
            'topPerformers',
            'totalPartners',
            'activePartners',
            'pendingPartners',
            'totalPrograms',
            'newProgramsThisMonth',
            'programGrowth',
            'totalNews',
            'newsThisWeek',
            'newsGrowth',
            'recentPartners',
            'recentPlayers',
            'aiInsights'
        ));
    }

    public function performanceOverview()
    {
        // ===== PLAYER METRICS =====
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
        $totalGoals = Player::sum('goals_scored');
        $totalAssists = Player::sum('assists');
        $totalMatches = Player::sum('matches_played');

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
        $activePartners = User::where('user_type', 'partner')->where('status', 'active')->count();
        $pendingPartners = User::where('user_type', 'partner')->where('status', 'pending')->count();
        $inactivePartners = User::where('user_type', 'partner')->where('status', 'inactive')->count();

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
        $totalNews = \App\Models\News::count();
        $publishedNews = \App\Models\News::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();
        $totalGallery = \App\Models\Gallery::count();

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
            'totalNews', 'publishedNews', 'totalGallery',

            // System Health
            'totalOrders', 'pendingOrders', 'completedOrders'
        ));
    }

    public function generateComplianceReport()
    {
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
        $pdf = Pdf::loadView('admin.compliance-report', compact(
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
}
