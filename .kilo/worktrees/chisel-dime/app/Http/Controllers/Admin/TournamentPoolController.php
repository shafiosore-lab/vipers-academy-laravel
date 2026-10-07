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
     * Get the route prefix based on user role.
     */
    private function getRoutePrefix(): string
    {
        $user = auth()->user();
        if ($user && $user->hasRole('super-admin')) {
            return 'super-admin';
        }
        return 'admin';
    }

    /**
     * Redirect to the appropriate tournament show page based on user role.
     */
    private function redirectToTournamentShow(Tournament $tournament, string $message = 'Success'): \Illuminate\Http\RedirectResponse
    {
        $prefix = $this->getRoutePrefix();
        return redirect()->route($prefix . '.tournaments.show', $tournament->id)
            ->with('success', $message);
    }
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

        return $this->redirectToTournamentShow($tournament, 'Pool created successfully.');
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

        return $this->redirectToTournamentShow($tournament, 'Pool updated successfully.');
    }

    /**
     * Delete a pool.
     */
    public function destroy(Tournament $tournament, TournamentPool $pool)
    {
        // Remove team associations first
        $pool->teams()->update(['pool_id' => null]);

        $pool->delete();

        return $this->redirectToTournamentShow($tournament, 'Pool deleted successfully.');
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

        return $this->redirectToTournamentShow($tournament, 'Teams redistributed across ' . $request->num_pools . ' pools using ' . $request->method . ' method.');
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

        return $this->redirectToTournamentShow($tournament, 'All pool assignments cleared.');
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

        // Create pools (use firstOrCreate to avoid duplicates)
        for ($i = 1; $i <= $request->num_pools; $i++) {
            TournamentPool::firstOrCreate(
                [
                    'tournament_id' => $tournament->id,
                    'name' => 'Pool ' . chr(64 + $i)
                ],
                [
                    'position' => $i,
                    'seed_method' => 'random'
                ]
            );
        }

        // Redistribute teams
        TournamentPool::redistributeTeams($tournament, $request->num_pools, 'random');

        return $this->redirectToTournamentShow($tournament, "Created {$request->num_pools} pools and distributed teams.");
    }

    /**
     * Display the reshuffle interface (FIFA-style).
     * Shows teams organized by pools with drag-and-drop functionality.
     * Includes automatic initial reshuffle on first load.
     */
    public function reshuffle(Request $request, Tournament $tournament)
    {
        $teams = $tournament->approvedTeams()->with(['team', 'standing'])->get();
        $pools = $tournament->pools()->ordered()->get();

        // Get reshuffle count from session (max 4 per session)
        $sessionKey = 'tournament_' . $tournament->id . '_reshuffle_count';
        $reshuffleCount = session($sessionKey, 0);
        $maxReshuffles = 4;

        // Calculate teams per pool for even distribution
        $numPools = max(2, (int) ceil($teams->count() / 4)); // Aim for ~4 teams per pool
        if ($numPools > 4) $numPools = 4; // Maximum 4 pools

        // Auto-create pools and distribute teams on first load if no pools exist
        if ($pools->isEmpty() && $teams->isNotEmpty()) {
            // Create pools (use firstOrCreate to avoid duplicates)
            for ($i = 1; $i <= $numPools; $i++) {
                TournamentPool::firstOrCreate(
                    ['tournament_id' => $tournament->id, 'name' => 'Pool ' . chr(64 + $i)],
                    ['position' => $i, 'seed_method' => 'random']
                );
            }

            // Perform initial distribution using seeding if available, otherwise random
            $hasSeeding = $teams->whereNotNull('seed_number')->isNotEmpty();
            $method = $hasSeeding ? 'seeding' : 'random';

            TournamentPool::redistributeTeams($tournament, $numPools, $method);

            // Refresh pools after redistribution
            $pools = $tournament->pools()->ordered()->get();
        }

        // Get teams organized by pool for the view
        $teamsByPool = [];
        $unassignedTeams = [];

        foreach ($teams as $team) {
            if ($team->pool_id && $pools->contains('id', $team->pool_id)) {
                if (!isset($teamsByPool[$team->pool_id])) {
                    $teamsByPool[$team->pool_id] = [];
                }
                $teamsByPool[$team->pool_id][] = $team;
            } else {
                $unassignedTeams[] = $team;
            }
        }

        // Sort teams within each pool by pool_position or seed_number
        foreach ($teamsByPool as $poolId => $poolTeams) {
            usort($teamsByPool[$poolId], function($a, $b) {
                $aPos = $a->pool_position ?? $a->seed_number ?? 999;
                $bPos = $b->pool_position ?? $b->seed_number ?? 999;
                return $aPos - $bPos;
            });
        }

        return view('admin.tournaments.pools.reshuffle', compact(
            'tournament', 'pools', 'teams', 'teamsByPool', 'unassignedTeams',
            'reshuffleCount', 'maxReshuffles'
        ));
    }

    /**
     * Perform a reshuffle of teams across pools.
     * Uses session to track reshuffle count (max 4 per session).
     */
    public function performReshuffle(Request $request, Tournament $tournament)
    {
        $sessionKey = 'tournament_' . $tournament->id . '_reshuffle_count';
        $reshuffleCount = session($sessionKey, 0);
        $maxReshuffles = 4;

        if ($reshuffleCount >= $maxReshuffles) {
            return redirect()->back()
                ->with('error', "Maximum reshuffle limit ({$maxReshuffles}) reached for this session. You can continue manually adjusting teams using drag and drop.");
        }

        $teams = $tournament->approvedTeams()->with('standing')->get();
        $pools = $tournament->pools()->ordered()->get();

        if ($teams->isEmpty() || $pools->isEmpty()) {
            return redirect()->back()
                ->with('error', 'No teams or pools to shuffle. Please create pools first.');
        }

        $numPools = $pools->count();

        // Determine reshuffle method based on available data
        $method = 'random';
        $hasSeeding = $teams->whereNotNull('seed_number')->isNotEmpty();
        $hasPerformance = $teams->filter(function($team) {
            return $team->standing && $team->standing->points > 0;
        })->isNotEmpty();

        // Use seeding if available, otherwise performance-based, otherwise random
        if ($hasSeeding) {
            $method = 'seeding';
        } elseif ($hasPerformance && $request->input('use_performance', false)) {
            $method = 'performance';
        }

        // Perform redistribution
        TournamentPool::redistributeTeams($tournament, $numPools, $method);

        // Increment reshuffle count in session
        session([$sessionKey => $reshuffleCount + 1]);

        $newCount = $reshuffleCount + 1;

        return redirect()->route($this->getRoutePrefix() . '.tournaments.pools.reshuffle', $tournament->id)
            ->with('success', "Teams reshuffled! ({$newCount}/{$maxReshuffles} reshuffles used)");
    }

    /**
     * Reset the reshuffle count for a tournament.
     */
    public function resetReshuffleCount(Request $request, Tournament $tournament)
    {
        $sessionKey = 'tournament_' . $tournament->id . '_reshuffle_count';
        session([$sessionKey => 0]);

        return redirect()->route($this->getRoutePrefix() . '.tournaments.pools.reshuffle', $tournament->id)
            ->with('success', 'Reshuffle counter has been reset.');
    }

    /**
     * Update team positions after drag-and-drop.
     * This endpoint accepts JSON with team positions.
     */
    public function updateTeamPositions(Request $request, Tournament $tournament)
    {
        $validator = Validator::make($request->all(), [
            'teams' => 'required|array',
            'teams.*.id' => 'required|exists:tournament_teams,id',
            'teams.*.pool_id' => 'nullable|exists:tournament_pools,id',
            'teams.*.position' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        foreach ($request->teams as $teamData) {
            TournamentTeam::where('id', $teamData['id'])
                ->where('tournament_id', $tournament->id)
                ->update([
                    'pool_id' => $teamData['pool_id'],
                    'pool_position' => $teamData['position'],
                ]);
        }

        return response()->json(['success' => true, 'message' => 'Team positions updated successfully.']);
    }

    /**
     * Perform reshuffle via AJAX (for Match Center modal).
     */
    public function performReshuffleAjax(Request $request, Tournament $tournament)
    {
        $sessionKey = 'tournament_' . $tournament->id . '_reshuffle_count';
        $reshuffleCount = session($sessionKey, 0);
        $maxReshuffles = 4;

        if ($reshuffleCount >= $maxReshuffles) {
            return response()->json([
                'success' => false,
                'error' => "Maximum reshuffle limit ({$maxReshuffles}) reached for this session."
            ], 422);
        }

        $teams = $tournament->approvedTeams()->with('standing')->get();
        $pools = $tournament->pools()->ordered()->get();

        if ($teams->isEmpty() || $pools->isEmpty()) {
            return response()->json([
                'success' => false,
                'error' => 'No teams or pools to shuffle.'
            ], 422);
        }

        $numPools = $pools->count();
        $method = 'random';
        $hasSeeding = $teams->whereNotNull('seed_number')->isNotEmpty();
        $hasPerformance = $teams->filter(function($team) {
            return $team->standing && $team->standing->points > 0;
        })->isNotEmpty();

        if ($hasSeeding) {
            $method = 'seeding';
        } elseif ($hasPerformance && $request->input('use_performance', false)) {
            $method = 'performance';
        }

        TournamentPool::redistributeTeams($tournament, $numPools, $method);

        session([$sessionKey => $reshuffleCount + 1]);

        return response()->json([
            'success' => true,
            'reshuffle_count' => $reshuffleCount + 1,
            'max_reshuffles' => $maxReshuffles,
            'message' => 'Teams reshuffled successfully!'
        ]);
    }

    /**
     * Reset reshuffle count via AJAX (for Match Center modal).
     */
    public function resetReshuffleCountAjax(Request $request, Tournament $tournament)
    {
        $sessionKey = 'tournament_' . $tournament->id . '_reshuffle_count';
        session([$sessionKey => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Reshuffle counter has been reset.'
        ]);
    }
}
