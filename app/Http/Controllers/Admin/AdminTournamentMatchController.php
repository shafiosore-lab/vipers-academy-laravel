<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TournamentMatch;
use App\Models\TournamentPool;
use App\Models\TournamentVenue;
use App\Services\MatchScheduler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AdminTournamentMatchController extends Controller
{
    protected $scheduler;

    public function __construct(MatchScheduler $scheduler)
    {
        $this->scheduler = $scheduler;
    }

    /**
     * Display matches for a tournament with filtering.
     */
    public function index(Request $request, Tournament $tournament)
    {
        $query = $tournament->matches()->with(['homeTeam.team', 'awayTeam.team', 'pool', 'venueModel']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by match type
        if ($request->has('match_type') && $request->match_type) {
            $query->where('match_type', $request->match_type);
        }

        // Filter by pool
        if ($request->has('pool_id') && $request->pool_id) {
            $query->where('pool_id', $request->pool_id);
        }

        // Filter by match day
        if ($request->has('match_day') && $request->match_day) {
            $query->where('match_day', $request->match_day);
        }

        // Filter by venue
        if ($request->has('venue_id') && $request->venue_id) {
            $query->where('venue_id', $request->venue_id);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('kickoff_time', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('kickoff_time', '<=', $request->date_to);
        }

        $matches = $query->orderBy('match_day')->orderBy('kickoff_time')->paginate(15);

        $matchDays = $tournament->matches()->distinct()->pluck('match_day')->filter()->sort();
        $pools = $tournament->pools()->ordered()->get();
        $venues = $tournament->venues()->active()->ordered()->get();

        return view('admin.tournaments.matches.index', compact('tournament', 'matches', 'matchDays', 'pools', 'venues'));
    }

    /**
     * Show the form for creating a match with match type selection.
     */
    public function create(Request $request, Tournament $tournament)
    {
        $matchType = $request->get('type', 'tournament');
        $approvedTeams = $tournament->approvedTeams()->with('team')->ordered()->get();
        $pools = $tournament->pools()->ordered()->get();
        $venues = $tournament->venues()->active()->ordered()->get();

        // Get the tournament's venues or create default
        if ($venues->count() === 0 && $tournament->venue) {
            // Use tournament's main venue
            $venues = collect([
                (object)['id' => null, 'name' => $tournament->venue, 'city' => null]
            ]);
        }

        return view('admin.tournaments.matches.create', compact('tournament', 'approvedTeams', 'pools', 'venues', 'matchType'));
    }

    /**
     * Create a new match with all configuration options.
     */
    public function store(Request $request, Tournament $tournament)
    {
        $validator = Validator::make($request->all(), [
            'home_team_id' => 'required|exists:tournament_teams,id',
            'away_team_id' => 'required|different:home_team_id|exists:tournament_teams,id',
            'match_type' => 'required|in:tournament,league,friendly,knockout,group_stage,exhibition',
            'pool_id' => 'nullable|exists:tournament_pools,id',
            'venue_id' => 'nullable|exists:tournament_venues,id',
            'venue' => 'nullable|string|max:255',
            'kickoff_time' => 'nullable|date',
            'timezone' => 'nullable|string|max:50',
            'match_day' => 'nullable|integer|min:1',
            'round' => 'nullable|integer|min:1',
            'scheduled_day' => 'nullable|integer|min:1',
            'match_format' => 'nullable|array',
            'scoring_rules' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify both teams are in this tournament
        $homeTeam = TournamentTeam::where('id', $request->home_team_id)
            ->where('tournament_id', $tournament->id)->first();
        $awayTeam = TournamentTeam::where('id', $request->away_team_id)
            ->where('tournament_id', $tournament->id)->first();

        if (!$homeTeam || !$awayTeam) {
            return redirect()->back()
                ->with('error', 'Invalid team selection. Both teams must be registered in this tournament.');
        }

        // Check if match already exists
        $existingMatch = TournamentMatch::where('tournament_id', $tournament->id)
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('home_team_id', $request->home_team_id)
                      ->where('away_team_id', $request->away_team_id);
                })->orWhere(function ($q) use ($request) {
                    $q->where('home_team_id', $request->away_team_id)
                      ->where('away_team_id', $request->home_team_id);
                });
            })->first();

        if ($existingMatch) {
            return redirect()->back()
                ->with('error', 'A match between these teams already exists.');
        }

        // Get venue name if venue_id provided
        $venueName = $request->venue;
        if ($request->venue_id) {
            $venue = TournamentVenue::find($request->venue_id);
            $venueName = $venue?->name ?? $request->venue;
        }

        // Get match format and scoring rules
        $matchFormat = $request->match_format ?? TournamentMatch::getDefaultFormat($request->match_type);
        $scoringRules = $request->scoring_rules ?? TournamentMatch::getDefaultScoringRules();

        $match = TournamentMatch::create([
            'tournament_id' => $tournament->id,
            'home_team_id' => $request->home_team_id,
            'away_team_id' => $request->away_team_id,
            'pool_id' => $request->pool_id,
            'venue_id' => $request->venue_id,
            'venue' => $venueName,
            'match_type' => $request->match_type,
            'kickoff_time' => $request->kickoff_time,
            'timezone' => $request->timezone ?? config('app.timezone', 'UTC'),
            'match_day' => $request->match_day,
            'round' => $request->round,
            'scheduled_day' => $request->scheduled_day,
            'match_format' => $matchFormat,
            'scoring_rules' => $scoringRules,
            'notes' => $request->notes,
            'status' => TournamentMatch::STATUS_SCHEDULED,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.tournaments.matches.show', [$tournament->id, $match->id])
            ->with('success', 'Match created successfully.');
    }

    /**
     * Display match details.
     */
    public function show(Tournament $tournament, TournamentMatch $match)
    {
        $match->load(['homeTeam.team', 'awayTeam.team', 'tournament', 'pool', 'venueModel', 'canceller']);

        // Check for scheduling conflicts
        $conflicts = $this->scheduler->getMatchConflicts($match);

        return view('admin.tournaments.matches.show', compact('tournament', 'match', 'conflicts'));
    }

    /**
     * Show the form for editing a match.
     */
    public function edit(Tournament $tournament, TournamentMatch $match)
    {
        $approvedTeams = $tournament->approvedTeams()->with('team')->ordered()->get();
        $pools = $tournament->pools()->ordered()->get();
        $venues = $tournament->venues()->active()->ordered()->get();

        return view('admin.tournaments.matches.edit', compact('tournament', 'match', 'approvedTeams', 'pools', 'venues'));
    }

    /**
     * Update a match.
     */
    public function update(Request $request, Tournament $tournament, TournamentMatch $match)
    {
        if (!$match->canEdit()) {
            return redirect()->back()
                ->with('error', 'Cannot edit a completed or cancelled match.');
        }

        $validator = Validator::make($request->all(), [
            'home_team_id' => 'required|exists:tournament_teams,id',
            'away_team_id' => 'required|different:home_team_id|exists:tournament_teams,id',
            'match_type' => 'required|in:tournament,league,friendly,knockout,group_stage,exhibition',
            'pool_id' => 'nullable|exists:tournament_pools,id',
            'venue_id' => 'nullable|exists:tournament_venues,id',
            'venue' => 'nullable|string|max:255',
            'kickoff_time' => 'nullable|date',
            'timezone' => 'nullable|string|max:50',
            'match_day' => 'nullable|integer|min:1',
            'round' => 'nullable|integer|min:1',
            'scheduled_day' => 'nullable|integer|min:1',
            'match_format' => 'nullable|array',
            'scoring_rules' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get venue name if venue_id provided
        $venueName = $request->venue;
        if ($request->venue_id) {
            $venue = TournamentVenue::find($request->venue_id);
            $venueName = $venue?->name ?? $request->venue;
        }

        $match->update([
            'home_team_id' => $request->home_team_id,
            'away_team_id' => $request->away_team_id,
            'pool_id' => $request->pool_id,
            'venue_id' => $request->venue_id,
            'venue' => $venueName,
            'match_type' => $request->match_type,
            'kickoff_time' => $request->kickoff_time,
            'timezone' => $request->timezone,
            'match_day' => $request->match_day,
            'round' => $request->round,
            'scheduled_day' => $request->scheduled_day,
            'match_format' => $request->match_format ?? TournamentMatch::getDefaultFormat($request->match_type),
            'scoring_rules' => $request->scoring_rules ?? TournamentMatch::getDefaultScoringRules(),
            'notes' => $request->notes,
        ]);

        return redirect()->back()
            ->with('success', 'Match updated successfully.');
    }

    /**
     * Delete a match.
     */
    public function destroy(Tournament $tournament, TournamentMatch $match)
    {
        if ($match->isCompleted()) {
            return redirect()->back()
                ->with('error', 'Cannot delete a completed match.');
        }

        $match->delete();

        return redirect()->route('admin.tournaments.matches.index', $tournament->id)
            ->with('success', 'Match deleted successfully.');
    }

    /**
     * Record match result.
     */
    public function recordResult(Request $request, Tournament $tournament, TournamentMatch $match)
    {
        if ($match->isCompleted()) {
            return redirect()->back()
                ->with('error', 'Match result already recorded.');
        }

        $validator = Validator::make($request->all(), [
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'after_overtime' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $match->recordResult($request->home_score, $request->away_score, $request->boolean('after_overtime'));

        return redirect()->back()
            ->with('success', 'Match result recorded. Standings updated.');
    }

    /**
     * Start a match.
     */
    public function startMatch(Tournament $tournament, TournamentMatch $match)
    {
        if (!$match->isScheduled()) {
            return redirect()->back()
                ->with('error', 'Match cannot be started in its current status.');
        }

        $match->startMatch();

        return redirect()->back()
            ->with('success', 'Match started.');
    }

    /**
     * Postpone a match.
     */
    public function postpone(Request $request, Tournament $tournament, TournamentMatch $match)
    {
        if ($match->isCompleted() || $match->isCancelled()) {
            return redirect()->back()
                ->with('error', 'Cannot postpone a completed or cancelled match.');
        }

        $newDateTime = null;
        if ($request->has('new_kickoff_time') && $request->new_kickoff_time) {
            $newDateTime = Carbon::parse($request->new_kickoff_time);
        }

        $match->postponeMatch($newDateTime);

        return redirect()->back()
            ->with('success', 'Match postponed.');
    }

    /**
     * Cancel a match with reason.
     */
    public function cancel(Request $request, Tournament $tournament, TournamentMatch $match)
    {
        if ($match->isCompleted()) {
            return redirect()->back()
                ->with('error', 'Cannot cancel a completed match.');
        }

        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $match->cancelMatch($request->cancellation_reason, Auth::id());

        return redirect()->back()
            ->with('success', 'Match cancelled.');
    }

    /**
     * Reschedule a match.
     */
    public function reschedule(Request $request, Tournament $tournament, TournamentMatch $match)
    {
        if (!$match->canEdit()) {
            return redirect()->back()
                ->with('error', 'Cannot reschedule this match.');
        }

        $validator = Validator::make($request->all(), [
            'kickoff_time' => 'required|date|after:now',
            'timezone' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $timezone = $request->timezone ?? config('app.timezone', 'UTC');
        $match->reschedule(Carbon::parse($request->kickoff_time), $timezone);

        return redirect()->back()
            ->with('success', 'Match rescheduled successfully.');
    }

    /**
     * Generate league fixtures automatically.
     */
    public function generateFixtures(Tournament $tournament)
    {
        // Check if fixtures already exist
        if ($tournament->matches()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Fixtures already exist. Delete them first to regenerate.');
        }

        // Check minimum teams
        if ($tournament->approvedTeams()->count() < 2) {
            return redirect()->back()
                ->with('error', 'At least 2 approved teams required to generate fixtures.');
        }

        TournamentMatch::generateFixtures($tournament);

        return redirect()->route('admin.tournaments.matches.index', $tournament->id)
            ->with('success', 'Fixtures generated successfully!');
    }

    /**
     * Generate league schedule with intelligent scheduling.
     */
    public function generateLeagueSchedule(Request $request, Tournament $tournament)
    {
        if ($tournament->matches()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Matches already exist. Delete them first to regenerate.');
        }

        if ($tournament->approvedTeams()->count() < 2) {
            return redirect()->back()
                ->with('error', 'At least 2 approved teams required.');
        }

        $venueId = $request->get('venue_id');
        $startDate = $request->get('start_date')
            ? Carbon::parse($request->start_date)
            : ($tournament->start_date ?? Carbon::now()->addWeek());

        $matches = $this->scheduler->generateLeagueSchedule($tournament, $venueId);

        return redirect()->route('admin.tournaments.matches.index', $tournament->id)
            ->with('success', 'Generated ' . count($matches) . ' league matches!');
    }

    /**
     * Generate knockout bracket.
     */
    public function generateKnockoutBracket(Request $request, Tournament $tournament)
    {
        if ($tournament->matches()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Matches already exist. Delete them first.');
        }

        if ($tournament->approvedTeams()->count() < 2) {
            return redirect()->back()
                ->with('error', 'At least 2 approved teams required.');
        }

        $rounds = $request->get('rounds');
        $venueId = $request->get('venue_id');

        $matches = $this->scheduler->generateKnockoutBracket($tournament, $rounds, $venueId);

        return redirect()->route('admin.tournaments.matches.index', $tournament->id)
            ->with('success', 'Generated knockout bracket with ' . count($matches) . ' matches!');
    }

    /**
     * Generate group stage schedule.
     */
    public function generateGroupStageSchedule(Request $request, Tournament $tournament)
    {
        if ($tournament->pools()->count() === 0) {
            return redirect()->back()
                ->with('error', 'Please create pools first before generating group stage schedule.');
        }

        $teamsPerGroup = $request->get('teams_per_group', 4);
        $venueId = $request->get('venue_id');

        $matches = $this->scheduler->generateGroupStageSchedule($tournament, $teamsPerGroup, $venueId);

        return redirect()->route('admin.tournaments.matches.index', $tournament->id)
            ->with('success', 'Generated ' . count($matches) . ' group stage matches!');
    }

    /**
     * Delete all fixtures.
     */
    public function deleteFixtures(Tournament $tournament)
    {
        $count = $tournament->matches()->count();

        if ($count === 0) {
            return redirect()->back()
                ->with('error', 'No fixtures to delete.');
        }

        // Check for completed matches
        $completedCount = $tournament->matches()->completed()->count();
        if ($completedCount > 0) {
            return redirect()->back()
                ->with('error', "Cannot delete {$completedCount} completed matches. Delete them individually first.");
        }

        $tournament->matches()->delete();

        return redirect()->back()
            ->with('success', "Deleted {$count} fixtures.");
    }

    /**
     * Check for scheduling conflicts.
     */
    public function checkConflicts(Tournament $tournament)
    {
        $conflicts = $this->scheduler->getScheduleConflicts($tournament);

        if (empty($conflicts)) {
            return redirect()->back()
                ->with('success', 'No scheduling conflicts found!');
        }

        return view('admin.tournaments.matches.conflicts', compact('tournament', 'conflicts'));
    }

    /**
     * Get upcoming fixtures for the tournament dashboard.
     */
    public function getUpcomingFixtures(Tournament $tournament, int $limit = 5)
    {
        return $tournament->matches()
            ->with(['homeTeam.team', 'awayTeam.team', 'venueModel'])
            ->upcoming()
            ->limit($limit)
            ->get();
    }

    /**
     * Get active (in-progress) matches.
     */
    public function getActiveMatches(Tournament $tournament)
    {
        return $tournament->matches()
            ->with(['homeTeam.team', 'awayTeam.team'])
            ->inProgress()
            ->get();
    }

    /**
     * Get match history with filters.
     */
    public function getHistory(Request $request, Tournament $tournament)
    {
        $query = $tournament->matches()
            ->with(['homeTeam.team', 'awayTeam.team', 'venueModel'])
            ->completed();

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('kickoff_time', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('kickoff_time', '<=', $request->date_to);
        }
        if ($request->has('team_id') && $request->team_id) {
            $query->where(function ($q) use ($request) {
                $q->where('home_team_id', $request->team_id)
                  ->orWhere('away_team_id', $request->team_id);
            });
        }
        if ($request->has('venue_id') && $request->venue_id) {
            $query->where('venue_id', $request->venue_id);
        }

        return $query->orderBy('kickoff_time', 'desc')->paginate(15);
    }
}
