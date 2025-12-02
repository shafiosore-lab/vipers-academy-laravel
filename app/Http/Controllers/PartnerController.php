<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerController extends Controller
{
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

        $request->validate([
            // Basic Information
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'place_of_birth' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'position' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',

            // Contact Information
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:255',

            // Guardian Information
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'guardian_relationship' => 'required|string|max:255',

            // Medical Information
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'blood_type' => 'nullable|string|max:10',

            // Academic Information
            'school_name' => 'nullable|string|max:255',
            'school_grade' => 'nullable|string|max:255',

            // Physical Information
            'height_cm' => 'nullable|numeric|min:50|max:250',
            'weight_kg' => 'nullable|numeric|min:20|max:200',
            'dominant_foot' => 'nullable|in:Left,Right,Both',
        ]);

        $data = $request->all();
        $data['partner_id'] = $user->id; // Link to partner
        $data['registration_status'] = 'Pending'; // All partner players start as pending

        // Create full name from first and last name
        $data['name'] = $data['first_name'] . ' ' . $data['last_name'];

        // Calculate age from date of birth
        if (isset($data['date_of_birth'])) {
            $data['age'] = \Carbon\Carbon::parse($data['date_of_birth'])->age;
        }

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

        $request->validate([
            // Basic Information
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'place_of_birth' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'position' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',

            // Contact Information
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:255',

            // Guardian Information
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'guardian_relationship' => 'required|string|max:255',

            // Medical Information
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'blood_type' => 'nullable|string|max:10',

            // Academic Information
            'school_name' => 'nullable|string|max:255',
            'school_grade' => 'nullable|string|max:255',

            // Physical Information
            'height_cm' => 'nullable|numeric|min:50|max:250',
            'weight_kg' => 'nullable|numeric|min:20|max:200',
            'dominant_foot' => 'nullable|in:Left,Right,Both',
        ]);

        $data = $request->all();

        // Update full name from first and last name
        $data['name'] = $data['first_name'] . ' ' . $data['last_name'];

        // Recalculate age from date of birth
        if (isset($data['date_of_birth'])) {
            $data['age'] = \Carbon\Carbon::parse($data['date_of_birth'])->age;
        }

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
                    $player->name,
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
