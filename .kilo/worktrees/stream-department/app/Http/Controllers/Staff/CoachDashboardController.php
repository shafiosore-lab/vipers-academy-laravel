<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\TrainingSession;
use App\Models\Attendance;
use Illuminate\Http\Request;

class CoachDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:coach|assistant-coach|head-coach']);
    }

    public function index()
    {
        $user = auth()->user();

        // Get players assigned to this coach (if any)
        $players = Player::count();
        $activePlayers = Player::where('registration_status', 'Active')->count();

        // Get upcoming training sessions
        $upcomingSessions = TrainingSession::where('scheduled_start_time', '>', now())
            ->orderBy('scheduled_start_time')
            ->take(5)
            ->get();

        // Get today's sessions
        $todaySessions = TrainingSession::whereDate('scheduled_start_time', today())->get();

        // Get recent attendance
        $recentAttendance = Attendance::with(['player', 'trainingSession'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get player statistics
        $playersByAgeGroup = Player::select('age_group')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('age_group')
            ->get();

        // Get performance metrics
        $highPerformers = Player::where('performance_rating', '>=', 8.0)->count();
        $playersNeedingAttention = Player::where('needs_attention', true)->count();

        return view('staff/coach/dashboard', compact(
            'user',
            'players',
            'activePlayers',
            'upcomingSessions',
            'todaySessions',
            'recentAttendance',
            'playersByAgeGroup',
            'highPerformers',
            'playersNeedingAttention'
        ));
    }

    public function trainingSessions()
    {
        $sessions = TrainingSession::with(['attendances.player'])
            ->orderBy('scheduled_start_time', 'desc')
            ->paginate(15);

        return view('staff/coach/sessions', compact('sessions'));
    }

    public function playerProgress($playerId)
    {
        $player = Player::with(['attendance.trainingSession'])->findOrFail($playerId);

        $attendanceRate = $player->attendance()->count() > 0
            ? round(($player->attendance()->where('status', 'present')->count() / $player->attendance()->count()) * 100, 1)
            : 0;

        return view('staff/coach/player-progress', compact('player', 'attendanceRate'));
    }

    public function players(Request $request)
    {
        $query = Player::query();

        // Filter by search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by age group
        if ($request->has('age_group') && $request->age_group) {
            $query->where('age_group', $request->age_group);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('registration_status', $request->status);
        }

        // Get all players with pagination
        $players = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get unique age groups for filter
        $ageGroups = Player::distinct()->pluck('age_group');

        return view('staff/coach/players', compact('players', 'ageGroups'));
    }
}
