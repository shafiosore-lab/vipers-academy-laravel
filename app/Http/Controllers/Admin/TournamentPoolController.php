<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\TournamentPool;
use App\Models\TournamentTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TournamentPoolController extends Controller
{
    /**
     * Display pools for a tournament.
     */
    public function index(Tournament $tournament)
    {
        $pools = $tournament->pools()->ordered()->get();
        $teams = $tournament->approvedTeams()->with(['team', 'pool'])->get();

        return view('admin.tournaments.pools.index', compact('tournament', 'pools', 'teams'));
    }

    /**
     * Show the form for creating a pool.
     */
    public function create(Tournament $tournament)
    {
        return view('admin.tournaments.pools.create', compact('tournament'));
    }

    /**
     * Store a new pool.
     */
    public function store(Request $request, Tournament $tournament)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'position' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pool = TournamentPool::create([
            'tournament_id' => $tournament->id,
            'name' => $request->name,
            'description' => $request->description,
            'position' => $request->position ?? ($tournament->pools()->max('position') + 1),
            'seed_method' => $request->seed_method ?? 'manual',
        ]);

        return redirect()->route('admin.tournaments.pools.index', $tournament->id)
            ->with('success', 'Pool created successfully.');
    }

    /**
     * Show pool details.
     */
    public function show(Tournament $tournament, TournamentPool $pool)
    {
        $teams = $pool->teams()->with('team')->ordered()->get();
        $matches = $pool->matches()->with(['homeTeam.team', 'awayTeam.team'])->get();

        return view('admin.tournaments.pools.show', compact('tournament', 'pool', 'teams', 'matches'));
    }

    /**
     * Show the form for editing a pool.
     */
    public function edit(Tournament $tournament, TournamentPool $pool)
    {
        return view('admin.tournaments.pools.edit', compact('tournament', 'pool'));
    }

    /**
     * Update a pool.
     */
    public function update(Request $request, Tournament $tournament, TournamentPool $pool)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'position' => 'nullable|integer|min:1',
            'seed_method' => 'nullable|in:manual,random,seeding,performance',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pool->update($request->all());

        return redirect()->route('admin.tournaments.pools.index', $tournament->id)
            ->with('success', 'Pool updated successfully.');
    }

    /**
     * Delete a pool.
     */
    public function destroy(Tournament $tournament, TournamentPool $pool)
    {
        // Remove team associations first
        $pool->teams()->update(['pool_id' => null]);

        $pool->delete();

        return redirect()->route('admin.tournaments.pools.index', $tournament->id)
            ->with('success', 'Pool deleted successfully.');
    }

    /**
     * Assign a team to a pool.
     */
    public function assignTeam(Request $request, Tournament $tournament, TournamentPool $pool)
    {
        $validator = Validator::make($request->all(), [
            'team_id' => 'required|exists:tournament_teams,id',
            'position' => 'nullable|integer|min:1',
            'seed_number' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $team = TournamentTeam::where('id', $request->team_id)
            ->where('tournament_id', $tournament->id)
            ->first();

        if (!$team) {
            return redirect()->back()
                ->with('error', 'Team not found in this tournament.');
        }

        $team->update([
            'pool_id' => $pool->id,
            'pool_position' => $request->position ?? $pool->teams()->max('pool_position') + 1,
            'seed_number' => $request->seed_number ?? $pool->getNextSeedNumber(),
        ]);

        return redirect()->back()
            ->with('success', 'Team assigned to pool.');
    }

    /**
     * Remove a team from a pool.
     */
    public function removeTeam(Tournament $tournament, TournamentPool $pool, TournamentTeam $team)
    {
        $team->update([
            'pool_id' => null,
            'pool_position' => null,
            'seed_number' => null,
        ]);

        return redirect()->back()
            ->with('success', 'Team removed from pool.');
    }

    /**
     * Reorder teams within a pool (drag and drop).
     */
    public function reorderTeams(Request $request, Tournament $tournament, TournamentPool $pool)
    {
        $validator = Validator::make($request->all(), [
            'teams' => 'required|array',
            'teams.*.team_id' => 'required|exists:tournament_teams,id',
            'teams.*.position' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        foreach ($request->teams as $teamData) {
            TournamentTeam::where('id', $teamData['team_id'])
                ->where('tournament_id', $tournament->id)
                ->update(['pool_position' => $teamData['position']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Move team to different pool (drag and drop between pools).
     */
    public function moveTeam(Request $request, Tournament $tournament)
    {
        $validator = Validator::make($request->all(), [
            'team_id' => 'required|exists:tournament_teams,id',
            'pool_id' => 'nullable|exists:tournament_pools,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $team = TournamentTeam::where('id', $request->team_id)
            ->where('tournament_id', $tournament->id)
            ->first();

        if (!$team) {
            return response()->json(['error' => 'Team not found'], 404);
        }

        $team->update(['pool_id' => $request->pool_id]);

        // Recalculate positions in both old and new pools
        if ($request->pool_id) {
            $pool = TournamentPool::find($request->pool_id);
            $pool->teams()->update(['pool_position' => null]);
            $teams = $pool->teams()->ordered()->get();
            foreach ($teams as $index => $t) {
                $t->update(['pool_position' => $index + 1]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Automatically redistribute teams across pools.
     */
    public function redistribute(Request $request, Tournament $tournament)
    {
        $validator = Validator::make($request->all(), [
            'num_pools' => 'required|integer|min:2',
            'method' => 'required|in:manual,random,seeding,performance',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $teamCount = $tournament->approvedTeams()->count();

        if ($teamCount < $request->num_pools) {
            return redirect()->back()
                ->with('error', "Cannot create {$request->num_pools} pools with only {$teamCount} teams.");
        }

        TournamentPool::redistributeTeams(
            $tournament,
            $request->num_pools,
            $request->method
        );

        return redirect()->route('admin.tournaments.pools.index', $tournament->id)
            ->with('success', 'Teams redistributed across ' . $request->num_pools . ' pools using ' . $request->method . ' method.');
    }

    /**
     * Clear all pool assignments.
     */
    public function clearPools(Tournament $tournament)
    {
        $tournament->teams()->update([
            'pool_id' => null,
            'pool_position' => null,
            'seed_number' => null,
        ]);

        return redirect()->route('admin.tournaments.pools.index', $tournament->id)
            ->with('success', 'All pool assignments cleared.');
    }

    /**
     * Auto-create pools based on number of teams.
     */
    public function autoCreatePools(Request $request, Tournament $tournament)
    {
        $validator = Validator::make($request->all(), [
            'num_pools' => 'required|integer|min:2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $teamCount = $tournament->approvedTeams()->count();

        if ($teamCount < $request->num_pools) {
            return redirect()->back()
                ->with('error', "Cannot create {$request->num_pools} pools with only {$teamCount} teams.");
        }

        // Create pools
        for ($i = 1; $i <= $request->num_pools; $i++) {
            TournamentPool::create([
                'tournament_id' => $tournament->id,
                'name' => 'Pool ' . chr(64 + $i),
                'position' => $i,
                'seed_method' => 'random',
            ]);
        }

        // Redistribute teams
        TournamentPool::redistributeTeams($tournament, $request->num_pools, 'random');

        return redirect()->route('admin.tournaments.pools.index', $tournament->id)
            ->with('success', "Created {$request->num_pools} pools and distributed teams.");
    }
}
