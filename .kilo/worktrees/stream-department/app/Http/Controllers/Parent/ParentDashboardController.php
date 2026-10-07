<?php

namespace App\Http\Controllers\Parent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Guardian;
use App\Models\Payment;
use App\Models\MonthlyBilling;
use App\Models\Attendance;
use App\Models\GameStatistic;
use App\Models\TrainingSession;
use App\Models\Blog;
use App\Models\AiInsight;
use App\Models\UserDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ParentDashboardController extends Controller
{
    /**
     * Check if user has parent access
     *
     * @return \Illuminate\Http\RedirectResponse|array
     */
    private function checkParentAccess()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')->with('error', 'Please log in to access this page.');
        }

        // Check if user has parent role
        if (!$user->hasRole('parent')) {
            return redirect('/')->with('error', 'Access denied. Parent account required.');
        }

        // Find the guardian record linked to this user
        $guardian = null;

        // Option 1: User might be linked via guardian_id on Player
        $playersAsGuardian = Player::where('parent_phone', $user->phone)
            ->orWhere('parent_guardian_name', 'like', '%' . $user->name . '%')
            ->get();

        // Option 2: User might have a guardian record
        if ($user->phone) {
            $guardian = Guardian::where('phone', $user->phone)->first();
        }

        return [
            'user' => $user,
            'guardian' => $guardian,
            'players' => $playersAsGuardian,
        ];
    }

    /**
     * Dashboard - Overview page with all key metrics
     */
    public function dashboard()
    {
        $accessCheck = $this->checkParentAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $user = $accessCheck['user'];
        $players = $accessCheck['players'];
        $guardian = $accessCheck['guardian'];

        // If no players found, show empty state
        $noPlayers = $players->isEmpty();

        if ($players->isEmpty()) {
            return view('parent.dashboard', [
                'user' => $user,
                'players' => collect(),
                'selectedPlayer' => null,
                'quickStats' => [],
                'recentActivity' => collect(),
                'financialSummary' => [],
                'attendanceSummary' => [],
                'upcomingSessions' => [],
                'recentMatches' => [],
                'aiInsights' => collect(),
                'noPlayers' => true,
            ]);
        }

        // Get the first player (primary child) for overview
        $selectedPlayer = $players->first();

        // Get quick stats for selected player
        $quickStats = $this->getQuickStats($selectedPlayer);

        // Get recent activity (game statistics)
        $recentActivity = GameStatistic::where('player_id', $selectedPlayer->id)
            ->latest()
            ->take(5)
            ->get();

        // Get financial summary
        $financialSummary = $this->getFinancialSummary($selectedPlayer);

        // Get attendance summary
        $attendanceSummary = $this->getAttendanceSummary($selectedPlayer);

        // Get upcoming training sessions
        $upcomingSessions = $this->getUpcomingSessions($selectedPlayer);

        // Get recent matches
        $recentMatches = $this->getRecentMatches($selectedPlayer);

        // Get AI insights for the player
        $aiInsights = $this->getAiInsights($selectedPlayer);

        return view('parent.dashboard', compact(
            'user',
            'players',
            'selectedPlayer',
            'quickStats',
            'recentActivity',
            'financialSummary',
            'attendanceSummary',
            'upcomingSessions',
            'recentMatches',
            'aiInsights',
            'noPlayers'
        ));
    }

    /**
     * Player Profile - Detailed view of player's profile with disciplinary records
     */
    public function playerProfile(Request $request)
    {
        $accessCheck = $this->checkParentAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $user = $accessCheck['user'];
        $players = $accessCheck['players'];
        $linkedPlayers = $players;
        $playerId = $request->get('player_id');

        // Find the selected player
        $selectedPlayer = null;
        if ($playerId) {
            $selectedPlayer = $players->where('id', $playerId)->first();
        }

        if (!$selectedPlayer) {
            $selectedPlayer = $players->first();
        }

        if (!$selectedPlayer) {
            return redirect()->route('parent.dashboard')
                ->with('error', 'No player profile found.');
        }

        // Get player documents
        $playerDocuments = UserDocument::where('user_id', $selectedPlayer->user_id ?? $selectedPlayer->id)
            ->get();

        // Get program info
        $program = $selectedPlayer->program;

        // Get billing history
        $billingHistory = MonthlyBilling::where('player_id', $selectedPlayer->id)
            ->orderBy('month_year', 'desc')
            ->take(12)
            ->get();

        return view('parent.profile', compact(
            'user',
            'players',
            'linkedPlayers',
            'selectedPlayer',
            'playerDocuments',
            'program',
            'billingHistory'
        ));
    }

    /**
     * Financial Tracking - View all payments, billing, outstanding amounts
     */
    public function finances(Request $request)
    {
        $accessCheck = $this->checkParentAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $user = $accessCheck['user'];
        $players = $accessCheck['players'];
        $playerId = $request->get('player_id');

        // Find the selected player
        $selectedPlayer = null;
        if ($playerId) {
            $selectedPlayer = $players->where('id', $playerId)->first();
        }

        if (!$selectedPlayer) {
            $selectedPlayer = $players->first();
        }

        if (!$selectedPlayer) {
            return redirect()->route('parent.dashboard')
                ->with('error', 'No player found.');
        }

        // Get all payments for this player
        $payments = Payment::where('player_id', $selectedPlayer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get billing history
        $billings = MonthlyBilling::where('player_id', $selectedPlayer->id)
            ->orderBy('month_year', 'desc')
            ->take(12)
            ->get();

        // Calculate totals
        $totalPaid = Payment::where('player_id', $selectedPlayer->id)
            ->where('payment_status', 'completed')
            ->sum('amount');

        $pendingPayments = Payment::where('player_id', $selectedPlayer->id)
            ->where('payment_status', 'pending')
            ->get();

        $totalPending = $pendingPayments->sum('amount');

        $overduePayments = Payment::where('player_id', $selectedPlayer->id)
            ->where('payment_status', 'pending')
            ->where('due_date', '<', now())
            ->get();

        $totalOverdue = $overduePayments->sum('amount');

        // Get current outstanding balance
        $latestBilling = MonthlyBilling::where('player_id', $selectedPlayer->id)
            ->latest('month_year')
            ->first();

        $currentBalance = $latestBilling ? $latestBilling->closing_balance : 0;

        // Get monthly fee
        $monthlyFee = $selectedPlayer->getMonthlyFee();

        return view('parent.finances', compact(
            'user',
            'players',
            'selectedPlayer',
            'payments',
            'billings',
            'totalPaid',
            'totalPending',
            'totalOverdue',
            'currentBalance',
            'monthlyFee',
            'pendingPayments'
        ));
    }

    /**
     * Training & Attendance - View training sessions with timestamps
     */
    public function training(Request $request)
    {
        $accessCheck = $this->checkParentAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $user = $accessCheck['user'];
        $players = $accessCheck['players'];
        $playerId = $request->get('player_id');

        // Find the selected player
        $selectedPlayer = null;
        if ($playerId) {
            $selectedPlayer = $players->where('id', $playerId)->first();
        }

        if (!$selectedPlayer) {
            $selectedPlayer = $players->first();
        }

        if (!$selectedPlayer) {
            return redirect()->route('parent.dashboard')
                ->with('error', 'No player found.');
        }

        // Get attendance records with session details
        $attendances = Attendance::with(['session'])
            ->where('player_id', $selectedPlayer->id)
            ->orderBy('session_date', 'desc')
            ->paginate(20);

        // Get monthly statistics
        $monthlyStats = $this->getMonthlyAttendanceStats($selectedPlayer);

        // Get overall attendance rate
        $totalSessions = Attendance::where('player_id', $selectedPlayer->id)->count();
        $attendedSessions = Attendance::where('player_id', $selectedPlayer->id)
            ->whereNotNull('check_in_time')
            ->count();

        $attendanceRate = $totalSessions > 0 ? round(($attendedSessions / $totalSessions) * 100, 1) : 0;

        // Get total training minutes
        $totalMinutes = Attendance::where('player_id', $selectedPlayer->id)
            ->sum('total_duration_minutes');

        return view('parent.training', compact(
            'user',
            'players',
            'selectedPlayer',
            'attendances',
            'monthlyStats',
            'attendanceRate',
            'totalSessions',
            'totalMinutes'
        ));
    }

    /**
     * Match Participation - View match records with playing minutes
     */
    public function matches(Request $request)
    {
        $accessCheck = $this->checkParentAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $user = $accessCheck['user'];
        $players = $accessCheck['players'];
        $playerId = $request->get('player_id');

        // Find the selected player
        $selectedPlayer = null;
        if ($playerId) {
            $selectedPlayer = $players->where('id', $playerId)->first();
        }

        if (!$selectedPlayer) {
            $selectedPlayer = $players->first();
        }

        if (!$selectedPlayer) {
            return redirect()->route('parent.dashboard')
                ->with('error', 'No player found.');
        }

        // Get game statistics (matches)
        $matches = GameStatistic::with(['match'])
            ->where('player_id', $selectedPlayer->id)
            ->orderBy('match_date', 'desc')
            ->paginate(15);

        // Calculate totals
        $totalAppearances = $matches->total();
        $totalMinutes = $matches->sum('minutes_played');
        $totalGoals = $matches->sum('goals_scored');
        $totalAssists = $matches->sum('assists');
        $totalYellowCards = $matches->sum('yellow_cards');
        $totalRedCards = $matches->sum('red_cards');

        // Get match statistics by month
        $monthlyMatchStats = $this->getMonthlyMatchStats($selectedPlayer);

        return view('parent.matches', compact(
            'user',
            'players',
            'selectedPlayer',
            'matches',
            'totalAppearances',
            'totalMinutes',
            'totalGoals',
            'totalAssists',
            'totalYellowCards',
            'totalRedCards',
            'monthlyMatchStats'
        ));
    }

    /**
     * Media Gallery - View YouTube videos and photos
     */
    public function media()
    {
        $accessCheck = $this->checkParentAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $user = $accessCheck['user'];
        $players = $accessCheck['players'];

        // Get recent blogs (news/media posts)
        $blogs = Blog::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->take(12)
            ->get();

        // Get gallery images
        $galleries = \App\Models\Gallery::orderBy('created_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('parent.media', compact(
            'user',
            'players',
            'blogs',
            'galleries'
        ));
    }

    /**
     * AI Insights - View AI-powered analytical insights
     */
    public function insights(Request $request)
    {
        $accessCheck = $this->checkParentAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $user = $accessCheck['user'];
        $players = $accessCheck['players'];
        $playerId = $request->get('player_id');

        // Find the selected player
        $selectedPlayer = null;
        if ($playerId) {
            $selectedPlayer = $players->where('id', $playerId)->first();
        }

        if (!$selectedPlayer) {
            $selectedPlayer = $players->first();
        }

        if (!$selectedPlayer) {
            return redirect()->route('parent.dashboard')
                ->with('error', 'No player found.');
        }

        // Get AI insights for this player
        $insights = AiInsight::where('player_id', $selectedPlayer->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get performance metrics
        $performanceMetrics = $this->getPerformanceMetrics($selectedPlayer);

        // Calculate scores for the view
        $goals = GameStatistic::where('player_id', $selectedPlayer->id)->sum('goals_scored');
        $assists = GameStatistic::where('player_id', $selectedPlayer->id)->sum('assists');
        $matches = GameStatistic::where('player_id', $selectedPlayer->id)->count();
        $performanceScore = min(100, ($goals * 2 + $assists * 1.5 + $matches * 3));

        // Training consistency
        $totalSessions = Attendance::where('player_id', $selectedPlayer->id)->count();
        $attendedSessions = Attendance::where('player_id', $selectedPlayer->id)->whereNotNull('check_in_time')->count();
        $trainingConsistency = $totalSessions > 0 ? round(($attendedSessions / $totalSessions) * 100, 1) : 0;

        // Mental strength (random for now - would come from assessments)
        $mentalStrength = rand(70, 95);

        // Match rating
        $matchRating = $matches > 0 ? round(($goals * 2 + $assists * 1.5) / $matches + 5, 1) : 0;

        // Skills data for radar chart
        $skillsData = [
            ['skill' => 'Technical', 'score' => rand(60, 90)],
            ['skill' => 'Tactical', 'score' => rand(55, 85)],
            ['skill' => 'Physical', 'score' => rand(65, 95)],
            ['skill' => 'Mental', 'score' => $mentalStrength],
            ['skill' => 'Communication', 'score' => rand(60, 90)],
        ];

        // Progress data
        $progressData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $progressData[] = [
                'month' => $month->format('M'),
                'score' => rand(60, 95),
            ];
        }

        // Recommendations
        $recommendations = $this->getDevelopmentalRecommendations($selectedPlayer);

        // Weak areas
        $weakAreas = [];
        if ($trainingConsistency < 80) {
            $weakAreas[] = [
                'name' => 'Training Attendance',
                'score' => $trainingConsistency,
                'tip' => 'Attend more training sessions to improve consistency.',
            ];
        }
        if ($performanceScore < 50) {
            $weakAreas[] = [
                'name' => 'Goal Scoring',
                'score' => rand(30, 50),
                'tip' => 'Practice finishing drills to improve goal-scoring.',
            ];
        }

        return view('parent.insights', compact(
            'user',
            'players',
            'selectedPlayer',
            'insights',
            'performanceMetrics',
            'performanceScore',
            'mentalStrength',
            'trainingConsistency',
            'matchRating',
            'skillsData',
            'progressData',
            'recommendations',
            'weakAreas'
        ));
    }

    /**
     * Announcements - View club announcements
     */
    public function announcements(Request $request)
    {
        $accessCheck = $this->checkParentAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $user = $accessCheck['user'];
        $players = $accessCheck['players'];

        $type = $request->get('type');

        // Get published blogs as announcements (using published_at)
        $query = Blog::whereNotNull('published_at')
            ->where('published_at', '<=', now());

        if ($type) {
            $query->where('category', $type);
        }

        $announcements = $query->orderBy('published_at', 'desc')->paginate(10);

        return view('parent.announcements', compact(
            'user',
            'players',
            'announcements'
        ));
    }

    /**
     * Update parent profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'phone' => 'nullable|string|max:20',
        ]);

        $user->phone = $request->input('phone');
        $user->save();

        return redirect()->route('parent.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update parent password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('parent.profile')->with('error', 'Current password is incorrect.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('parent.profile')->with('success', 'Password updated successfully.');
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    /**
     * Get quick statistics for dashboard
     */
    private function getQuickStats($player)
    {
        if (!$player) {
            return [
                'training_sessions' => 0,
                'goals_scored' => 0,
                'assists' => 0,
                'minutes_played' => 0,
                'appearances' => 0,
                'attendance_rate' => 0,
            ];
        }

        // Training sessions
        $trainingSessions = Attendance::where('player_id', $player->id)
            ->whereNotNull('check_in_time')
            ->count();

        // Game stats
        $goalsScored = GameStatistic::where('player_id', $player->id)
            ->sum('goals_scored');
        $assists = GameStatistic::where('player_id', $player->id)
            ->sum('assists');
        $minutesPlayed = GameStatistic::where('player_id', $player->id)
            ->sum('minutes_played');
        $appearances = GameStatistic::where('player_id', $player->id)
            ->count();

        // Attendance rate
        $totalSessions = Attendance::where('player_id', $player->id)->count();
        $attendedSessions = Attendance::where('player_id', $player->id)
            ->whereNotNull('check_in_time')
            ->count();
        $attendanceRate = $totalSessions > 0 ? round(($attendedSessions / $totalSessions) * 100, 1) : 0;

        return [
            'training_sessions' => $trainingSessions,
            'goals_scored' => $goalsScored,
            'assists' => $assists,
            'minutes_played' => $minutesPlayed,
            'appearances' => $appearances,
            'attendance_rate' => $attendanceRate,
        ];
    }

    /**
     * Get financial summary
     */
    private function getFinancialSummary($player)
    {
        if (!$player) {
            return [];
        }

        $totalPaid = Payment::where('player_id', $player->id)
            ->where('payment_status', 'completed')
            ->sum('amount');

        $latestBilling = MonthlyBilling::where('player_id', $player->id)
            ->latest('month_year')
            ->first();

        $currentBalance = $latestBilling ? $latestBilling->closing_balance : 0;
        $monthlyFee = $player->getMonthlyFee();

        return [
            'total_paid' => $totalPaid,
            'current_balance' => $currentBalance,
            'monthly_fee' => $monthlyFee,
            'fee_category' => $player->fee_category,
        ];
    }

    /**
     * Get attendance summary
     */
    private function getAttendanceSummary($player)
    {
        if (!$player) {
            return [];
        }

        // Last 30 days
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $recentAttendances = Attendance::where('player_id', $player->id)
            ->where('session_date', '>=', $thirtyDaysAgo)
            ->get();

        $totalSessions = $recentAttendances->count();
        $attendedSessions = $recentAttendances->whereNotNull('check_in_time')->count();
        $attendanceRate = $totalSessions > 0 ? round(($attendedSessions / $totalSessions) * 100, 1) : 0;

        $totalMinutes = $recentAttendances->sum('total_duration_minutes');

        return [
            'total_sessions' => $totalSessions,
            'attended_sessions' => $attendedSessions,
            'attendance_rate' => $attendanceRate,
            'total_minutes' => $totalMinutes,
        ];
    }

    /**
     * Get upcoming training sessions
     */
    private function getUpcomingSessions($player)
    {
        if (!$player) {
            return [];
        }

        // Get sessions for player's program
        $programId = $player->program_id;

        return TrainingSession::where('program_id', $programId)
            ->where('session_date', '>=', Carbon::now())
            ->orderBy('session_date')
            ->take(5)
            ->get();
    }

    /**
     * Get recent matches
     */
    private function getRecentMatches($player)
    {
        if (!$player) {
            return [];
        }

        return GameStatistic::with(['match'])
            ->where('player_id', $player->id)
            ->orderBy('match_date', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Get AI insights
     */
    private function getAiInsights($player)
    {
        if (!$player) {
            return collect();
        }

        return AiInsight::where('player_id', $player->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Get monthly attendance statistics
     */
    private function getMonthlyAttendanceStats($player)
    {
        if (!$player) {
            return [];
        }

        $stats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->startOfMonth();
            $monthEnd = $month->endOfMonth();

            $attendances = Attendance::where('player_id', $player->id)
                ->whereBetween('session_date', [$monthStart, $monthEnd])
                ->get();

            $stats[] = [
                'month' => $month->format('M Y'),
                'sessions' => $attendances->count(),
                'attended' => $attendances->whereNotNull('check_in_time')->count(),
                'minutes' => $attendances->sum('total_duration_minutes'),
            ];
        }

        return $stats;
    }

    /**
     * Get monthly match statistics
     */
    private function getMonthlyMatchStats($player)
    {
        if (!$player) {
            return [];
        }

        $stats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->startOfMonth();
            $monthEnd = $month->endOfMonth();

            $matches = GameStatistic::where('player_id', $player->id)
                ->whereBetween('match_date', [$monthStart, $monthEnd])
                ->get();

            $stats[] = [
                'month' => $month->format('M Y'),
                'matches' => $matches->count(),
                'goals' => $matches->sum('goals_scored'),
                'assists' => $matches->sum('assists'),
                'minutes' => $matches->sum('minutes_played'),
            ];
        }

        return $stats;
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics($player)
    {
        if (!$player) {
            return [];
        }

        // Get last 10 matches
        $recentMatches = GameStatistic::where('player_id', $player->id)
            ->orderBy('match_date', 'desc')
            ->take(10)
            ->get();

        $avgMinutes = $recentMatches->avg('minutes_played');
        $avgGoals = $recentMatches->avg('goals_scored');
        $avgAssists = $recentMatches->avg('assists');

        return [
            'avg_minutes' => round($avgMinutes ?? 0, 1),
            'avg_goals' => round($avgGoals ?? 0, 1),
            'avg_assists' => round($avgAssists ?? 0, 1),
            'total_matches' => $recentMatches->count(),
        ];
    }

    /**
     * Get mental strength metrics (placeholder - can be expanded)
     */
    private function getMentalStrengthMetrics($player)
    {
        if (!$player) {
            return [];
        }

        // This would typically come from assessments or AI insights
        // For now, provide placeholder data
        return [
            'focus' => rand(70, 95),
            'confidence' => rand(65, 90),
            'resilience' => rand(70, 95),
            'teamwork' => rand(75, 98),
            'motivation' => rand(70, 92),
        ];
    }

    /**
     * Get developmental recommendations
     */
    private function getDevelopmentalRecommendations($player)
    {
        if (!$player) {
            return [];
        }

        // Generate recommendations based on player stats
        $recommendations = [];

        // Check goals
        $totalGoals = GameStatistic::where('player_id', $player->id)->sum('goals_scored');
        if ($totalGoals < 5) {
            $recommendations[] = [
                'area' => 'Attacking',
                'recommendation' => 'Focus on positioning and finishing drills to improve goal-scoring ability.',
                'priority' => 'high',
            ];
        }

        // Check attendance
        $totalSessions = Attendance::where('player_id', $player->id)->count();
        $attended = Attendance::where('player_id', $player->id)->whereNotNull('check_in_time')->count();
        $attendanceRate = $totalSessions > 0 ? ($attended / $totalSessions) * 100 : 0;

        if ($attendanceRate < 80) {
            $recommendations[] = [
                'area' => 'Attendance',
                'recommendation' => 'Improve training attendance to maximize development opportunities.',
                'priority' => 'high',
            ];
        }

        // Check playing time
        $avgMinutes = GameStatistic::where('player_id', $player->id)->avg('minutes_played');
        if ($avgMinutes && $avgMinutes < 45) {
            $recommendations[] = [
                'area' => 'Game Time',
                'recommendation' => 'Work on fitness and consistency to earn more playing time.',
                'priority' => 'medium',
            ];
        }

        return $recommendations;
    }
}
