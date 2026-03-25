<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TournamentSquad;
use App\Models\TeamRegistration;
use App\Models\TournamentTeamAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TeamDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    /**
     * Get the team admin's team.
     */
    protected function getTeam()
    {
        // Get team admin relationship
        $teamAdmin = TournamentTeamAdmin::where('user_id', $this->user->id)->first();

        if (!$teamAdmin) {
            return null;
        }

        return $teamAdmin->team;
    }

    /**
     * Get the team admin's tournament team (for a specific tournament).
     */
    protected function getTournamentTeam(Tournament $tournament)
    {
        $team = $this->getTeam();

        if (!$team) {
            return null;
        }

        return TournamentTeam::where('tournament_id', $tournament->id)
            ->where('team_id', $team->id)
            ->first();
    }

    /**
     * Display the team dashboard home.
     */
    public function index()
    {
        $teamAdmin = TournamentTeamAdmin::where('user_id', $this->user->id)
            ->with('team')
            ->first();

        if (!$teamAdmin) {
            return view('team.no-team')->with('message', 'You are not assigned to any team.');
        }

        $team = $teamAdmin->team;

        // Get tournaments where this team is registered
        $tournamentTeams = TournamentTeam::where('team_id', $team->id)
            ->with('tournament')
            ->get();

        return view('team.dashboard', compact('team', 'tournamentTeams', 'teamAdmin'));
    }

    /**
     * Display tournaments available for registration.
     */
    public function tournaments()
    {
        $tournaments = Tournament::public()
            ->whereIn('status', [Tournament::STATUS_OPEN])
            ->whereNull('registration_deadline')
            ->orWhere(function ($query) {
                $query->where('status', Tournament::STATUS_OPEN)
                    ->where('registration_deadline', '>', now());
            })
            ->with('organization')
            ->orderBy('registration_deadline', 'asc')
            ->paginate(12);

        return view('team.tournaments', compact('tournaments'));
    }

    /**
     * Show team registration form for a tournament.
     */
    public function showRegister(Tournament $tournament)
    {
        // Check if registration is open
        if (!$tournament->canRegister()) {
            return redirect()->route('team.tournaments')
                ->with('error', 'Registration is not open for this tournament.');
        }

        // Get available teams from user's organization (if they have a team assigned)
        $teamAdmin = TournamentTeamAdmin::where('user_id', $this->user->id)
            ->with('team')
            ->first();

        $team = $teamAdmin ? $teamAdmin->team : null;

        // Get all teams in the user's organization for selection
        $organizationId = $this->user->organization_id;
        $availableTeams = [];

        if ($organizationId) {
            $availableTeams = \App\Models\Team::where('organization_id', $organizationId)
                ->active()
                ->orderBy('name')
                ->get();
        }

        // Check if already registered
        if ($team) {
            $existingRegistration = TournamentTeam::where('tournament_id', $tournament->id)
                ->where('team_id', $team->id)
                ->first();

            if ($existingRegistration) {
                return redirect()->route('team.tournament.show', $tournament->id)
                    ->with('info', 'Your team is already registered for this tournament.');
            }
        }

        return view('team.register', compact('tournament', 'team', 'availableTeams'));
    }

    /**
     * Register team for a tournament.
     */
    public function register(Request $request, Tournament $tournament)
    {
        // Validate tournament is open
        if (!$tournament->canRegister()) {
            return redirect()->back()
                ->with('error', 'Registration is not open for this tournament.');
        }

        // Validate: either team_id OR new_team_name must be provided
        $validator = Validator::make($request->all(), [
            'team_id' => 'nullable|exists:teams,id',
            'new_team_name' => 'nullable|string|max:255',
            'team_contact_name' => 'required|string|max:255',
            'team_contact_email' => 'required|email|max:255',
            'team_contact_phone' => 'required|string|max:50',
        ]);

        // Custom validation: at least one of team_id or new_team_name
        $validator->after(function ($validator) use ($request) {
            if (empty($request->team_id) && empty($request->new_team_name)) {
                $validator->errors()->add('team_id', 'Please select an existing team or create a new team.');
                $validator->errors()->add('new_team_name', 'Please select an existing team or create a new team.');
            }
            if (!empty($request->team_id) && !empty($request->new_team_name)) {
                $validator->errors()->add('team_id', 'Please select only one option - either existing team OR new team.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get or create team
        $team = null;
        $isNewTeam = false;

        if ($request->team_id) {
            // Use existing team
            $team = \App\Models\Team::find($request->team_id);
        } elseif ($request->new_team_name) {
            // Create new team
            $organizationId = $this->user->organization_id;

            $team = \App\Models\Team::create([
                'organization_id' => $organizationId,
                'name' => $request->new_team_name,
                'status' => 'active',
                'created_by' => $this->user->id,
            ]);
            $isNewTeam = true;
        }

        if (!$team) {
            return redirect()->back()
                ->with('error', 'Unable to process team registration.')
                ->withInput();
        }

        // Check if already registered
        $existingRegistration = TournamentTeam::where('tournament_id', $tournament->id)
            ->where('team_id', $team->id)
            ->first();

        if ($existingRegistration) {
            return redirect()->route('team.tournament.show', $tournament->id)
                ->with('info', 'This team is already registered for this tournament.');
        }

        // Create tournament team registration
        $tournamentTeam = TournamentTeam::create([
            'tournament_id' => $tournament->id,
            'team_id' => $team->id,
            'team_name' => $team->name,
            'team_contact_name' => $request->team_contact_name,
            'team_contact_email' => $request->team_contact_email,
            'team_contact_phone' => $request->team_contact_phone,
            'approval_status' => TournamentTeam::STATUS_PENDING,
            'registration_date' => now(),
        ]);

        // If new team, assign current user as team admin
        if ($isNewTeam) {
            TournamentTeamAdmin::create([
                'team_id' => $team->id,
                'user_id' => $this->user->id,
                'role' => 'coach',
                'is_primary' => true,
            ]);
        }

        return redirect()->route('team.tournament.show', $tournament->id)
            ->with('success', 'Team registered successfully! Your registration is pending approval.');
    }

    /**
     * Show tournament team dashboard.
     */
    public function tournamentShow(Tournament $tournament)
    {
        $team = $this->getTeam();

        if (!$team) {
            return redirect()->route('team.dashboard')
                ->with('error', 'You need to be assigned to a team first.');
        }

        $tournamentTeam = $this->getTournamentTeam($tournament);

        if (!$tournamentTeam) {
            return redirect()->route('team.tournaments')
                ->with('error', 'Your team is not registered for this tournament.');
        }

        $tournamentTeam->load(['tournament', 'squads.player']);

        return view('team.tournament-dashboard', compact('tournament', 'tournamentTeam', 'team'));
    }

    /**
     * Show player list for the team in a tournament.
     */
    public function players(Tournament $tournament)
    {
        $tournamentTeam = $this->getTournamentTeam($tournament);

        if (!$tournamentTeam) {
            return redirect()->route('team.tournaments')
                ->with('error', 'Your team is not registered for this tournament.');
        }

        $tournamentTeam->load(['squads.player']);

        return view('team.players.index', compact('tournament', 'tournamentTeam'));
    }

    /**
     * Show form to add a new player.
     */
    public function showAddPlayer(Tournament $tournament)
    {
        $tournamentTeam = $this->getTournamentTeam($tournament);

        if (!$tournamentTeam) {
            return redirect()->route('team.tournaments')
                ->with('error', 'Your team is not registered for this tournament.');
        }

        // Check if squad is locked
        if ($tournamentTeam->isSquadLocked()) {
            return redirect()->route('team.players', $tournament->id)
                ->with('error', 'Squad is locked. Cannot add new players.');
        }

        // Check squad limit
        if (!$tournamentTeam->meetsSquadLimit()) {
            return redirect()->route('team.players', $tournament->id)
                ->with('error', 'Squad has reached the maximum limit.');
        }

        return view('team.players.create', compact('tournament', 'tournamentTeam'));
    }

    /**
     * Register a new player.
     */
    public function storePlayer(Request $request, Tournament $tournament)
    {
        $tournamentTeam = $this->getTournamentTeam($tournament);

        if (!$tournamentTeam) {
            return redirect()->route('team.tournaments')
                ->with('error', 'Your team is not registered for this tournament.');
        }

        // Check if squad is locked
        if ($tournamentTeam->isSquadLocked()) {
            return redirect()->route('team.players', $tournament->id)
                ->with('error', 'Squad is locked. Cannot add new players.');
        }

        // Check squad limit
        if (!$tournamentTeam->meetsSquadLimit()) {
            return redirect()->route('team.players', $tournament->id)
                ->with('error', 'Squad has reached the maximum limit.');
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            // Player identification
            'id_type' => 'required|in:national_id,passport,birth_certificate,other',
            'id_number' => 'required|string|max:50|unique:players,id_number',

            // Name fields (three names required)
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',

            // Location
            'city' => 'required|string|max:100',

            // Age - date of birth for exact age calculation
            'date_of_birth' => 'required|date|before:today|after:1990-01-01',

            // Gender
            'gender' => 'required|in:male,female',

            // Position
            'position' => 'nullable|string|max:50',

            // Jersey number
            'jersey_number' => 'nullable|integer|min:1|max:99',

            // Passport photo
            'passport_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=200,min_height=200',
        ], [
            'id_number.unique' => 'A player with this ID number already exists in the system.',
            'passport_photo.required' => 'Passport photograph is required for identity verification.',
            'passport_photo.dimensions' => 'Photo must be at least 200x200 pixels.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Calculate exact age
        $dateOfBirth = Carbon::parse($request->date_of_birth);
        $age = $dateOfBirth->age;

        // Handle passport photo upload
        $passportPhotoPath = null;
        if ($request->hasFile('passport_photo')) {
            $photo = $request->file('passport_photo');
            $filename = time() . '_' . str_replace(' ', '_', $request->first_name) . '_' . str_replace(' ', '_', $request->last_name) . '.' . $photo->getClientOriginalExtension();

            // Store in secure directory
            $passportPhotoPath = $photo->storeAs('player-photos/secure', $filename, 'private');
        }

        // Get team organization
        $team = $tournamentTeam->team;
        $organizationId = $team->organization_id;

        // Create player
        $player = Player::create([
            'organization_id' => $organizationId,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'id_type' => $request->id_type,
            'id_number' => $request->id_number,
            'city' => $request->city,
            'date_of_birth' => $request->date_of_birth,
            'age' => $age,
            'gender' => $request->gender,
            'position' => $request->position,
            'passport_photo_path' => $passportPhotoPath,
            'registration_status' => 'registered',
            'approval_type' => 'pending',
            'status' => 'active',
            'registered_by' => $this->user->id,
            'registered_at' => now(),
        ]);

        // Add player to tournament squad
        $squad = TournamentSquad::create([
            'tournament_team_id' => $tournamentTeam->id,
            'player_id' => $player->id,
            'jersey_number' => $request->jersey_number,
            'position' => $request->position,
            'verification_status' => TournamentSquad::STATUS_PENDING,
            'registration_date' => now(),
        ]);

        return redirect()->route('team.players', $tournament->id)
            ->with('success', "Player {$player->full_name} registered successfully! Registration is pending verification.");
    }

    /**
     * Show form to edit a player.
     */
    public function showEditPlayer(Tournament $tournament, TournamentSquad $squad)
    {
        $tournamentTeam = $this->getTournamentTeam($tournament);

        if (!$tournamentTeam || $squad->tournament_team_id !== $tournamentTeam->id) {
            return redirect()->route('team.players', $tournament->id)
                ->with('error', 'Player not found in your squad.');
        }

        // Check if can edit
        if ($squad->isLocked()) {
            return redirect()->route('team.players', $tournament->id)
                ->with('error', 'Cannot edit locked squad entry.');
        }

        $squad->load('player');

        return view('team.players.edit', compact('tournament', 'tournamentTeam', 'squad'));
    }

    /**
     * Update player information.
     */
    public function updatePlayer(Request $request, Tournament $tournament, TournamentSquad $squad)
    {
        $tournamentTeam = $this->getTournamentTeam($tournament);

        if (!$tournamentTeam || $squad->tournament_team_id !== $tournamentTeam->id) {
            return redirect()->route('team.players', $tournament->id)
                ->with('error', 'Player not found in your squad.');
        }

        // Check if can edit
        if ($squad->isLocked()) {
            return redirect()->route('team.players', $tournament->id)
                ->with('error', 'Cannot edit locked squad entry.');
        }

        $player = $squad->player;

        // Validate request
        $validator = Validator::make($request->all(), [
            'id_type' => 'sometimes|in:national_id,passport,birth_certificate,other',
            'id_number' => 'sometimes|string|max:50|unique:players,id_number,' . $player->id,
            'first_name' => 'sometimes|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'city' => 'sometimes|string|max:100',
            'date_of_birth' => 'sometimes|date|before:today|after:1990-01-01',
            'gender' => 'sometimes|in:male,female',
            'position' => 'nullable|string|max:50',
            'jersey_number' => 'nullable|integer|min:1|max:99',
            'passport_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=200,min_height=200',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Calculate exact age if date of birth changed
        $age = $player->age;
        if ($request->has('date_of_birth')) {
            $age = Carbon::parse($request->date_of_birth)->age;
        }

        // Handle passport photo upload if provided
        $passportPhotoPath = $player->passport_photo_path;
        if ($request->hasFile('passport_photo')) {
            // Delete old photo if exists
            if ($passportPhotoPath) {
                Storage::disk('private')->delete($passportPhotoPath);
            }

            $photo = $request->file('passport_photo');
            $filename = time() . '_' . str_replace(' ', '_', $request->first_name ?? $player->first_name) . '_' . str_replace(' ', '_', $request->last_name ?? $player->last_name) . '.' . $photo->getClientOriginalExtension();
            $passportPhotoPath = $photo->storeAs('player-photos/secure', $filename, 'private');
        }

        // Update player
        $player->update(array_filter([
            'id_type' => $request->id_type,
            'id_number' => $request->id_number,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'city' => $request->city,
            'date_of_birth' => $request->date_of_birth,
            'age' => $age,
            'gender' => $request->gender,
            'position' => $request->position,
            'passport_photo_path' => $passportPhotoPath,
        ]));

        // Update squad entry
        $squad->update(array_filter([
            'jersey_number' => $request->jersey_number,
            'position' => $request->position,
        ]));

        return redirect()->route('team.players', $tournament->id)
            ->with('success', 'Player information updated successfully!');
    }

    /**
     * Remove player from squad.
     */
    public function destroyPlayer(Tournament $tournament, TournamentSquad $squad)
    {
        $tournamentTeam = $this->getTournamentTeam($tournament);

        if (!$tournamentTeam || $squad->tournament_team_id !== $tournamentTeam->id) {
            return redirect()->route('team.players', $tournament->id)
                ->with('error', 'Player not found in your squad.');
        }

        // Check if can edit
        if ($squad->isLocked()) {
            return redirect()->route('team.players', $tournament->id)
                ->with('error', 'Cannot remove locked squad entry.');
        }

        $playerName = $squad->player->full_name;
        $squad->delete();

        return redirect()->route('team.players', $tournament->id)
            ->with('success', "Player {$playerName} removed from squad.");
    }

    /**
     * Show bulk upload form.
     */
    public function showBulkUpload(Tournament $tournament)
    {
        $tournamentTeam = $this->getTournamentTeam($tournament);

        if (!$tournamentTeam) {
            return redirect()->route('team.tournaments')
                ->with('error', 'Your team is not registered for this tournament.');
        }

        // Check if squad is locked
        if ($tournamentTeam->isSquadLocked()) {
            return redirect()->route('team.players', $tournament->id)
                ->with('error', 'Squad is locked. Cannot upload players.');
        }

        return view('team.players.bulk-upload', compact('tournament', 'tournamentTeam'));
    }

    /**
     * Bulk upload players via CSV.
     */
    public function bulkUpload(Request $request, Tournament $tournament)
    {
        $tournamentTeam = $this->getTournamentTeam($tournament);

        if (!$tournamentTeam) {
            return redirect()->route('team.tournaments')
                ->with('error', 'Your team is not registered for this tournament.');
        }

        // Check if squad is locked
        if ($tournamentTeam->isSquadLocked()) {
            return redirect()->route('team.players', $tournament->id)
                ->with('error', 'Squad is locked. Cannot upload players.');
        }

        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('csv_file');
        $handle = fopen($file->path(), 'r');
        $header = fgetcsv($handle);

        // Expected columns for new player registration
        $expectedColumns = ['first_name', 'middle_name', 'last_name', 'id_type', 'id_number', 'date_of_birth', 'gender', 'city', 'position', 'jersey_number'];
        $header = array_map('strtolower', array_map('trim', $header));

        if (array_diff($expectedColumns, $header)) {
            fclose($handle);
            return redirect()->back()
                ->with('error', 'Invalid CSV format. Required columns: ' . implode(', ', $expectedColumns));
        }

        $team = $tournamentTeam->team;
        $organizationId = $team->organization_id;

        $addedCount = 0;
        $skippedCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);

                // Skip empty rows
                if (empty(trim($data['first_name'] ?? ''))) {
                    continue;
                }

                // Validate required fields
                if (empty(trim($data['first_name'])) || empty(trim($data['last_name'])) ||
                    empty(trim($data['id_number'])) || empty(trim($data['id_type'])) ||
                    empty(trim($data['date_of_birth'])) || empty(trim($data['gender'])) ||
                    empty(trim($data['city']))) {
                    $skippedCount++;
                    $errors[] = "Missing required fields: " . ($data['first_name'] ?? 'Unknown');
                    continue;
                }

                // Check if player with this ID already exists
                $existingPlayer = Player::where('id_number', trim($data['id_number']))->first();

                if ($existingPlayer) {
                    // Check if player already in this tournament
                    $existingSquad = TournamentSquad::where('tournament_team_id', $tournamentTeam->id)
                        ->where('player_id', $existingPlayer->id)
                        ->first();

                    if ($existingSquad) {
                        $skippedCount++;
                        $errors[] = "Player already in squad: {$existingPlayer->full_name}";
                        continue;
                    }

                    // Use existing player
                    $player = $existingPlayer;
                } else {
                    // Calculate age
                    $dateOfBirth = Carbon::parse(trim($data['date_of_birth']));
                    $age = $dateOfBirth->age;

                    // Create new player
                    $player = Player::create([
                        'organization_id' => $organizationId,
                        'first_name' => trim($data['first_name']),
                        'middle_name' => isset($data['middle_name']) ? trim($data['middle_name']) : null,
                        'last_name' => trim($data['last_name']),
                        'id_type' => trim($data['id_type']),
                        'id_number' => trim($data['id_number']),
                        'date_of_birth' => $data['date_of_birth'],
                        'age' => $age,
                        'gender' => trim($data['gender']),
                        'city' => trim($data['city']),
                        'position' => isset($data['position']) ? trim($data['position']) : null,
                        'registration_status' => 'registered',
                        'approval_type' => 'pending',
                        'status' => 'active',
                        'registered_by' => $this->user->id,
                        'registered_at' => now(),
                    ]);
                }

                // Check squad limit
                if ($tournamentTeam->squads()->count() >= $tournament->squad_limit) {
                    $errors[] = "Squad limit reached. Stopped at: {$player->full_name}";
                    break;
                }

                // Add to squad
                TournamentSquad::create([
                    'tournament_team_id' => $tournamentTeam->id,
                    'player_id' => $player->id,
                    'jersey_number' => isset($data['jersey_number']) ? (int)trim($data['jersey_number']) : null,
                    'position' => isset($data['position']) ? trim($data['position']) : null,
                    'verification_status' => TournamentSquad::STATUS_PENDING,
                    'registration_date' => now(),
                ]);

                $addedCount++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error processing CSV: ' . $e->getMessage());
        }

        fclose($handle);

        $message = "Successfully registered {$addedCount} players. Skipped {$skippedCount} players.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode('; ', array_slice($errors, 0, 5));
        }

        return redirect()->route('team.players', $tournament->id)
            ->with($addedCount > 0 ? 'success' : 'warning', $message);
    }

    /**
     * Download CSV template for bulk upload.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="player_registration_template.csv"',
        ];

        $callback = function() {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, ['first_name', 'middle_name', 'last_name', 'id_type', 'id_number', 'date_of_birth', 'gender', 'city', 'position', 'jersey_number']);

            // Example rows
            fputcsv($handle, ['John', 'James', 'Doe', 'national_id', '12345678', '2010-01-15', 'male', 'Nairobi', 'Midfielder', '10']);
            fputcsv($handle, ['Jane', '', 'Smith', 'passport', 'AB123456', '2011-03-20', 'female', 'Mombasa', 'Forward', '9']);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * View player details.
     */
    public function viewPlayer(Tournament $tournament, TournamentSquad $squad)
    {
        $tournamentTeam = $this->getTournamentTeam($tournament);

        if (!$tournamentTeam || $squad->tournament_team_id !== $tournamentTeam->id) {
            return redirect()->route('team.players', $tournament->id)
                ->with('error', 'Player not found in your squad.');
        }

        $squad->load('player');

        return view('team.players.view', compact('tournament', 'tournamentTeam', 'squad'));
    }

    /**
     * Export squad to CSV.
     */
    public function exportPlayers(Tournament $tournament)
    {
        $tournamentTeam = $this->getTournamentTeam($tournament);

        if (!$tournamentTeam) {
            return redirect()->route('team.tournaments')
                ->with('error', 'Your team is not registered for this tournament.');
        }

        $tournamentTeam->load(['squads.player']);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$tournamentTeam->team_name}_players.csv\"",
        ];

        $callback = function() use ($tournamentTeam) {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'First Name', 'Middle Name', 'Last Name', 'ID Type', 'ID Number',
                'Date of Birth', 'Age', 'Gender', 'City', 'Position',
                'Jersey Number', 'Status', 'Registration Date'
            ]);

            foreach ($tournamentTeam->squads as $squad) {
                $player = $squad->player;
                fputcsv($handle, [
                    $player->first_name,
                    $player->middle_name,
                    $player->last_name,
                    $player->id_type,
                    $player->id_number,
                    $player->date_of_birth ? $player->date_of_birth->format('Y-m-d') : '',
                    $player->age,
                    $player->gender,
                    $player->city,
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
}
