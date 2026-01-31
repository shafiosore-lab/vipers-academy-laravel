<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingSession;
use App\Models\Player;
use App\Models\ActivityLog;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingSessionController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display a listing of training sessions.
     */
    public function index(Request $request)
    {
        $query = TrainingSession::with('startedBy');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by team
        if ($request->filled('team_category')) {
            $query->where('team_category', $request->team_category);
        }

        // For coaches, only show sessions for their teams
        if (Auth::user()->hasRole('coach')) {
            // This would need to be enhanced based on coach's assigned teams
            // For now, show all sessions
        }

        $sessions = $query->orderBy('scheduled_start_time', 'desc')->paginate(20);

        // Get team categories for filter
        $teamCategories = TrainingSession::select('team_category')
            ->distinct()
            ->whereNotNull('team_category')
            ->pluck('team_category')
            ->sort();

        return view('admin.training-sessions.index', compact('sessions', 'teamCategories'));
    }

    /**
     * Show the form for creating a new training session.
     */
    public function create()
    {
        return view('admin.training-sessions.create');
    }

    /**
     * Store a newly created training session.
     */
    public function store(Request $request)
    {
        $request->validate([
            'session_type' => 'required|in:training,match,office_time,meeting',
            'team_category' => 'required|string',
            'scheduled_start_time' => 'required|date|after:now',
        ]);

        TrainingSession::create([
            'session_type' => $request->session_type,
            'team_category' => $request->team_category,
            'scheduled_start_time' => $request->scheduled_start_time,
            'started_by' => auth()->id(),
            'status' => 'scheduled',
        ]);

        return redirect()->route('admin.training-sessions.index')
            ->with('success', 'Training session scheduled successfully.');
    }

    /**
     * Display the specified training session.
     */
    public function show(TrainingSession $trainingSession)
    {
        $trainingSession->load(['attendances.player', 'startedBy']);

        // Map session team_category to player category format
        $categoryMapping = [
            'U13' => 'u13',
            'U15' => 'u15',
            'U17' => 'u17',
            'Senior' => 'senior',
        ];

        $playerCategory = $categoryMapping[$trainingSession->team_category] ?? $trainingSession->team_category;

        // Get available players for admission (active players in this team category)
        $availablePlayers = Player::where('category', $playerCategory)
            ->where('registration_status', 'Active')
            ->whereDoesntHave('attendances', function ($query) use ($trainingSession) {
                $query->where('session_id', $trainingSession->id);
            })
            ->orderBy('first_name')
            ->get();

        return view('admin.training-sessions.show', compact('trainingSession', 'availablePlayers'));
    }

    /**
     * Start a training session.
     */
    public function start(TrainingSession $trainingSession)
    {
        try {
            $trainingSession->start(Auth::id());

            ActivityLog::log('started_training_session', 'TrainingSession', $trainingSession->id, [
                'team_category' => $trainingSession->team_category,
                'scheduled_start_time' => $trainingSession->scheduled_start_time,
            ]);

            return redirect()->back()->with('success', 'Training session started successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * End a training session.
     */
    public function end(TrainingSession $trainingSession)
    {
        try {
            $trainingSession->end();

            ActivityLog::log('ended_training_session', 'TrainingSession', $trainingSession->id, [
                'team_category' => $trainingSession->team_category,
                'total_duration_minutes' => $trainingSession->total_duration_minutes,
                'players_admitted' => $trainingSession->players_admitted,
            ]);

            return redirect()->back()->with('success', 'Training session ended successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Admit a player to the training session.
     */
    public function admitPlayer(Request $request, TrainingSession $trainingSession)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
        ]);

        $player = Player::find($request->player_id);

        try {
            $attendance = $trainingSession->admitPlayer($player, Auth::id());

            // Send SMS notification to parent
            $this->smsService->sendAdmissionNotification($player, $trainingSession);

            ActivityLog::log('admitted_player_to_session', 'Attendance', $attendance->id, [
                'session_id' => $trainingSession->id,
                'player_id' => $player->id,
                'missed_minutes' => $attendance->missed_minutes,
                'lateness_category' => $attendance->lateness_category,
            ]);

            return redirect()->back()->with('success', 'Player admitted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified training session.
     */
    public function edit(TrainingSession $trainingSession)
    {
        // Only allow editing of scheduled sessions (not active or ended)
        if ($trainingSession->status !== 'scheduled') {
            return redirect()->route('admin.training-sessions.show', $trainingSession)
                ->with('error', 'Cannot edit sessions that are active or ended.');
        }

        return view('admin.training-sessions.edit', compact('trainingSession'));
    }

    /**
     * Update the specified training session.
     */
    public function update(Request $request, TrainingSession $trainingSession)
    {
        // Only allow updating of scheduled sessions
        if ($trainingSession->status !== 'scheduled') {
            return redirect()->route('admin.training-sessions.show', $trainingSession)
                ->with('error', 'Cannot update sessions that are active or ended.');
        }

        $request->validate([
            'session_type' => 'required|in:training,match,office_time,meeting',
            'team_category' => 'required|string',
            'scheduled_start_time' => 'required|date|after:now',
        ]);

        $trainingSession->update([
            'session_type' => $request->session_type,
            'team_category' => $request->team_category,
            'scheduled_start_time' => $request->scheduled_start_time,
        ]);

        ActivityLog::log('updated_training_session', 'TrainingSession', $trainingSession->id, [
            'session_type' => $request->session_type,
            'team_category' => $request->team_category,
            'scheduled_start_time' => $request->scheduled_start_time,
        ]);

        return redirect()->route('admin.training-sessions.show', $trainingSession)
            ->with('success', 'Training session updated successfully.');
    }

    /**
     * Remove the specified training session.
     */
    public function destroy(TrainingSession $trainingSession)
    {
        // Only allow deletion of scheduled sessions
        if ($trainingSession->status !== 'scheduled') {
            return redirect()->route('admin.training-sessions.show', $trainingSession)
                ->with('error', 'Cannot delete sessions that are active or ended.');
        }

        // Check if session has any attendance records
        if ($trainingSession->attendances()->count() > 0) {
            return redirect()->route('admin.training-sessions.show', $trainingSession)
                ->with('error', 'Cannot delete session with existing attendance records.');
        }

        ActivityLog::log('deleted_training_session', 'TrainingSession', $trainingSession->id, [
            'session_type' => $trainingSession->session_type,
            'team_category' => $trainingSession->team_category,
            'scheduled_start_time' => $trainingSession->scheduled_start_time,
        ]);

        $trainingSession->delete();

        return redirect()->route('admin.training-sessions.index')
            ->with('success', 'Training session deleted successfully.');
    }

    /**
     * Get players for attendance recording in a session.
     */
    public function getPlayersForAttendance(TrainingSession $trainingSession)
    {
        // Map session team_category to player category format
        $categoryMapping = [
            'U13' => 'u13',
            'U15' => 'u15',
            'U17' => 'u17',
            'Senior' => 'senior',
        ];

        $playerCategory = $categoryMapping[$trainingSession->team_category] ?? $trainingSession->team_category;

        $players = Player::where('category', $playerCategory)
            ->where('registration_status', 'Active')
            ->with(['attendances' => function ($query) use ($trainingSession) {
                $query->where('session_id', $trainingSession->id);
            }])
            ->orderBy('first_name')
            ->get()
            ->map(function ($player) use ($trainingSession) {
                $attendanceRecord = $player->attendances->first();

                return [
                    'id' => $player->id,
                    'full_name' => $player->full_name,
                    'position' => $player->position,
                    'image_path' => $player->image_path,
                    'image_url' => $player->image_path ? asset('storage/' . $player->image_path) : null,
                    'attendance_record' => $attendanceRecord ? [
                        'check_in_time' => $attendanceRecord->check_in_time?->format('H:i'),
                        'lateness_category' => $attendanceRecord->lateness_category,
                        'missed_minutes' => $attendanceRecord->missed_minutes,
                    ] : null,
                ];
            });

        return response()->json([
            'session' => [
                'id' => $trainingSession->id,
                'session_type' => ucfirst($trainingSession->session_type),
                'team_category' => $trainingSession->team_category,
                'scheduled_time' => $trainingSession->scheduled_start_time->format('M j, Y H:i'),
                'status' => $trainingSession->status,
            ],
            'players' => $players,
        ]);
    }

    /**
     * Get live session data for AJAX updates.
     */
    public function liveData(TrainingSession $trainingSession)
    {
        return response()->json([
            'elapsed_time' => $trainingSession->formatted_elapsed_time,
            'players_admitted' => $trainingSession->players_admitted,
            'late_arrivals' => $trainingSession->late_arrivals,
            'punctuality_rate' => $trainingSession->punctuality_rate,
            'status' => $trainingSession->status,
        ]);
    }
}
