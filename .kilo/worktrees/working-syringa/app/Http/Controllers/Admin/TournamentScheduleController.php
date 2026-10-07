<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\TournamentVenue;
use App\Services\TournamentSchedulingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TournamentScheduleController extends Controller
{
    protected $scheduler;

    public function __construct(TournamentSchedulingService $scheduler)
    {
        $this->scheduler = $scheduler;
    }

    /**
     * Get the route prefix based on current user role
     * Note: We use admin views for both admin and super-admin to avoid duplication
     */
    protected function getRoutePrefix(): string
    {
        // Always use admin views since they're shared
        return 'admin';
    }

    /**
     * Display tournament scheduling dashboard
     */
    public function index(Tournament $tournament)
    {
        $routePrefix = $this->getRoutePrefix();

        // Validate tournament has dates
        $dateValidation = $this->scheduler->validateTournamentDates($tournament);

        // Get scheduling stats
        $stats = $this->scheduler->getSchedulingStats($tournament);

        // Check for constraint violations
        $violations = $this->scheduler->checkSchedulingConstraints($tournament);

        // Get unscheduled matches
        $unscheduledMatches = $tournament->matches()
            ->whereNull('kickoff_time')
            ->where('status', TournamentMatch::STATUS_SCHEDULED)
            ->with(['homeTeam.team', 'awayTeam.team', 'pool'])
            ->get();

        // Get venues
        $venues = $tournament->venues()->active()->ordered()->get();

        // Get existing matches for display
        $matches = $tournament->matches()
            ->with(['homeTeam.team', 'awayTeam.team', 'pool', 'venue'])
            ->orderBy('kickoff_time')
            ->paginate(20);

        // Calculate view-specific stats
        $scheduledMatches = $matches->whereNotNull('kickoff_time')->count() +
            $tournament->matches()->whereNotNull('kickoff_time')->whereDoesntHave('venueModel')->count();
        $pendingMatches = $unscheduledMatches->count();
        $completedMatches = $tournament->matches()->where('status', TournamentMatch::STATUS_COMPLETED)->count();
        $config = is_array($tournament->scheduling_config) ? $tournament->scheduling_config : [];

        // Calculate tournament days
        $totalDays = 0;
        if ($tournament->start_date && $tournament->end_date) {
            $totalDays = Carbon::parse($tournament->start_date)->diffInDays(Carbon::parse($tournament->end_date)) + 1;
        }

        // Calculate required matches based on teams
        $teamCount = $tournament->approvedTeams()->count();
        $requiredMatches = 0;
        if ($teamCount >= 2) {
            switch ($tournament->competition_format) {
                case 'league':
                    $requiredMatches = ($teamCount * ($teamCount - 1)) / 2;
                    break;
                case 'knockout':
                    $requiredMatches = $teamCount - 1;
                    break;
                case 'groups_knockout':
                    $requiredMatches = $teamCount + ($teamCount / 4) - 1;
                    break;
                default:
                    $requiredMatches = ($teamCount * ($teamCount - 1)) / 2;
            }
        }

        return view("{$routePrefix}.tournaments.schedule.index", compact(
            'tournament',
            'stats',
            'violations',
            'unscheduledMatches',
            'venues',
            'matches',
            'dateValidation',
            'routePrefix',
            'scheduledMatches',
            'pendingMatches',
            'completedMatches',
            'config',
            'totalDays',
            'requiredMatches'
        ));
    }

    /**
     * Show scheduling configuration form
     */
    public function config(Tournament $tournament)
    {
        $routePrefix = $this->getRoutePrefix();

        // Get current config or defaults
        $config = $tournament->scheduling_config ?? [
            'match_duration' => 90,
            'buffer_time' => 15,
            'min_rest_hours' => 24,
            'max_games_per_venue_per_day' => 4,
            'max_games_per_team_per_day' => 1,
            'timezone' => 'Africa/Nairobi',
        ];

        $timezones = TournamentMatch::getTimezones();
        $venues = $tournament->venues()->active()->ordered()->get();

        // Validate dates
        $dateValidation = $this->scheduler->validateTournamentDates($tournament);

        return view("{$routePrefix}.tournaments.schedule.config", compact(
            'tournament',
            'config',
            'timezones',
            'venues',
            'dateValidation',
            'routePrefix'
        ));
    }

    /**
     * Save scheduling configuration
     */
    public function saveConfig(Request $request, Tournament $tournament)
    {
        $validator = Validator::make($request->all(), [
            'match_duration' => 'required|integer|min:30|max:180',
            'halftime_duration' => 'nullable|integer|min:5|max:30',
            'rest_between_matches' => 'nullable|integer|min:0|max:120',
            'min_rest_hours' => 'required|integer|min:1|max:72',
            'max_games_per_venue_per_day' => 'required|integer|min:1|max:10',
            'concurrent_matches' => 'nullable|boolean',
            'algorithm' => 'nullable|string|in:balanced,home_away,min_travel,day_spread,venue_priority',
            'prefer_same_day' => 'nullable|boolean',
            'avoid_weekend_congestion' => 'nullable|boolean',
            'match_start_time' => 'nullable|date_format:H:i',
            'match_end_time' => 'nullable|date_format:H:i',
            'preferred_days' => 'nullable|array',
            'preferred_days.*' => 'nullable|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $config = $request->except(['_token', '_method']);

        // Ensure preferred_days is stored properly
        if (isset($config['preferred_days']) && is_array($config['preferred_days'])) {
            $config['preferred_days'] = $config['preferred_days'];
        }

        // Convert boolean strings to actual booleans
        $booleanFields = ['concurrent_matches', 'prefer_same_day', 'avoid_weekend_congestion'];
        foreach ($booleanFields as $field) {
            if (isset($config[$field])) {
                $config[$field] = filter_var($config[$field], FILTER_VALIDATE_BOOLEAN);
            }
        }

        $tournament->update(['scheduling_config' => $config]);

        // Use correct route prefix based on current URL
        $routePrefix = request()->is('super-admin/*') ? 'super-admin' : 'admin';
        return redirect()->route("{$routePrefix}.tournaments.schedule.index", $tournament->id)
            ->with('success', 'Scheduling configuration saved successfully!');
    }

    /**
     * Show time slots for the tournament
     */
    public function timeSlots(Tournament $tournament)
    {
        $routePrefix = $this->getRoutePrefix();

        // Get available slots
        $slots = $this->scheduler->getAvailableTimeSlots($tournament);

        // Format available slots for view
        $availableSlots = [];
        foreach ($slots as $slot) {
            if ($slot['available']) {
                $availableSlots[] = [
                    'date' => $slot['date'],
                    'time' => $slot['time'],
                    'venue' => $slot['venue']['name'] ?? 'TBD'
                ];
            }
        }

        // Calculate tournament days
        $totalDays = 0;
        if ($tournament->start_date && $tournament->end_date) {
            $totalDays = Carbon::parse($tournament->start_date)->diffInDays(Carbon::parse($tournament->end_date)) + 1;
        }

        $config = is_array($tournament->scheduling_config) ? $tournament->scheduling_config : [];
        $venues = $tournament->venues()->active()->ordered()->get();
        $dateValidation = $this->scheduler->validateTournamentDates($tournament);

        return view("{$routePrefix}.tournaments.schedule.time-slots", compact(
            'tournament',
            'availableSlots',
            'totalDays',
            'config',
            'venues',
            'dateValidation',
            'routePrefix'
        ));
    }

    /**
     * Generate auto schedule with fair distribution
     */
    public function autoSchedule(Request $request, Tournament $tournament)
    {
        $routePrefix = $this->getRoutePrefix();

        // Validate tournament has dates
        $validation = $this->scheduler->validateTournamentDates($tournament);

        if (!$validation['valid']) {
            return redirect()->back()
                ->with('error', 'Cannot schedule: ' . implode(', ', $validation['errors']));
        }

        // Check if matches exist
        if ($tournament->matches()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Matches already exist. Delete them first to generate a new schedule.');
        }

        // Check teams
        if ($tournament->approvedTeams()->count() < 2) {
            return redirect()->back()
                ->with('error', 'At least 2 approved teams required.');
        }

        // Get scheduling config
        $config = $tournament->scheduling_config ?? [];

        // Configure scheduler
        if (!empty($config['match_duration'])) {
            $this->scheduler->setMatchDuration($config['match_duration']);
        }
        if (!empty($config['buffer_time'])) {
            $this->scheduler->setBufferTime($config['buffer_time']);
        }
        if (!empty($config['min_rest_hours'])) {
            $this->scheduler->setMinRestHours($config['min_rest_hours']);
        }
        if (!empty($config['max_games_per_venue_per_day'])) {
            $this->scheduler->setMaxGamesPerVenuePerDay($config['max_games_per_venue_per_day']);
        }
        if (!empty($config['max_games_per_team_per_day'])) {
            $this->scheduler->setMaxGamesPerTeamPerDay($config['max_games_per_team_per_day']);
        }
        if (!empty($config['timezone'])) {
            $this->scheduler->setTimezone($config['timezone']);
        }
        if (!empty($config['time_slots'])) {
            $this->scheduler->setTimeSlots($config['time_slots']);
        }

        $venueId = $request->get('venue_id', $config['default_venue_id'] ?? null);
        $format = $request->get('format', $tournament->competition_format);

        // Generate based on format
        $result = match ($format) {
            'knockout', 'knockout_plus' => $this->scheduler->generateFairKnockoutSchedule($tournament, $venueId),
            'groups_knockout' => $this->scheduler->generateFairGroupStageSchedule($tournament, $venueId),
            default => $this->scheduler->generateFairLeagueSchedule($tournament, $venueId),
        };

        if (isset($result['error'])) {
            return redirect()->back()->with('error', $result['error']);
        }

        $scheduledCount = count($result['scheduled'] ?? []);
        $conflictCount = count($result['conflicts'] ?? []);

        $message = "Successfully scheduled {$scheduledCount} matches!";
        if ($conflictCount > 0) {
            $message .= " {$conflictCount} matches could not be scheduled due to constraints.";
        }

        // Show warnings if any
        if (!empty($validation['warnings'])) {
            session()->flash('warning', implode('. ', $validation['warnings']));
        }

        // Use correct route prefix based on current URL
        $routePrefix = request()->is('super-admin/*') ? 'super-admin' : 'admin';
        return redirect()->route("{$routePrefix}.tournaments.schedule.index", $tournament->id)
            ->with('success', $message);
    }

    /**
     * Show bulk scheduling form for unscheduled matches
     */
    public function bulkSchedule(Tournament $tournament)
    {
        $routePrefix = $this->getRoutePrefix();

        // Get unscheduled matches
        $unscheduledMatches = $tournament->matches()
            ->whereNull('kickoff_time')
            ->where('status', TournamentMatch::STATUS_SCHEDULED)
            ->with(['homeTeam.team', 'awayTeam.team', 'pool', 'venue'])
            ->get();

        if ($unscheduledMatches->isEmpty()) {
            $routePrefix = request()->is('super-admin/*') ? 'super-admin' : 'admin';
            return redirect()->route("{$routePrefix}.tournaments.schedule.index", $tournament->id)
                ->with('info', 'No unscheduled matches found.');
        }

        // Get available time slots - format as array
        $slots = $this->scheduler->getAvailableTimeSlots($tournament);
        $availableSlots = [];
        foreach ($slots as $slot) {
            if ($slot['available']) {
                $availableSlots[] = [
                    'date' => $slot['date'],
                    'time' => $slot['time'],
                    'venue' => $slot['venue']['name'] ?? 'TBD'
                ];
            }
        }

        $venues = $tournament->venues()->active()->ordered()->get();
        $dateValidation = $this->scheduler->validateTournamentDates($tournament);

        return view("{$routePrefix}.tournaments.schedule.bulk-schedule", compact(
            'tournament',
            'unscheduledMatches',
            'availableSlots',
            'venues',
            'dateValidation',
            'routePrefix'
        ));
    }

    /**
     * Process bulk scheduling
     */
    public function processBulkSchedule(Request $request, Tournament $tournament)
    {
        $routePrefix = $this->getRoutePrefix();

        // Accept both 'schedules' and 'matches' formats
        $matchesData = $request->input('matches', $request->input('schedules', []));

        if (empty($matchesData)) {
            return redirect()->back()
                ->with('error', 'No schedule data provided.')
                ->withInput();
        }

        $updates = [];
        $errors = [];
        $updatedCount = 0;

        foreach ($matchesData as $index => $matchData) {
            if (!isset($matchData['match_id'])) {
                continue;
            }

            $matchId = $matchData['match_id'];
            $matchDate = $matchData['match_date'] ?? null;
            $startTime = $matchData['start_time'] ?? null;
            $venueId = $matchData['venue_id'] ?? null;

            if (!$matchDate || !$startTime) {
                continue;
            }

            // Combine date and time
            $kickoffDateTime = $matchDate . ' ' . $startTime;

            try {
                $match = $tournament->matches()->find($matchId);

                if ($match) {
                    $match->update([
                        'kickoff_time' => Carbon::parse($kickoffDateTime),
                        'venue_id' => $venueId,
                        'status' => TournamentMatch::STATUS_SCHEDULED
                    ]);
                    $updatedCount++;
                }
            } catch (\Exception $e) {
                $errors[] = "Match {$matchId}: " . $e->getMessage();
            }
        }

        if (count($errors) > 0) {
            session()->flash('warning', "Updated {$updatedCount} matches. Errors: " . implode('. ', $errors));
        }

        $routePrefix = request()->is('super-admin/*') ? 'super-admin' : 'admin';
        return redirect()->route("{$routePrefix}.tournaments.schedule.index", $tournament->id)
            ->with('success', "Successfully updated {$updatedCount} match schedules.");
    }

    /**
     * Check scheduling constraints and show violations
     */
    public function checkConstraints(Tournament $tournament)
    {
        $routePrefix = $this->getRoutePrefix();

        $result = $this->scheduler->checkSchedulingConstraints($tournament);

        $violations = $result['violations'] ?? [];
        $isValid = $result['is_valid'] ?? true;

        $totalMatches = $tournament->matches()->count();
        $scheduledMatches = $tournament->matches()->whereNotNull('kickoff_time')->count();
        $venuesUsed = $tournament->matches()->whereNotNull('venue_id')->distinct('venue_id')->count();
        $daysUsed = $tournament->matches()->whereNotNull('kickoff_time')->count();
        $config = is_array($tournament->scheduling_config) ? $tournament->scheduling_config : [];

        $constraints = [
            'is_valid' => $isValid,
            'violations' => $violations,
            'total_matches' => $totalMatches,
            'scheduled_matches' => $scheduledMatches,
            'venues_used' => $venuesUsed,
            'days_used' => $daysUsed,
            'min_team_rest' => 'N/A'
        ];

        return view("{$routePrefix}.tournaments.schedule.constraints", compact(
            'tournament',
            'constraints',
            'config',
            'routePrefix'
        ));
    }

    /**
     * Clear all scheduled times (keep matches, clear dates)
     */
    public function clearSchedule(Tournament $tournament)
    {
        $routePrefix = $this->getRoutePrefix();

        $count = $tournament->matches()
            ->whereNotIn('status', [TournamentMatch::STATUS_COMPLETED, TournamentMatch::STATUS_CANCELLED])
            ->update([
                'kickoff_time' => null,
                'timezone' => null,
            ]);

        $routePrefix = request()->is('super-admin/*') ? 'super-admin' : 'admin';
        return redirect()->route("{$routePrefix}.tournaments.schedule.index", $tournament->id)
            ->with('success', "Cleared schedule for {$count} matches.");
    }

    /**
     * Delete all matches (for regeneration)
     */
    public function deleteMatches(Request $request, Tournament $tournament)
    {
        $routePrefix = $this->getRoutePrefix();

        // Check for completed matches
        $completedCount = $tournament->matches()
            ->where('status', TournamentMatch::STATUS_COMPLETED)
            ->count();

        if ($completedCount > 0) {
            return redirect()->back()
                ->with('error', "Cannot delete {$completedCount} completed matches. Delete them individually first.");
        }

        $count = $tournament->matches()->count();
        $tournament->matches()->delete();

        $routePrefix = request()->is('super-admin/*') ? 'super-admin' : 'admin';
        return redirect()->route("{$routePrefix}.tournaments.schedule.index", $tournament->id)
            ->with('success', "Deleted {$count} matches. You can now generate a new schedule.");
    }

    /**
     * Get available time slots via AJAX
     */
    public function getAvailableSlots(Request $request, Tournament $tournament)
    {
        $venueId = $request->get('venue_id');

        $slots = $this->scheduler->getAvailableTimeSlots($tournament, $venueId)
            ->filter(fn($slot) => $slot['available'])
            ->groupBy('date');

        return response()->json([
            'slots' => $slots->toArray(),
            'count' => $slots->flatten(1)->count(),
        ]);
    }

    /**
     * Validate a specific time slot
     */
    public function validateSlot(Request $request, Tournament $tournament)
    {
        $matchId = $request->get('match_id');
        $kickoffTime = $request->get('kickoff_time');
        $venueId = $request->get('venue_id');

        if (!$kickoffTime) {
            return response()->json(['valid' => false, 'message' => 'Time is required']);
        }

        $dateTime = Carbon::parse($kickoffTime);

        // Check venue availability
        if ($venueId && !$this->scheduler->isVenueAvailableAt($tournament, $venueId, $dateTime)) {
            return response()->json([
                'valid' => false,
                'message' => 'Venue is not available at this time'
            ]);
        }

        // If match ID provided, check teams
        if ($matchId) {
            $match = $tournament->matches()->find($matchId);

            if ($match) {
                if ($match->home_team_id && !$this->scheduler->isTeamAvailableAt($tournament, $match->home_team_id, $dateTime)) {
                    return response()->json([
                        'valid' => false,
                        'message' => 'Home team is not available at this time'
                    ]);
                }

                if ($match->away_team_id && !$this->scheduler->isTeamAvailableAt($tournament, $match->away_team_id, $dateTime)) {
                    return response()->json([
                        'valid' => false,
                        'message' => 'Away team is not available at this time'
                    ]);
                }
            }
        }

        return response()->json(['valid' => true, 'message' => 'Time slot is available']);
    }
}
