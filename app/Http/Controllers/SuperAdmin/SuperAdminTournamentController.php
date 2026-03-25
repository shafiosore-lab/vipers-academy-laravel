<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TournamentSquad;
use App\Models\TournamentMatch;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SuperAdminTournamentController extends Controller
{
    /**
     * SuperAdminTournamentController constructor.
     */
    public function __construct()
    {
        // Apply authorization check to all methods
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            $permissionService = new PermissionService();

            if (!$permissionService->hasRoleOrHigher($user, 'super-admin')) {
                abort(403, 'Unauthorized access to Super Admin tournament management');
            }

            return $next($request);
        });
    }

    /**
     * Display tournament overview dashboard.
     */
    public function overview()
    {
        // Get statistics
        $stats = [
            'total_tournaments' => Tournament::count(),
            'active_tournaments' => Tournament::whereIn('status', ['open', 'ongoing'])->count(),
            'draft_tournaments' => Tournament::where('status', 'draft')->count(),
            'completed_tournaments' => Tournament::where('status', 'completed')->count(),
            'cancelled_tournaments' => Tournament::where('status', 'cancelled')->count(),
            'total_teams' => TournamentTeam::count(),
            'total_players' => TournamentSquad::count(),
            'total_matches' => TournamentMatch::count(),
        ];

        // Get recent tournaments
        $recentTournaments = Tournament::with('organization')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get ongoing tournaments
        $ongoingTournaments = Tournament::with('organization')
            ->where('status', 'ongoing')
            ->orderBy('start_date', 'desc')
            ->take(5)
            ->get();

        // Get upcoming tournaments (open for registration)
        $upcomingTournaments = Tournament::with('organization')
            ->where('status', 'open')
            ->orderBy('registration_deadline', 'asc')
            ->take(5)
            ->get();

        // Get tournaments by organization
        $tournamentsByOrg = Tournament::with('organization')
            ->select('organization_id', \DB::raw('count(*) as count'))
            ->groupBy('organization_id')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        // Get recent activity (recently created/updated tournaments)
        $recentActivity = Tournament::with('organization')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        return view('super-admin.tournaments.overview', compact(
            'stats',
            'recentTournaments',
            'ongoingTournaments',
            'upcomingTournaments',
            'tournamentsByOrg',
            'recentActivity'
        ));
    }

    /**
     * Display a listing of all tournaments across all organizations.
     */
    public function index(Request $request)
    {
        $query = Tournament::with(['organization']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by organization
        if ($request->has('organization_id') && $request->organization_id) {
            $query->where('organization_id', $request->organization_id);
        }

        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('season', 'like', '%' . $request->search . '%');
            });
        }

        $tournaments = $query->orderBy('created_at', 'desc')->paginate(20);

        $organizations = Organization::where('type', 'tournament')
            ->orWhere('type', 'club')
            ->orWhere('type', 'academy')
            ->orderBy('name')
            ->get();

        return view('super-admin.tournaments.index', compact('tournaments', 'organizations'));
    }

    /**
     * Show the form for creating a new tournament.
     */
    public function create()
    {
        $organizations = Organization::where('type', 'tournament')
            ->orWhere('type', 'club')
            ->orWhere('type', 'academy')
            ->active()
            ->orderBy('name')
            ->get();

        return view('super-admin.tournaments.create', compact('organizations'));
    }

    /**
     * Store a newly created tournament.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'organization_id' => 'required|exists:organizations,id',
            'season' => 'nullable|string|max:50',
            'competition_format' => 'nullable|in:league,round_robin,league_cup,knockout,knockout_plus,groups_knockout,double_elimination',
            'estimated_matches' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'registration_deadline' => 'nullable|date',
            'squad_limit' => 'nullable|integer|min:5|max:50',
            'min_players' => 'nullable|integer|min:5|max:20',
            'max_teams' => 'nullable|integer|min:2',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'venue' => 'nullable|string|max:255',
            'rules' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $tournament = Tournament::create([
            'organization_id' => $request->organization_id,
            'name' => $request->name,
            'slug' => Tournament::generateSlug($request->name),
            'season' => $request->season,
            'competition_format' => $request->competition_format,
            'estimated_matches' => $request->estimated_matches,
            'description' => $request->description,
            'registration_deadline' => $request->registration_deadline,
            'squad_limit' => $request->squad_limit ?? 25,
            'min_players' => $request->min_players ?? 11,
            'max_teams' => $request->max_teams,
            'status' => $request->status ?? Tournament::STATUS_DRAFT,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'venue' => $request->venue,
            'rules' => $request->rules,
            'is_public' => $request->is_public ?? true,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('super-admin.tournaments.show', $tournament->id)
            ->with('success', 'Tournament created successfully.');
    }

    /**
     * Display tournament details with all teams and players.
     */
    public function show(Tournament $tournament)
    {
        $tournament->load([
            'organization',
            'teams.team',
            'teams.squads.player',
            'matches.homeTeam.team',
            'matches.awayTeam.team',
            'standings.team.team',
            'creator'
        ]);

        // Calculate statistics
        $stats = [
            'total_teams' => $tournament->teams()->count(),
            'approved_teams' => $tournament->teams()->approved()->count(),
            'pending_teams' => $tournament->teams()->pending()->count(),
            'rejected_teams' => $tournament->teams()->rejected()->count(),
            'total_players' => $tournament->squads()->count(),
            'verified_players' => $tournament->squads()->verified()->count(),
            'pending_players' => $tournament->squads()->pending()->count(),
            'total_matches' => $tournament->matches()->count(),
            'completed_matches' => $tournament->matches()->where('status', 'completed')->count(),
        ];

        return view('super-admin.tournaments.show', compact('tournament', 'stats'));
    }

    /**
     * Show the form for editing a tournament.
     */
    public function edit(Tournament $tournament)
    {
        $organizations = Organization::where('type', 'tournament')
            ->orWhere('type', 'club')
            ->orWhere('type', 'academy')
            ->active()
            ->orderBy('name')
            ->get();

        return view('super-admin.tournaments.edit', compact('tournament', 'organizations'));
    }

    /**
     * Update tournament details.
     */
    public function update(Request $request, Tournament $tournament)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'organization_id' => 'required|exists:organizations,id',
            'season' => 'nullable|string|max:50',
            'competition_format' => 'nullable|in:league,round_robin,league_cup,knockout,knockout_plus,groups_knockout,double_elimination',
            'estimated_matches' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'registration_deadline' => 'nullable|date',
            'squad_limit' => 'nullable|integer|min:5|max:50',
            'min_players' => 'nullable|integer|min:5|max:20',
            'max_teams' => 'nullable|integer|min:2',
            'status' => 'required|in:draft,open,closed,ongoing,completed,cancelled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'venue' => 'nullable|string|max:255',
            'rules' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $tournament->update($request->all());

        return redirect()->route('super-admin.tournaments.show', $tournament->id)
            ->with('success', 'Tournament updated successfully.');
    }

    /**
     * Remove a tournament.
     */
    public function destroy(Tournament $tournament)
    {
        // Check if tournament has matches
        if ($tournament->matches()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete tournament with matches. Please delete matches first.');
        }

        $tournament->delete();

        return redirect()->route('super-admin.tournaments.index')
            ->with('success', 'Tournament deleted successfully.');
    }

    /**
     * View all teams registered in a tournament.
     */
    public function teams(Request $request, Tournament $tournament)
    {
        $query = $tournament->teams()->with('team');

        // Filter by approval status
        if ($request->has('status') && $request->status) {
            $query->where('approval_status', $request->status);
        }

        $teams = $query->orderBy('registration_date', 'desc')->paginate(20);

        return view('super-admin.tournaments.teams', compact('tournament', 'teams'));
    }

    /**
     * View all players in a tournament across all teams.
     */
    public function players(Request $request, Tournament $tournament)
    {
        $query = TournamentSquad::whereHas('tournamentTeam', function ($q) use ($tournament) {
            $q->where('tournament_id', $tournament->id);
        })->with(['player', 'tournamentTeam.team']);

        // Filter by verification status
        if ($request->has('status') && $request->status) {
            $query->where('verification_status', $request->status);
        }

        // Filter by team
        if ($request->has('team_id') && $request->team_id) {
            $query->whereHas('tournamentTeam', function ($q) use ($request) {
                $q->where('team_id', $request->team_id);
            });
        }

        $players = $query->orderBy('registration_date', 'desc')->paginate(20);

        $teams = $tournament->teams()->with('team')->get();

        return view('super-admin.tournaments.players', compact('tournament', 'players', 'teams'));
    }

    /**
     * Approve a team registration.
     */
    public function approveTeam(Tournament $tournament, TournamentTeam $team)
    {
        $user = Auth::user();
        $team->approve($user);

        return redirect()->back()
            ->with('success', 'Team approved successfully.');
    }

    /**
     * Reject a team registration.
     */
    public function rejectTeam(Request $request, Tournament $tournament, TournamentTeam $team)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $team->reject($user, $request->rejection_reason);

        return redirect()->back()
            ->with('success', 'Team registration rejected.');
    }

    /**
     * View players for a specific team grouped by position.
     */
    public function teamPlayers(Tournament $tournament, TournamentTeam $team)
    {
        $team->load('team', 'squads.player');
        $squads = $team->squads()->with('player')->get();

        // Group players by position
        $playersByPosition = $squads->groupBy('position');

        // Organize positions in order
        $positionOrder = ['Goalkeeper', 'Defender', 'Midfielder', 'Striker'];
        $groupedPlayers = [];

        foreach ($positionOrder as $position) {
            if ($playersByPosition->has($position)) {
                $groupedPlayers[$position] = $playersByPosition[$position];
            }
        }

        // Add any other positions not in the standard list
        foreach ($playersByPosition as $position => $players) {
            if (!in_array($position, $positionOrder)) {
                $groupedPlayers[$position] = $players;
            }
        }

        return view('super-admin.tournaments.team-players', compact('tournament', 'team', 'groupedPlayers'));
    }

    /**
     * Approve a player registration.
     */
    public function approvePlayer(Tournament $tournament, TournamentTeam $team, TournamentSquad $squad)
    {
        $squad->update([
            'verification_status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Player approved successfully.');
    }

    /**
     * Reject a player registration.
     */
    public function rejectPlayer(Request $request, Tournament $tournament, TournamentTeam $team, TournamentSquad $squad)
    {
        $validator = Validator::make($request->all(), [
            'verification_notes' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $squad->update([
            'verification_status' => 'rejected',
            'verification_notes' => $request->verification_notes,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Player registration rejected.');
    }

    /**
     * Remove a player from the team.
     */
    public function removePlayer(Tournament $tournament, TournamentTeam $team, TournamentSquad $squad)
    {
        $squad->delete();

        return redirect()->back()->with('success', 'Player removed from team.');
    }

    /**
     * View tournament matches and brackets.
     */
    public function matches(Tournament $tournament)
    {
        $tournament->load([
            'matches.homeTeam.team',
            'matches.awayTeam.team',
        ]);

        $matches = $tournament->matches()
            ->orderBy('match_day')
            ->orderBy('kickoff_time')
            ->paginate(20);

        return view('super-admin.tournaments.matches', compact('tournament', 'matches'));
    }

    /**
     * View tournament standings.
     */
    public function standings(Tournament $tournament)
    {
        $tournament->load(['standings.team.team', 'teams.team']);

        return view('super-admin.tournaments.standings', compact('tournament'));
    }

    /**
     * View tournament statistics and reports.
     */
    public function statistics(Tournament $tournament)
    {
        $tournament->load([
            'organization',
            'teams.team',
            'teams.squads.player',
            'matches.homeTeam.team',
            'matches.awayTeam.team',
        ]);

        // Calculate detailed statistics
        $stats = [
            'overview' => [
                'total_teams' => $tournament->teams()->count(),
                'approved_teams' => $tournament->teams()->approved()->count(),
                'pending_teams' => $tournament->teams()->pending()->count(),
                'rejected_teams' => $tournament->teams()->rejected()->count(),
                'total_players' => $tournament->squads()->count(),
                'verified_players' => $tournament->squads()->verified()->count(),
                'pending_players' => $tournament->squads()->pending()->count(),
            ],
            'matches' => [
                'total' => $tournament->matches()->count(),
                'completed' => $tournament->matches()->where('status', 'completed')->count(),
                'scheduled' => $tournament->matches()->where('status', 'scheduled')->count(),
                'in_progress' => $tournament->matches()->where('status', 'in_progress')->count(),
                'postponed' => $tournament->matches()->where('status', 'postponed')->count(),
                'cancelled' => $tournament->matches()->where('status', 'cancelled')->count(),
            ],
            'goals' => [
                'total' => $tournament->matches()->sum('home_score') + $tournament->matches()->sum('away_score'),
                'home_avg' => $tournament->matches()->where('status', 'completed')->count() > 0
                    ? round($tournament->matches()->avg('home_score'), 2) : 0,
                'away_avg' => $tournament->matches()->where('status', 'completed')->count() > 0
                    ? round($tournament->matches()->avg('away_score'), 2) : 0,
            ],
        ];

        return view('super-admin.tournaments.statistics', compact('tournament', 'stats'));
    }

    /**
     * Open tournament registration.
     */
    public function openRegistration(Tournament $tournament)
    {
        if (!in_array($tournament->status, [Tournament::STATUS_DRAFT, Tournament::STATUS_CLOSED])) {
            return redirect()->back()
                ->with('error', 'Tournament cannot be opened for registration in its current status.');
        }

        $tournament->update(['status' => Tournament::STATUS_OPEN]);

        return redirect()->back()
            ->with('success', 'Tournament is now open for registration.');
    }

    /**
     * Close tournament registration.
     */
    public function closeRegistration(Tournament $tournament)
    {
        if ($tournament->status !== Tournament::STATUS_OPEN) {
            return redirect()->back()
                ->with('error', 'Tournament registration is not open.');
        }

        $tournament->update(['status' => Tournament::STATUS_CLOSED]);

        return redirect()->back()
            ->with('success', 'Tournament registration is now closed.');
    }

    /**
     * Start the tournament.
     */
    public function startTournament(Tournament $tournament)
    {
        if ($tournament->status !== Tournament::STATUS_CLOSED) {
            return redirect()->back()
                ->with('error', 'Tournament must be closed before starting.');
        }

        $approvedTeams = $tournament->approvedTeams()->count();

        if ($approvedTeams < 2) {
            return redirect()->back()
                ->with('error', 'At least 2 teams must be approved to start the tournament.');
        }

        $tournament->update(['status' => Tournament::STATUS_ONGOING]);
        $tournament->lockSquads();

        return redirect()->back()
            ->with('success', 'Tournament has started! Squads have been locked.');
    }

    /**
     * Complete the tournament.
     */
    public function completeTournament(Tournament $tournament)
    {
        if ($tournament->status !== Tournament::STATUS_ONGOING) {
            return redirect()->back()
                ->with('error', 'Tournament must be ongoing to complete.');
        }

        $tournament->update(['status' => Tournament::STATUS_COMPLETED]);
        $tournament->calculateStandings();

        return redirect()->back()
            ->with('success', 'Tournament has been completed! Standings have been calculated.');
    }

    /**
     * Cancel the tournament.
     */
    public function cancelTournament(Tournament $tournament)
    {
        $tournament->update(['status' => Tournament::STATUS_CANCELLED]);

        return redirect()->back()
            ->with('success', 'Tournament has been cancelled.');
    }

    /**
     * Generate fixtures for the tournament.
     */
    public function generateFixtures(Tournament $tournament)
    {
        if ($tournament->status !== Tournament::STATUS_ONGOING && $tournament->status !== Tournament::STATUS_CLOSED) {
            return redirect()->back()
                ->with('error', 'Tournament must be closed or ongoing to generate fixtures.');
        }

        if ($tournament->matches()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Fixtures already exist. Please delete them first.');
        }

        $approvedTeams = $tournament->approvedTeams()->count();

        if ($approvedTeams < 2) {
            return redirect()->back()
                ->with('error', 'At least 2 teams must be approved to generate fixtures.');
        }

        TournamentMatch::generateFixtures($tournament);

        return redirect()->back()
            ->with('success', 'Fixtures generated successfully!');
    }

    /**
     * Recalculate standings.
     */
    public function recalculateStandings(Tournament $tournament)
    {
        $tournament->calculateStandings();

        return redirect()->back()
            ->with('success', 'Standings recalculated successfully.');
    }

    /**
     * Force unlock squads (Super Admin only).
     */
    public function unlockSquads(Tournament $tournament)
    {
        $tournament->unlockSquads();

        return redirect()->back()
            ->with('success', 'Squads have been unlocked.');
    }

    /**
     * Toggle tournament visibility (public/private).
     */
    public function toggleVisibility(Tournament $tournament)
    {
        $tournament->update(['is_public' => !$tournament->is_public]);

        $message = $tournament->is_public
            ? 'Tournament is now visible to the public.'
            : 'Tournament is now hidden from the public.';

        return redirect()->back()
            ->with('success', $message);
    }

    /**
     * Archive the tournament.
     */
    public function archiveTournament(Tournament $tournament)
    {
        if ($tournament->status !== Tournament::STATUS_COMPLETED && $tournament->status !== Tournament::STATUS_CANCELLED) {
            return redirect()->back()
                ->with('error', 'Only completed or cancelled tournaments can be archived.');
        }

        $tournament->update(['is_archived' => true]);

        return redirect()->back()
            ->with('success', 'Tournament has been archived.');
    }

    /**
     * Restore an archived tournament.
     */
    public function restoreTournament(Tournament $tournament)
    {
        $tournament->update(['is_archived' => false]);

        return redirect()->back()
            ->with('success', 'Tournament has been restored.');
    }

    /**
     * Get tournament quick stats for dashboard.
     */
    public function getQuickStats(Tournament $tournament)
    {
        $stats = [
            'total_teams' => $tournament->teams()->count(),
            'approved_teams' => $tournament->teams()->approved()->count(),
            'pending_teams' => $tournament->teams()->pending()->count(),
            'total_matches' => $tournament->matches()->count(),
            'completed_matches' => $tournament->matches()->where('status', 'completed')->count(),
            'pending_matches' => $tournament->matches()->where('status', 'pending')->count(),
            'total_players' => $tournament->squads()->count(),
            'active_players' => $tournament->squads()->where('status', 'active')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get tournament status summary.
     */
    public function getStatusSummary(Tournament $tournament)
    {
        $summary = [
            'name' => $tournament->name,
            'status' => $tournament->status,
            'status_label' => $tournament->getStatusLabel(),
            'start_date' => $tournament->start_date,
            'end_date' => $tournament->end_date,
            'registration_deadline' => $tournament->registration_deadline,
            'is_public' => $tournament->is_public,
            'teams_count' => $tournament->teams()->count(),
            'matches_count' => $tournament->matches()->count(),
            'squads_locked' => $tournament->squads_locked,
        ];

        return response()->json($summary);
    }

    /**
     * Bulk operations on tournament teams.
     */
    public function bulkTeamOperations(Request $request, Tournament $tournament)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:approve,reject,delete',
            'team_ids' => 'required|array',
            'team_ids.*' => 'exists:tournament_teams,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $teams = TournamentTeam::whereIn('id', $request->team_ids)
            ->where('tournament_id', $tournament->id)
            ->get();

        $count = 0;

        foreach ($teams as $team) {
            switch ($request->action) {
                case 'approve':
                    if ($team->status !== 'approved') {
                        $team->approve();
                        $count++;
                    }
                    break;
                case 'reject':
                    if ($team->status !== 'rejected') {
                        $team->reject();
                        $count++;
                    }
                    break;
                case 'delete':
                    if ($team->status === 'pending') {
                        $team->delete();
                        $count++;
                    }
                    break;
            }
        }

        return redirect()->back()
            ->with('success', "{$count} teams {$request->action}ed successfully.");
    }

    /**
     * Bulk operations on tournament matches.
     */
    public function bulkMatchOperations(Request $request, Tournament $tournament)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:postpone,cancel,delete',
            'match_ids' => 'required|array',
            'match_ids.*' => 'exists:tournament_matches,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $matches = TournamentMatch::whereIn('id', $request->match_ids)
            ->where('tournament_id', $tournament->id)
            ->get();

        $count = 0;

        foreach ($matches as $match) {
            switch ($request->action) {
                case 'postpone':
                    if ($match->status !== 'postponed') {
                        $match->postpone();
                        $count++;
                    }
                    break;
                case 'cancel':
                    if ($match->status !== 'cancelled') {
                        $match->cancel();
                        $count++;
                    }
                    break;
                case 'delete':
                    if ($match->status === 'pending') {
                        $match->delete();
                        $count++;
                    }
                    break;
            }
        }

        return redirect()->back()
            ->with('success', "{$count} matches {$request->action}ed successfully.");
    }

    /**
     * Get tournament compliance report.
     */
    public function complianceReport(Tournament $tournament)
    {
        $tournament->load(['teams.team', 'teams.squads.player']);

        $report = [
            'tournament_name' => $tournament->name,
            'organization' => $tournament->organization->name,
            'total_teams' => $tournament->teams()->count(),
            'approved_teams' => $tournament->teams()->approved()->count(),
            'pending_teams' => $tournament->teams()->pending()->count(),
            'rejected_teams' => $tournament->teams()->rejected()->count(),
            'total_players' => $tournament->squads()->count(),
            'verified_players' => $tournament->squads()->verified()->count(),
            'pending_verification' => $tournament->squads()->pending()->count(),
            'teams_with_complete_squads' => $tournament->teams()->whereHas('squads', function ($q) {
                $q->verified();
            })->count(),
            'teams_with_incomplete_squads' => $tournament->teams()->whereHas('squads', function ($q) {
                $q->pending();
            })->count(),
        ];

        return view('super-admin.tournaments.reports.compliance', compact('tournament', 'report'));
    }

    /**
     * Get tournament performance report.
     */
    public function performanceReport(Tournament $tournament)
    {
        $tournament->load(['matches', 'standings.team.team']);

        $report = [
            'tournament_name' => $tournament->name,
            'total_matches' => $tournament->matches()->count(),
            'completed_matches' => $tournament->matches()->where('status', 'completed')->count(),
            'average_goals_per_match' => $tournament->matches()->where('status', 'completed')->count() > 0
                ? round(($tournament->matches()->sum('home_score') + $tournament->matches()->sum('away_score')) / $tournament->matches()->where('status', 'completed')->count(), 2)
                : 0,
            'highest_scoring_match' => $tournament->matches()->where('status', 'completed')
                ->orderByRaw('home_score + away_score DESC')
                ->first(),
            'standings' => $tournament->standings()->with('team.team')->ordered()->get(),
        ];

        return view('super-admin.tournaments.reports.performance', compact('tournament', 'report'));
    }

    /**
     * Get tournament financial report.
     */
    public function financialReport(Tournament $tournament)
    {
        // This would integrate with the finance module
        $report = [
            'tournament_name' => $tournament->name,
            'registration_fees' => 0, // Would be calculated from team registrations
            'sponsorship_revenue' => 0,
            'expenses' => 0,
            'net_profit' => 0,
        ];

        return view('super-admin.tournaments.reports.financial', compact('tournament', 'report'));
    }

    /**
     * Export tournament data.
     */
    public function exportData(Tournament $tournament)
    {
        $data = [
            'tournament' => $tournament,
            'teams' => $tournament->teams()->with('team')->get(),
            'players' => $tournament->squads()->with(['player', 'tournamentTeam.team'])->get(),
            'matches' => $tournament->matches()->with(['homeTeam.team', 'awayTeam.team'])->get(),
            'standings' => $tournament->standings()->with('team.team')->ordered()->get(),
        ];

        return response()->json($data);
    }

    /**
     * Import tournament data.
     */
    public function importData(Request $request, Tournament $tournament)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|json',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = json_decode($request->data, true);

        // Import logic would go here
        // This is a placeholder for the import functionality

        return redirect()->back()
            ->with('success', 'Tournament data imported successfully.');
    }
}
