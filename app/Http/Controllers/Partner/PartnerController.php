<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlayerFormRequest;
use App\Models\Player;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerController extends \App\Http\Controllers\Controller
{
    /**
     * Prepare player data from request (shared logic)
     */
    protected function preparePlayerData(Request $request): array
    {
        $data = $request->all();

        // Map guardian fields to parent fields for SMS notifications
        $data['parent_guardian_name'] = $data['guardian_name'] ?? null;
        $data['parent_phone'] = $data['guardian_phone'] ?? null;

        // Create full name from first and last name
        $data['full_name'] = $data['first_name'] . ' ' . $data['last_name'];

        // Calculate age from date of birth
        if (isset($data['date_of_birth'])) {
            $data['age'] = \Carbon\Carbon::parse($data['date_of_birth'])->age;
        }

        return $data;
    }

    /**
     * Display the partner dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $partnerDetails = $user->partner_details ?? [];

        // Get partnership type and determine features
        $partnershipType = $partnerDetails['partnership_type'] ?? 'platform_access';

        // Handle case where partner_id column doesn't exist yet
        try {
            $managedPlayers = $user->managedPlayers()->get();

            // Calculate statistics
            $stats = [
                'total_players' => $managedPlayers->count(),
                'active_players' => $managedPlayers->where('registration_status', 'Active')->count(),
                'pending_players' => $managedPlayers->where('registration_status', 'Pending')->count(),
                'approved_players' => $managedPlayers->filter(function($player) {
                    return $player->isApproved();
                })->count(),
            ];

            // Get recent activities
            $recentPlayers = $managedPlayers->sortByDesc('created_at')->take(5);
        } catch (\Exception $e) {
            // If partner_id column doesn't exist, show empty data
            $managedPlayers = collect();
            $stats = [
                'total_players' => 0,
                'active_players' => 0,
                'pending_players' => 0,
                'approved_players' => 0,
            ];
            $recentPlayers = collect();
        }

        return view('partner.dashboard', compact(
            'user',
            'partnerDetails',
            'partnershipType',
            'managedPlayers',
            'stats',
            'recentPlayers'
        ));
    }

    /**
     * Display managed players
     */
    public function players()
    {
        $user = Auth::user();

        // Handle case where partner_id column doesn't exist yet
        try {
            $players = $user->managedPlayers()->paginate(15);
        } catch (\Exception $e) {
            // If partner_id column doesn't exist, show empty collection
            $players = collect();
        }

        return view('partner.players', compact('players'));
    }

    /**
     * Show specific player details
     */
    public function showPlayer($id)
    {
        $user = Auth::user();

        // Handle case where partner_id column doesn't exist yet
        try {
            $player = Player::where('partner_id', $user->id)->findOrFail($id);
        } catch (\Exception $e) {
            // If partner_id column doesn't exist, return 404
            abort(404, 'Player not found or migration not run yet.');
        }

        return view('partner.player_show', compact('player'));
    }

    /**
     * Create new player for the partner
     */
    public function createPlayer()
    {
        $programs = Program::all();
        return view('partner.player_create', compact('programs'));
    }

    /**
     * Store new player
     */
    public function storePlayer(Request $request)
    {
        $user = Auth::user();

        // Use consolidated basic validation from PlayerFormRequest
        $formRequest = new PlayerFormRequest();
        $request->validate($formRequest->basicRules(false));

        $data = $this->preparePlayerData($request);
        $data['partner_id'] = $user->id; // Link to partner
        $data['registration_status'] = 'Pending'; // All partner players start as pending

        Player::create($data);

        return redirect()->route('partner.players')->with('success', 'Player registered successfully and submitted for academy approval.');
    }

    /**
     * Edit player
     */
    public function editPlayer($id)
    {
        $user = Auth::user();

        // Handle case where partner_id column doesn't exist yet
        try {
            $player = Player::where('partner_id', $user->id)->findOrFail($id);
        } catch (\Exception $e) {
            // If partner_id column doesn't exist, return 404
            abort(404, 'Player not found or migration not run yet.');
        }

        $programs = Program::all();
        return view('partner.player_edit', compact('player', 'programs'));
    }

    /**
     * Update player
     */
    public function updatePlayer(Request $request, $id)
    {
        $user = Auth::user();

        // Handle case where partner_id column doesn't exist yet
        try {
            $player = Player::where('partner_id', $user->id)->findOrFail($id);
        } catch (\Exception $e) {
            // If partner_id column doesn't exist, return 404
            abort(404, 'Player not found or migration not run yet.');
        }

        // Use consolidated basic validation from PlayerFormRequest
        $formRequest = new PlayerFormRequest();
        $request->validate($formRequest->basicRules(true));

        $data = $this->preparePlayerData($request);

        $player->update($data);

        return redirect()->route('partner.players')->with('success', 'Player updated successfully.');
    }

    /**
     * Get analytics data for partner
     */
    public function analytics()
    {
        $user = Auth::user();

        // Handle case where partner_id column doesn't exist yet
        try {
            $players = $user->managedPlayers()->get();
        } catch (\Exception $e) {
            // If partner_id column doesn't exist, show empty analytics
            $players = collect();
        }

        $analytics = [
            'total_players' => $players->count(),
            'active_players' => $players->where('registration_status', 'Active')->count(),
            'pending_approvals' => $players->where('registration_status', 'Pending')->count(),
            'approved_players' => $players->filter(function($player) {
                return $player->isApproved();
            })->count(),
            'players_by_program' => $players->groupBy('program_id')->map(function($group) {
                return $group->count();
            }),
            'players_by_age_group' => $players->groupBy('age_group')->map(function($group) {
                return $group->count();
            }),
            'recent_registrations' => $players->sortByDesc('created_at')->take(10),
        ];

        return view('partner.analytics', compact('analytics'));
    }

    /**
     * Export player data
     */
    public function exportPlayers()
    {
        $user = Auth::user();

        // Handle case where partner_id column doesn't exist yet
        try {
            $players = $user->managedPlayers()->get();
        } catch (\Exception $e) {
            // If partner_id column doesn't exist, show empty export
            $players = collect();
        }

        // Simple CSV export
        $filename = 'partner_players_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($players) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Name', 'Position', 'Age', 'Status', 'Program',
                'Registration Date', 'Approval Status'
            ]);

            // CSV data
            foreach ($players as $player) {
                fputcsv($file, [
                    $player->full_name,
                    $player->position,
                    $player->age,
                    $player->registration_status,
                    $player->program->title ?? 'N/A',
                    $player->created_at->format('Y-m-d'),
                    $player->isApproved() ? 'Approved' : 'Pending'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
