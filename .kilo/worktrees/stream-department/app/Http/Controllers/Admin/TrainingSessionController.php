<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingSession;
use App\Models\Player;
use App\Models\WebsitePlayer;
use App\Models\Attendance;
use App\Models\ActivityLog;
use App\Services\SmsService;
use App\Http\Requests\TrainingSessionFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TrainingSessionController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Category mapping constants
     */
    const CATEGORY_MAP = [
        'U13' => 'u13',
        'U15' => 'u15',
        'U17' => 'u17',
        'Senior' => 'senior',
    ];

    public function index(Request $request)
    {
        $query = TrainingSession::with('startedBy');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('team_category')) {
            $query->where('team_category', $request->team_category);
        }

        if (Auth::user()->hasRole('coach')) {
            // Enhanced implementation based on coach's assigned teams needed
        }

        $sessions = $query->orderBy('scheduled_start_time', 'desc')->paginate(20);

        $teamCategories = TrainingSession::select('team_category')
            ->distinct()
            ->whereNotNull('team_category')
            ->pluck('team_category')
            ->sort();

        return view('admin.training-sessions.index', compact('sessions', 'teamCategories'));
    }

    public function create()
    {
        return view('admin.training-sessions.create');
    }

    public function store(TrainingSessionFormRequest $request)
    {
        $validated = $request->validated();
        $validated['started_by'] = auth()->id();
        $validated['status'] = 'scheduled';

        TrainingSession::create($validated);

        return redirect()->route('admin.training-sessions.index')
            ->with('success', 'Training session scheduled successfully.');
    }

    public function show(TrainingSession $trainingSession)
    {
        $trainingSession->load(['attendances.player', 'startedBy']);

        $playerCategory = self::CATEGORY_MAP[$trainingSession->team_category] ?? $trainingSession->team_category;

        Log::debug('PLAYER_SYNC_DEBUG: Category mapping for session', [
            'session_id' => $trainingSession->id,
            'team_category' => $trainingSession->team_category,
            'player_category' => $playerCategory,
        ]);

        $registrationFormPlayers = $this->getRegistrationFormPlayers($trainingSession, $playerCategory);
        $websitePlayers = $this->getWebsitePlayers($trainingSession, $playerCategory);

        $availablePlayers = $registrationFormPlayers->merge($websitePlayers)->sortBy('first_name');

        Log::debug('PLAYER_SYNC_DEBUG: Total available players after merge', [
            'session_id' => $trainingSession->id,
            'total_count' => $availablePlayers->count(),
            'registration_form_count' => $registrationFormPlayers->count(),
            'website_count' => $websitePlayers->count(),
        ]);

        return view('admin.training-sessions.show', compact('trainingSession', 'availablePlayers'));
    }

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

    public function end(TrainingSession $trainingSession)
    {
        try {
            $trainingSession->end();
            $this->sendSessionEndNotifications($trainingSession);

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

    private function sendSessionEndNotifications(TrainingSession $trainingSession)
    {
        $playerIds = $trainingSession->attendances()->pluck('player_id');
        $players = Player::whereIn('id', $playerIds)->get();

        $sentCount = 0;
        $failedCount = 0;

        foreach ($players as $player) {
            if (!$player->parent_phone) {
                continue;
            }

            $message = "Dear Parent/Guardian,\n\n" .
                "Your child {$player->full_name} attended the {$trainingSession->team_category} {$trainingSession->session_type} session.\n\n" .
                "Duration: {$trainingSession->total_duration_minutes} minutes\n" .
                "End Time: {$trainingSession->end_time->format('M j, Y g:i A')}\n\n" .
                "Vipers Academy";

            $result = $this->smsService->sendSms($player->parent_phone, $message);

            $result ? $sentCount++ : $failedCount++;
        }

        Log::info('Session end notifications sent', [
            'session_id' => $trainingSession->id,
            'sent' => $sentCount,
            'failed' => $failedCount,
        ]);

        return ['sent' => $sentCount, 'failed' => $failedCount];
    }

    public function admitPlayer(Request $request, TrainingSession $trainingSession)
    {
        $validated = $request->validate([
            'player_id' => 'required',
            'source' => 'required|in:registration_form,website',
        ]);

        $source = $validated['source'];
        $playerId = $validated['player_id'];

        Log::debug('PLAYER_SYNC_DEBUG: Player admission attempt', [
            'session_id' => $trainingSession->id,
            'player_id' => $playerId,
            'source' => $source,
        ]);

        $player = $this->resolvePlayer($request, $source, $playerId);

        try {
            $attendance = $trainingSession->admitPlayer($player, Auth::id());

            Log::debug('PLAYER_SYNC_DEBUG: Player admitted successfully', [
                'session_id' => $trainingSession->id,
                'player_id' => $playerId,
                'source' => $source,
                'attendance_id' => $attendance->id,
            ]);

            if ($source === 'registration_form' && $player->parent_phone) {
                $this->smsService->sendAdmissionNotification($player, $trainingSession);
            }

            ActivityLog::log('admitted_player_to_session', 'Attendance', $attendance->id, [
                'session_id' => $trainingSession->id,
                'player_id' => $playerId,
                'player_source' => $source,
                'missed_minutes' => $attendance->missed_minutes,
                'lateness_category' => $attendance->lateness_category,
            ]);

            return redirect()->back()->with('success', 'Player admitted successfully.');
        } catch (\Exception $e) {
            Log::error('PLAYER_SYNC_DEBUG: Player admission failed', [
                'session_id' => $trainingSession->id,
                'player_id' => $playerId,
                'source' => $source,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    protected function resolvePlayer(Request $request, $source, $playerId)
    {
        if ($source === 'registration_form') {
            $request->validate(['player_id' => 'exists:players,id']);
            $player = Player::find($playerId);
            $player->source = 'registration_form';
        } else {
            $request->validate(['player_id' => 'exists:website_uploaded_players,id']);
            $websitePlayer = WebsitePlayer::find($playerId);

            if (!$websitePlayer) {
                throw new \Exception('Website player not found.');
            }

            if ($websitePlayer->player_id) {
                $player = Player::find($websitePlayer->player_id);
                if (!$player) {
                    throw new \Exception('Linked player record not found. Please sync the website player first.');
                }
                $player->source = 'website';
                $player->websitePlayerId = $websitePlayer->id;
            } else {
                $player = $this->createPlayerFromWebsite($websitePlayer);
            }
        }

        return $player;
    }

    protected function createPlayerFromWebsite(WebsitePlayer $websitePlayer)
    {
        $player = Player::create([
            'first_name' => $websitePlayer->first_name,
            'last_name' => $websitePlayer->last_name,
            'full_name' => $websitePlayer->first_name . ' ' . $websitePlayer->last_name,
            'category' => $websitePlayer->category,
            'position' => $websitePlayer->position,
            'age' => $websitePlayer->age,
            'image_path' => $websitePlayer->image_path,
            'registration_status' => 'Active',
            'approval_type' => 'full',
            'documents_completed' => true,
        ]);

        $websitePlayer->update(['player_id' => $player->id]);

        $player->source = 'website';
        $player->websitePlayerId = $websitePlayer->id;

        Log::debug('PLAYER_SYNC_DEBUG: Auto-created player from orphaned website player', [
            'website_player_id' => $websitePlayer->id,
            'new_player_id' => $player->id,
            'player_name' => $player->full_name,
        ]);

        return $player;
    }

    public function checkOutPlayer(Request $request, TrainingSession $trainingSession)
    {
        $validated = $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
        ]);

        $attendance = Attendance::findOrFail($validated['attendance_id']);

        try {
            $attendance->checkOut();

            Log::debug('TIMER_DEBUG: Player checked out via controller', [
                'attendance_id' => $attendance->id,
                'player_id' => $attendance->player_id,
                'session_id' => $trainingSession->id,
                'total_duration_minutes' => $attendance->total_duration_minutes,
            ]);

            ActivityLog::log('checked_out_player_from_session', 'Attendance', $attendance->id, [
                'session_id' => $trainingSession->id,
                'player_id' => $attendance->player_id,
                'total_duration_minutes' => $attendance->total_duration_minutes,
            ]);

            return redirect()->back()->with('success', 'Player checked out successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit(TrainingSession $trainingSession)
    {
        if ($trainingSession->status !== 'scheduled') {
            return redirect()->route('admin.training-sessions.show', $trainingSession)
                ->with('error', 'Cannot edit sessions that are active or ended.');
        }

        return view('admin.training-sessions.edit', compact('trainingSession'));
    }

    public function update(TrainingSessionFormRequest $request, TrainingSession $trainingSession)
    {
        if ($trainingSession->status !== 'scheduled') {
            return redirect()->route('admin.training-sessions.show', $trainingSession)
                ->with('error', 'Cannot update sessions that are active or ended.');
        }

        $trainingSession->update($request->validated());

        ActivityLog::log('updated_training_session', 'TrainingSession', $trainingSession->id, [
            'session_type' => $request->session_type,
            'team_category' => $request->team_category,
            'scheduled_start_time' => $request->scheduled_start_time,
        ]);

        return redirect()->route('admin.training-sessions.show', $trainingSession)
            ->with('success', 'Training session updated successfully.');
    }

    public function destroy(TrainingSession $trainingSession)
    {
        if ($trainingSession->status !== 'scheduled') {
            return redirect()->route('admin.training-sessions.show', $trainingSession)
                ->with('error', 'Cannot delete sessions that are active or ended.');
        }

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

    public function getPlayersForAttendance(TrainingSession $trainingSession)
    {
        $playerCategory = self::CATEGORY_MAP[$trainingSession->team_category] ?? $trainingSession->team_category;

        $registrationFormPlayers = $this->formatRegistrationFormPlayers($trainingSession, $playerCategory);
        $websitePlayers = $this->formatWebsitePlayers($trainingSession, $playerCategory);

        $players = $registrationFormPlayers->merge($websitePlayers)->sortBy('full_name');

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

    protected function getRegistrationFormPlayers(TrainingSession $trainingSession, $playerCategory)
    {
        return Player::where('category', $playerCategory)
            ->where('registration_status', 'Active')
            ->whereDoesntHave('attendances', function ($query) use ($trainingSession) {
                $query->where('session_id', $trainingSession->id);
            })
            ->orderBy('first_name')
            ->get()
            ->each(function ($player) {
                $player->source = 'registration_form';
            });
    }

    protected function getWebsitePlayers(TrainingSession $trainingSession, $playerCategory)
    {
        return WebsitePlayer::where('category', $playerCategory)
            ->whereDoesntHave('player.attendances', function ($query) use ($trainingSession) {
                $query->where('session_id', $trainingSession->id);
            })
            ->orderBy('first_name')
            ->get()
            ->each(function ($player) {
                $player->source = 'website';
            });
    }

    protected function formatRegistrationFormPlayers(TrainingSession $trainingSession, $playerCategory)
    {
        return Player::where('category', $playerCategory)
            ->where('registration_status', 'Active')
            ->with(['attendances' => function ($query) use ($trainingSession) {
                $query->where('session_id', $trainingSession->id);
            }])
            ->orderBy('first_name')
            ->get()
            ->map(function ($player) {
                $attendanceRecord = $player->attendances->first();

                return [
                    'id' => $player->id,
                    'source' => 'registration_form',
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
    }

    protected function formatWebsitePlayers(TrainingSession $trainingSession, $playerCategory)
    {
        return WebsitePlayer::where('category', $playerCategory)
            ->with(['player' => function ($query) use ($trainingSession) {
                $query->with(['attendances' => function ($q) use ($trainingSession) {
                    $q->where('session_id', $trainingSession->id);
                }]);
            }])
            ->orderBy('first_name')
            ->get()
            ->map(function ($websitePlayer) {
                $attendanceRecord = $websitePlayer->player ? $websitePlayer->player->attendances->first() : null;

                return [
                    'id' => $websitePlayer->id,
                    'source' => 'website',
                    'full_name' => $websitePlayer->full_name,
                    'position' => $websitePlayer->position,
                    'image_path' => $websitePlayer->image_path,
                    'image_url' => $websitePlayer->getImageUrlAttribute(),
                    'attendance_record' => $attendanceRecord ? [
                        'check_in_time' => $attendanceRecord->check_in_time?->format('H:i'),
                        'lateness_category' => $attendanceRecord->lateness_category,
                        'missed_minutes' => $attendanceRecord->missed_minutes,
                    ] : null,
                ];
            });
    }

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
