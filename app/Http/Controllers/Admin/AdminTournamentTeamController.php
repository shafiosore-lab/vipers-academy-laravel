<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminTournamentTeamController extends Controller
{
    /**
     * Display registered teams for a tournament.
     */
    public function index(Request $request, Tournament $tournament)
    {
        $query = $tournament->teams()->with('team');

        // Filter by approval status
        if ($request->has('status') && $request->status) {
            $query->where('approval_status', $request->status);
        }

        $teams = $query->orderBy('registration_date', 'desc')->paginate(10);

        return view('admin.tournaments.teams.index', compact('tournament', 'teams'));
    }

    /**
     * Show the form for registering a team in a tournament.
     */
    public function create(Tournament $tournament)
    {
        $user = Auth::user();
        $organizationId = $user->organization_id;

        // Get teams from user's organization
        $teams = Team::where('organization_id', $organizationId)
            ->active()
            ->orderBy('name')
            ->get();

        // Get available teams (not already registered in this tournament)
        $registeredTeamIds = $tournament->teams()->pluck('team_id')->toArray();
        $availableTeams = $teams->whereNotIn('id', $registeredTeamIds);

        return view('admin.tournaments.teams.create', compact('tournament', 'availableTeams'));
    }

    /**
     * Register a team in a tournament.
     */
    public function store(Request $request, Tournament $tournament)
    {
        $user = Auth::user();

        // Check if tournament is open for registration
        if (!$tournament->canRegister()) {
            return redirect()->back()
                ->with('error', 'Tournament registration is not open.');
        }

        // Check if deadline has passed
        if ($tournament->isRegistrationDeadlinePassed()) {
            return redirect()->back()
                ->with('error', 'Registration deadline has passed.');
        }

        // Check if tournament has capacity
        if (!$tournament->hasCapacity()) {
            return redirect()->back()
                ->with('error', 'Tournament has reached maximum team capacity.');
        }

        $validator = Validator::make($request->all(), [
            'team_id' => 'required|exists:teams,id',
            'team_contact_name' => 'nullable|string|max:255',
            'team_contact_email' => 'nullable|email|max:255',
            'team_contact_phone' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if team is already registered
        $existingRegistration = TournamentTeam::where('tournament_id', $tournament->id)
            ->where('team_id', $request->team_id)
            ->first();

        if ($existingRegistration) {
            return redirect()->back()
                ->with('error', 'This team is already registered in the tournament.');
        }

        // Get team details
        $team = Team::findOrFail($request->team_id);

        // Create tournament team registration
        $tournamentTeam = TournamentTeam::create([
            'tournament_id' => $tournament->id,
            'team_id' => $request->team_id,
            'team_name' => $team->name,
            'team_contact_name' => $request->team_contact_name ?? $user->name,
            'team_contact_email' => $request->team_contact_email ?? $user->email,
            'team_contact_phone' => $request->team_contact_phone ?? $user->phone ?? '',
            'approval_status' => TournamentTeam::STATUS_PENDING,
            'registration_date' => now(),
        ]);

        return redirect()->route('admin.tournaments.teams.show', [$tournament->id, $tournamentTeam->id])
            ->with('success', 'Team registered successfully. Pending approval.');
    }

    /**
     * Display tournament team details.
     */
    public function show(Tournament $tournament, TournamentTeam $team)
    {
        $team->load(['team', 'tournament', 'squads.player']);

        return view('admin.tournaments.teams.show', compact('tournament', 'team'));
    }

    /**
     * Show the form for editing team registration.
     */
    public function edit(Tournament $tournament, TournamentTeam $team)
    {
        return view('admin.tournaments.teams.edit', compact('tournament', 'team'));
    }

    /**
     * Update team registration.
     */
    public function update(Request $request, Tournament $tournament, TournamentTeam $team)
    {
        $validator = Validator::make($request->all(), [
            'team_contact_name' => 'nullable|string|max:255',
            'team_contact_email' => 'nullable|email|max:255',
            'team_contact_phone' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $team->update($request->all());

        return redirect()->back()
            ->with('success', 'Team registration updated successfully.');
    }

    /**
     * Remove team from tournament.
     */
    public function destroy(Tournament $tournament, TournamentTeam $team)
    {
        // Check if team has players in squad
        if ($team->squads()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot remove team with registered players. Please remove players first.');
        }

        $team->delete();

        return redirect()->route('admin.tournaments.teams.index', $tournament->id)
            ->with('success', 'Team removed from tournament.');
    }

    /**
     * Approve team registration.
     */
    public function approve(Request $request, Tournament $tournament, TournamentTeam $team)
    {
        if (!$team->isPending() && !$team->isCorrectionRequested()) {
            return redirect()->back()
                ->with('error', 'Team is not pending approval.');
        }

        $user = Auth::user();
        $team->approve($user);

        return redirect()->back()
            ->with('success', 'Team approved successfully.');
    }

    /**
     * Reject team registration.
     */
    public function reject(Request $request, Tournament $tournament, TournamentTeam $team)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!$team->isPending()) {
            return redirect()->back()
                ->with('error', 'Team is not pending approval.');
        }

        $user = Auth::user();
        $team->reject($user, $request->rejection_reason);

        return redirect()->back()
            ->with('success', 'Team registration rejected.');
    }

    /**
     * Request corrections from team.
     */
    public function requestCorrection(Request $request, Tournament $tournament, TournamentTeam $team)
    {
        $validator = Validator::make($request->all(), [
            'correction_notes' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!$team->isPending()) {
            return redirect()->back()
                ->with('error', 'Team is not pending approval.');
        }

        $user = Auth::user();
        $team->requestCorrection($user, $request->correction_notes);

        return redirect()->back()
            ->with('success', 'Correction request sent to team.');
    }

    /**
     * Get available teams for a tournament (AJAX).
     */
    public function getAvailableTeams(Tournament $tournament)
    {
        $user = Auth::user();
        $organizationId = $user->organization_id;

        // Get teams from user's organization
        $teams = Team::where('organization_id', $organizationId)
            ->active()
            ->orderBy('name')
            ->get();

        // Get registered team IDs
        $registeredTeamIds = $tournament->teams()->pluck('team_id')->toArray();

        // Filter out registered teams
        $availableTeams = $teams->whereNotIn('id', $registeredTeamIds);

        return response()->json($availableTeams);
    }
}
