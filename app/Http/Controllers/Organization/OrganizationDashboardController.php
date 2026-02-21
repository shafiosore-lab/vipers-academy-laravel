<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Program;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Payment;
use App\Models\Attendance;
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
            'aiInsights'
        ));
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
}
