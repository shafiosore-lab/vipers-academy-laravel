<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TournamentSquad;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminTournamentSquadController extends Controller
{
    /**
     * Display squad for a tournament team.
     */
    public function index(Request $request, Tournament $tournament, TournamentTeam $team)
    {
        $team->load(['team', 'squads.player']);

        $query = $team->squads()->with('player');

        // Filter by verification status
        if ($request->has('status') && $request->status) {
            $query->where('verification_status', $request->status);
        }

        $squads = $query->orderBy('jersey_number')->paginate(15);

        return view('admin.tournaments.squads.index', compact('tournament', 'team', 'squads'));
    }

    /**
     * Show the form for adding a player to squad.
     */
    public function create(Tournament $tournament, TournamentTeam $team)
    {
        // Get players from the team's organization
        $teamOrganizationId = $team->team->organization_id;

        // Get players not already in this tournament
        $registeredPlayerIds = TournamentSquad::whereHas('tournamentTeam', function ($query) use ($tournament) {
            $query->where('tournament_id', $tournament->id);
        })->pluck('player_id')->toArray();

        $players = Player::where('organization_id', $teamOrganizationId)
            ->whereNotIn('id', $registeredPlayerIds)
            ->approved()
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('admin.tournaments.squads.create', compact('tournament', 'team', 'players'));
    }

    /**
     * Add a player to the tournament squad.
     */
    public function store(Request $request, Tournament $tournament, TournamentTeam $team)
    {
        // Check if squad is locked
        if ($team->isSquadLocked()) {
            return redirect()->back()
                ->with('error', 'Squad is locked. Cannot add players.');
        }

        // Check squad limit
        if (!$team->meetsSquadLimit()) {
            return redirect()->back()
                ->with('error', 'Squad has reached the maximum limit.');
        }

        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:players,id',
            'jersey_number' => 'nullable|integer|min:1|max:99',
            'position' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if player is already registered in this tournament (different team)
        if (TournamentSquad::playerRegisteredInTournament($request->player_id, $tournament->id)) {
            return redirect()->back()
                ->with('error', 'This player is already registered in another team in this tournament.');
        }

        // Check if player is already in this squad
        $existingSquad = TournamentSquad::where('tournament_team_id', $team->id)
            ->where('player_id', $request->player_id)
            ->first();

        if ($existingSquad) {
            return redirect()->back()
                ->with('error', 'This player is already in the squad.');
        }

        // Create squad entry
        $squad = TournamentSquad::create([
            'tournament_team_id' => $team->id,
            'player_id' => $request->player_id,
            'jersey_number' => $request->jersey_number,
            'position' => $request->position,
            'verification_status' => TournamentSquad::STATUS_PENDING,
            'registration_date' => now(),
        ]);

        return redirect()->route('admin.tournaments.squads.index', [$tournament->id, $team->id])
            ->with('success', 'Player added to squad successfully.');
    }

    /**
     * Remove player from squad.
     */
    public function destroy(Tournament $tournament, TournamentTeam $team, TournamentSquad $squad)
    {
        // Check if squad is locked
        if ($squad->isLocked()) {
            return redirect()->back()
                ->with('error', 'Player squad entry is locked. Cannot remove.');
        }

        $squad->delete();

        return redirect()->back()
            ->with('success', 'Player removed from squad.');
    }

    /**
     * Verify player squad entry.
     */
    public function verify(Request $request, Tournament $tournament, TournamentTeam $team, TournamentSquad $squad)
    {
        $user = Auth::user();
        $squad->verify($user, $request->notes ?? null);

        return redirect()->back()
            ->with('success', 'Player verified successfully.');
    }

    /**
     * Reject player squad entry.
     */
    public function reject(Request $request, Tournament $tournament, TournamentTeam $team, TournamentSquad $squad)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $squad->reject($user, $request->reason);

        return redirect()->back()
            ->with('success', 'Player squad entry rejected.');
    }

    /**
     * Bulk upload players via CSV.
     */
    public function bulkUpload(Request $request, Tournament $tournament, TournamentTeam $team)
    {
        // Check if squad is locked
        if ($team->isSquadLocked()) {
            return redirect()->back()
                ->with('error', 'Squad is locked. Cannot upload players.');
        }

        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('csv_file');
        $handle = fopen($file->path(), 'r');
        $header = fgetcsv($handle);

        // Expected columns: Full Name, DOB, Position, Jersey Number, ID Number
        $expectedColumns = ['full_name', 'dob', 'position', 'jersey_number', 'id_number'];
        $header = array_map('strtolower', array_map('trim', $header));

        if (array_diff($expectedColumns, $header)) {
            fclose($handle);
            return redirect()->back()
                ->with('error', 'Invalid CSV format. Required columns: Full Name, DOB, Position, Jersey Number, ID Number');
        }

        $teamOrganizationId = $team->team->organization_id;
        $addedCount = 0;
        $skippedCount = 0;
        $errors = [];

        // Get already registered players in this tournament
        $registeredPlayerIds = TournamentSquad::whereHas('tournamentTeam', function ($query) use ($tournament) {
            $query->where('tournament_id', $tournament->id);
        })->pluck('player_id')->toArray();

        // Get players from organization
        $organizationPlayers = Player::where('organization_id', $teamOrganizationId)->get();

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            // Find player by full name or ID number
            $player = $organizationPlayers->filter(function ($p) use ($data) {
                $fullName = trim($data['full_name']);
                $idNumber = isset($data['id_number']) ? trim($data['id_number']) : '';

                return $p->full_name === $fullName ||
                       ($idNumber && $p->id_number === $idNumber);
            })->first();

            if (!$player) {
                $skippedCount++;
                $errors[] = "Player not found: {$data['full_name']}";
                continue;
            }

            // Check if player already registered
            if (in_array($player->id, $registeredPlayerIds)) {
                $skippedCount++;
                $errors[] = "Already registered: {$player->full_name}";
                continue;
            }

            // Check squad limit
            if ($team->squads()->count() >= $tournament->squad_limit) {
                $errors[] = "Squad limit reached. Cannot add more players.";
                break;
            }

            // Add player to squad
            TournamentSquad::create([
                'tournament_team_id' => $team->id,
                'player_id' => $player->id,
                'jersey_number' => isset($data['jersey_number']) ? (int)$data['jersey_number'] : null,
                'position' => isset($data['position']) ? $data['position'] : null,
                'verification_status' => TournamentSquad::STATUS_PENDING,
                'registration_date' => now(),
            ]);

            $registeredPlayerIds[] = $player->id;
            $addedCount++;
        }

        fclose($handle);

        $message = "Uploaded {$addedCount} players. Skipped {$skippedCount} players.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', array_slice($errors, 0, 5));
        }

        return redirect()->back()
            ->with($addedCount > 0 ? 'success' : 'warning', $message);
    }

    /**
     * Download CSV template.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="tournament_squad_template.csv"',
        ];

        $callback = function() {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, ['Full Name', 'DOB', 'Position', 'Jersey Number', 'ID Number']);

            // Example row
            fputcsv($handle, ['John Doe', '2010-01-15', 'Midfielder', '10', '12345678']);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Update player squad details.
     */
    public function update(Request $request, Tournament $tournament, TournamentTeam $team, TournamentSquad $squad)
    {
        // Check if squad entry is locked
        if ($squad->isLocked()) {
            return redirect()->back()
                ->with('error', 'Squad entry is locked. Cannot edit.');
        }

        $validator = Validator::make($request->all(), [
            'jersey_number' => 'nullable|integer|min:1|max:99',
            'position' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $squad->update($request->all());

        return redirect()->back()
            ->with('success', 'Player squad details updated.');
    }

    /**
     * Approve all pending squad players.
     */
    public function approveAll(Tournament $tournament, TournamentTeam $team)
    {
        $user = Auth::user();

        $pendingSquads = $team->squads()->pending()->get();

        foreach ($pendingSquads as $squad) {
            $squad->verify($user, 'Bulk verified');
        }

        return redirect()->back()
            ->with('success', "Approved {$pendingSquads->count()} players.");
    }

    /**
     * Export squad to CSV.
     */
    public function export(Tournament $tournament, TournamentTeam $team)
    {
        $team->load(['team', 'squads.player']);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$team->team->name}_squad.csv\"",
        ];

        $callback = function() use ($team) {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, ['Full Name', 'Position', 'Jersey Number', 'Status', 'Registration Date']);

            foreach ($team->squads as $squad) {
                fputcsv($handle, [
                    $squad->player->full_name,
                    $squad->position,
                    $squad->jersey_number,
                    $squad->verification_status,
                    $squad->registration_date ? $squad->registration_date->format('Y-m-d') : '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get squad statistics.
     */
    public function stats(Tournament $tournament, TournamentTeam $team)
    {
        $team->load(['team', 'squads.player']);

        $stats = [
            'total' => $team->squads()->count(),
            'verified' => $team->squads()->verified()->count(),
            'pending' => $team->squads()->pending()->count(),
            'rejected' => $team->squads()->rejected()->count(),
            'min_players' => $tournament->min_players,
            'max_players' => $tournament->squad_limit,
            'meets_minimum' => $team->meetsMinimumRequirement(),
            'locked' => $team->isSquadLocked(),
        ];

        return response()->json($stats);
    }
}
