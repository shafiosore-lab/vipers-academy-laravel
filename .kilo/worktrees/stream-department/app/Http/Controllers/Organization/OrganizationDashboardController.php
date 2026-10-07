<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Program;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Payment;
use App\Models\Attendance;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:org-admin']);
    }

    public function index()
    {
        $user = Auth::user();
        $organizationId = $user->organization_id;

        if (!$organizationId) {
            return redirect()->route('home')->with('error', 'No organization assigned to your account.');
        }

        // Get organization name
        $organizationName = $user->organization->name ?? 'Your Organization';

        // Player stats - filtered by organization
        $totalPlayers = Player::where('organization_id', $organizationId)->count();
        $activePlayers = Player::where('organization_id', $organizationId)
            ->where('registration_status', 'Active')
            ->count();
        $pendingPlayers = Player::where('organization_id', $organizationId)
            ->where('registration_status', 'Pending')
            ->count();

        // New players this month
        $newPlayersThisMonth = Player::where('organization_id', $organizationId)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $playersLastMonth = Player::where('organization_id', $organizationId)
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();

        $playerGrowth = $playersLastMonth > 0
            ? round((($newPlayersThisMonth - $playersLastMonth) / $playersLastMonth) * 100, 1)
            : 0;

        // Program stats
        $totalPrograms = Program::where('organization_id', $organizationId)->count();
        $activePrograms = Program::where('organization_id', $organizationId)
            ->where('status', 'active')
            ->count();

        // Enrollment stats
        $totalEnrollments = Enrollment::whereHas('program', function($q) use ($organizationId) {
            $q->where('organization_id', $organizationId);
        })->count();

        $enrollmentsThisWeek = Enrollment::whereHas('program', function($q) use ($organizationId) {
            $q->where('organization_id', $organizationId);
        })->whereDate('created_at', '>=', now()->startOfWeek())->count();

        // Staff count
        $totalStaff = User::where('organization_id', $organizationId)
            ->whereNotNull('organization_id')
            ->count();

        // Payment stats
        $totalRevenue = Payment::where('organization_id', $organizationId)
            ->where('status', 'completed')
            ->sum('amount');

        $monthlyRevenue = Payment::where('organization_id', $organizationId)
            ->where('status', 'completed')
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');

        $pendingPayments = Payment::where('organization_id', $organizationId)
            ->where('status', 'pending')
            ->sum('amount');

        // Attendance stats
        $totalAttendance = Attendance::whereHas('player', function($q) use ($organizationId) {
            $q->where('organization_id', $organizationId);
        })->count();

        $presentToday = Attendance::whereHas('player', function($q) use ($organizationId) {
            $q->where('organization_id', $organizationId);
        })->whereDate('created_at', today())
          ->where('status', 'present')
          ->count();

        // Recent players
        $recentPlayers = Player::where('organization_id', $organizationId)
            ->latest()
            ->take(5)
            ->get();

        // Recent enrollments
        $recentEnrollments = Enrollment::whereHas('program', function($q) use ($organizationId) {
            $q->where('organization_id', $organizationId);
        })
        ->with(['player', 'program'])
        ->latest()
        ->take(5)
        ->get();

        // Upcoming sessions
        $upcomingSessions = \App\Models\TrainingSession::where('organization_id', $organizationId)
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->take(5)
            ->get();

        // Performance metrics
        $averageAttendance = $totalAttendance > 0
            ? round(($presentToday / max($totalAttendance, 1)) * 100, 1)
            : 0;

        // Chart data
        $chartData = $this->getChartData($organizationId);

        // Compact analytics metrics
        $recentActivity = [];

        // Get recent players
        foreach (Player::where('organization_id', $organizationId)->latest()->take(3)->get() as $player) {
            $recentActivity[] = [
                'icon' => '👤',
                'type' => 'Player',
                'description' => $player->first_name . ' ' . $player->last_name . ' registered',
                'date' => $player->created_at->diffForHumans(),
                'status' => $player->registration_status ?? 'new',
            ];
        }

        // Get recent enrollments
        foreach (Enrollment::whereHas('program', function($q) use ($organizationId) {
            $q->where('organization_id', $organizationId);
        })->latest()->take(2)->get() as $enrollment) {
            $recentActivity[] = [
                'icon' => '📝',
                'type' => 'Enrollment',
                'description' => ($enrollment->player->first_name ?? 'Unknown') . ' enrolled in ' . ($enrollment->program->name ?? 'program'),
                'date' => $enrollment->created_at->diffForHumans(),
                'status' => 'active',
            ];
        }

        $metrics = [
            'total_enrollments' => $totalEnrollments,
            'total_revenue' => $totalRevenue,
            'active_programs' => $activePrograms,
            'attendance_rate' => $averageAttendance,
            'recent_activity' => $recentActivity,
        ];

        // Document statistics for compact analytics
        $documentStats = $this->getDocumentStats($organizationId);
        $metrics['document_stats'] = $documentStats;

        // Generate AI insights
        $aiInsights = $this->generateInsights(
            $totalPlayers,
            $activePlayers,
            $pendingPlayers,
            $averageAttendance
        );

        return view('organization.dashboard', compact(
            'organizationName',
            'totalPlayers',
            'activePlayers',
            'pendingPlayers',
            'newPlayersThisMonth',
            'playerGrowth',
            'totalPrograms',
            'activePrograms',
            'totalEnrollments',
            'enrollmentsThisWeek',
            'totalStaff',
            'totalRevenue',
            'monthlyRevenue',
            'pendingPayments',
            'totalAttendance',
            'presentToday',
            'averageAttendance',
            'recentPlayers',
            'recentEnrollments',
            'upcomingSessions',
            'aiInsights',
            'chartData',
            'metrics'
        ));
    }

    /**
     * Get chart data for dashboard visualizations.
     */
    private function getChartData($organizationId)
    {
        // Programs by category (pie chart)
        $programCategories = Program::where('organization_id', $organizationId)
            ->select('category')
            ->whereNotNull('category')
            ->get()
            ->groupBy('category')
            ->map(function ($programs) {
                return $programs->count();
            });

        // Fees by program (bar chart)
        $programFees = Program::where('organization_id', $organizationId)
            ->select('title', 'regular_fee', 'mumias_fee')
            ->whereNotNull('regular_fee')
            ->limit(10)
            ->get();

        // Monthly enrollments (line chart) - last 6 months
        $monthlyEnrollments = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyEnrollments[] = [
                'month' => $month->format('M'),
                'count' => Player::where('organization_id', $organizationId)
                    ->whereBetween('created_at', [
                        $month->copy()->startOfMonth(),
                        $month->copy()->endOfMonth()
                    ])->count()
            ];
        }

        // Monthly revenue (line chart)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyRevenue[] = [
                'month' => $month->format('M'),
                'amount' => Payment::where('organization_id', $organizationId)
                    ->where('status', 'completed')
                    ->whereBetween('paid_at', [
                        $month->copy()->startOfMonth(),
                        $month->copy()->endOfMonth()
                    ])->sum('amount')
            ];
        }

        // Attendance by month
        $monthlyAttendance = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $present = Attendance::whereHas('player', function($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            })
            ->whereBetween('created_at', [
                $month->copy()->startOfMonth(),
                $month->copy()->endOfMonth()
            ])
            ->where('status', 'present')
            ->count();

            $total = Attendance::whereHas('player', function($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            })
            ->whereBetween('created_at', [
                $month->copy()->startOfMonth(),
                $month->copy()->endOfMonth()
            ])
            ->count();

            $monthlyAttendance[] = [
                'month' => $month->format('M'),
                'rate' => $total > 0 ? round(($present / $total) * 100, 1) : 0
            ];
        }

        return [
            'program_categories' => $programCategories,
            'program_fees' => $programFees,
            'monthly_enrollments' => $monthlyEnrollments,
            'monthly_revenue' => $monthlyRevenue,
            'monthly_attendance' => $monthlyAttendance,
        ];
    }

    private function generateInsights($totalPlayers, $activePlayers, $pendingPlayers, $averageAttendance)
    {
        $insights = [];

        // Player growth insight
        if ($totalPlayers > 0) {
            $insights[] = [
                'type' => 'success',
                'icon' => 'fas fa-users',
                'title' => 'Player Enrollment',
                'message' => "Your organization has {$totalPlayers} players. {$activePlayers} are active."
            ];
        }

        // Pending registrations
        if ($pendingPlayers > 0) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'fas fa-user-clock',
                'title' => 'Pending Approvals',
                'message' => "{$pendingPlayers} player registrations are awaiting approval."
            ];
        }

        // Attendance insight
        if ($averageAttendance >= 80) {
            $insights[] = [
                'type' => 'success',
                'icon' => 'fas fa-check-circle',
                'title' => 'Attendance Rate',
                'message' => "Excellent! Your attendance rate is {$averageAttendance}%."
            ];
        } elseif ($averageAttendance >= 60) {
            $insights[] = [
                'type' => 'info',
                'icon' => 'fas fa-info-circle',
                'title' => 'Attendance Rate',
                'message' => "Current attendance rate is {$averageAttendance}%. Consider follow-ups."
            ];
        }

        // Default insight
        if (empty($insights)) {
            $insights[] = [
                'type' => 'secondary',
                'icon' => 'fas fa-chart-line',
                'title' => 'Getting Started',
                'message' => 'Start by adding players and creating training programs.'
            ];
        }

        return $insights;
    }

    /**
     * Get document statistics for compact analytics
     */
    private function getDocumentStats($organizationId)
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
