<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TournamentMatch;
use App\Models\TournamentSquad;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminTournamentController extends Controller
{
    /**
     * AdminTournamentController constructor.
     */
    public function __construct()
    {
        // Apply authorization check to all methods
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            $permissionService = new PermissionService();

            if (!$permissionService->hasRoleOrHigher($user, 'admin')) {
                abort(403, 'Unauthorized access to tournament management');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of tournaments.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $organizationId = $user->organization_id;

        $query = Tournament::with(['organization'])
            ->when($organizationId, function ($q) use ($organizationId) {
                return $q->where('organization_id', $organizationId);
            })
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by organization type (for super admin)
        if ($user->hasRole('super-admin') && $request->has('organization_type')) {
            $query->whereHas('organization', function ($q) use ($request) {
                $q->where('type', $request->organization_type);
            });
        }

        $tournaments = $query->paginate(10);

        return view('admin.tournaments.index', compact('tournaments'));
    }

    /**
     * Show the form for creating a new tournament.
     */
    public function create()
    {
        // Get tournament-type organizations for super admin
        $organizations = Organization::where('type', 'tournament')
            ->orWhere('type', 'club')
            ->orWhere('type', 'academy')
            ->active()
            ->orderBy('name')
            ->get();

        return view('admin.tournaments.create', compact('organizations'));
    }

    /**
     * Store a newly created tournament.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'organization_id' => 'required|exists:organizations,id',
            'season' => 'nullable|string|max:50',
            'competition_format' => 'nullable|in:league,round_robin,league_cup,knockout,knockout_plus,groups_knockout,double_elimination',
            'estimated_matches' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'registration_deadline' => 'nullable|date|after:now',
            'squad_limit' => 'nullable|integer|min:5|max:50',
            'min_players' => 'nullable|integer|min:5|max:20',
            'max_teams' => 'nullable|integer|min:2',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
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
            'created_by' => $user->id,
        ]);

        return redirect()->route('admin.tournaments.show', $tournament->id)
            ->with('success', 'Tournament created successfully.');
    }

    /**
     * Display the specified tournament.
     */
    public function show(Tournament $tournament)
    {
        $tournament->load(['organization', 'teams.team', 'teams.squads.player']);

        $registeredTeams = $tournament->teams()->with('team')->paginate(10);
        $pendingTeams = $tournament->teams()->pending()->with('team')->get();
        $approvedTeams = $tournament->teams()->approved()->with('team')->get();

        $matches = $tournament->matches()->with(['homeTeam.team', 'awayTeam.team'])
            ->orderBy('match_day')
            ->orderBy('kickoff_time')
            ->paginate(10);

        $standings = $tournament->standings()->with('team.team')->ordered()->get();

        return view('admin.tournaments.show', compact(
            'tournament',
            'registeredTeams',
            'pendingTeams',
            'approvedTeams',
            'matches',
            'standings'
        ));
    }

    /**
     * Show the form for editing the specified tournament.
     */
    public function edit(Tournament $tournament)
    {
        $organizations = Organization::where('type', 'tournament')
            ->orWhere('type', 'club')
            ->orWhere('type', 'academy')
            ->active()
            ->orderBy('name')
            ->get();

        return view('admin.tournaments.edit', compact('tournament', 'organizations'));
    }

    /**
     * Update the specified tournament.
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
            'status' => ['required', Rule::in([
                Tournament::STATUS_DRAFT,
                Tournament::STATUS_OPEN,
                Tournament::STATUS_CLOSED,
                Tournament::STATUS_ONGOING,
                Tournament::STATUS_COMPLETED,
                Tournament::STATUS_CANCELLED,
            ])],
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

        // Handle status change - lock/unlock squads
        $oldStatus = $tournament->status;
        $newStatus = $request->status;

        $tournament->update($request->all());

        // Lock squads when tournament starts
        if ($oldStatus !== Tournament::STATUS_ONGOING && $newStatus === Tournament::STATUS_ONGOING) {
            $tournament->lockSquads();
        }

        // Unlock squads when tournament is completed (for archive)
        if ($oldStatus === Tournament::STATUS_ONGOING && $newStatus === Tournament::STATUS_COMPLETED) {
            // Keep squads locked for completed tournaments
        }

        return redirect()->route('admin.tournaments.show', $tournament->id)
            ->with('success', 'Tournament updated successfully.');
    }

    /**
     * Remove the specified tournament.
     */
    public function destroy(Tournament $tournament)
    {
        // Check if tournament has matches
        if ($tournament->matches()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete tournament with matches. Please delete matches first.');
        }

        $tournament->delete();

        return redirect()->route('admin.tournaments.index')
            ->with('success', 'Tournament deleted successfully.');
    }

    /**
     * Open tournament for registration.
     */
    public function openRegistration(Tournament $tournament)
    {
        if ($tournament->status !== Tournament::STATUS_DRAFT && $tournament->status !== Tournament::STATUS_CLOSED) {
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

        if ($tournament->approvedTeams()->count() < 2) {
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
     * View tournament standings.
     */
    public function standings(Tournament $tournament)
    {
        $tournament->load(['standings.team.team', 'teams.team']);

        return view('admin.tournaments.standings', compact('tournament'));
    }

    /**
     * Force unlock squads (Super Admin only).
     */
    public function unlockSquads(Tournament $tournament)
    {
        $user = auth()->user();

        if (!$user->hasRole('super-admin')) {
            return redirect()->back()
                ->with('error', 'Only Super Admins can force unlock squads.');
        }

        $tournament->unlockSquads();

        return redirect()->back()
            ->with('success', 'Squads have been unlocked.');
    }

    /**
     * Cancel tournament.
     */
    public function cancelTournament(Tournament $tournament)
    {
        if ($tournament->status === Tournament::STATUS_COMPLETED) {
            return redirect()->back()
                ->with('error', 'Cannot cancel a completed tournament.');
        }

        $tournament->update(['status' => Tournament::STATUS_CANCELLED]);

        return redirect()->back()
            ->with('success', 'Tournament has been cancelled.');
    }

    /**
     * Archive tournament.
     */
    public function archiveTournament(Tournament $tournament)
    {
        if ($tournament->status !== Tournament::STATUS_COMPLETED) {
            return redirect()->back()
                ->with('error', 'Only completed tournaments can be archived.');
        }

        $tournament->update(['status' => Tournament::STATUS_ARCHIVED]);

        return redirect()->back()
            ->with('success', 'Tournament has been archived.');
    }

    /**
     * Toggle tournament visibility.
     */
    public function toggleVisibility(Tournament $tournament)
    {
        $tournament->update(['is_public' => !$tournament->is_public]);

        $status = $tournament->is_public ? 'public' : 'private';

        return redirect()->back()
            ->with('success', "Tournament is now {$status}.");
    }

    /**
     * Restore archived tournament.
     */
    public function restoreTournament(Tournament $tournament)
    {
        if ($tournament->status !== Tournament::STATUS_ARCHIVED) {
            return redirect()->back()
                ->with('error', 'Only archived tournaments can be restored.');
        }

        $tournament->update(['status' => Tournament::STATUS_DRAFT]);

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
     * Get public tournaments list.
     */
    public function publicIndex(Request $request)
    {
        $tournaments = Tournament::public()
            ->with('organization')
            ->whereIn('status', [Tournament::STATUS_OPEN, Tournament::STATUS_ONGOING])
            ->orderBy('start_date', 'desc')
            ->paginate(12);

        return view('public.tournaments.index', compact('tournaments'));
    }

    /**
     * Show public tournament details.
     */
    public function publicShow(Tournament $tournament)
    {
        if (!$tournament->is_public && !auth()->check()) {
            abort(404);
        }

        $tournament->load(['organization', 'approvedTeams.team']);

        $matches = $tournament->matches()
            ->with(['homeTeam.team', 'awayTeam.team'])
            ->where('status', 'completed')
            ->orderBy('kickoff_time', 'desc')
            ->limit(10)
            ->get();

        $standings = $tournament->standings()
            ->with('team.team')
            ->ordered()
            ->get();

        return view('public.tournaments.show', compact('tournament', 'matches', 'standings'));
    }
}
