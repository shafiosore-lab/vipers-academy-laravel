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
        // Check if user has access to this tournament's organization
        $this->authorizeTournamentAccess($tournament);

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
     * Passes dynamic location fields based on tournament's organization location level.
     */
    public function create(Tournament $tournament)
    {
        // Check if user has access to this tournament's organization
        $this->authorizeTournamentAccess($tournament);

        $user = Auth::user();
        $organizationId = $user->organization_id;

        // Get teams from user's organization
        $teams = Team::where('organization_id', $organizationId)
            ->active()
            ->orderBy('name')
            ->get();

        // Get available teams (not already registered in this tournament - only those with team_id)
        $registeredTeamIds = $tournament->teams()->whereNotNull('team_id')->pluck('team_id')->toArray();
        $availableTeams = $teams->whereNotIn('id', $registeredTeamIds);

        // Get location fields from tournament's organization
        $locationFields = [];
        $locationLevel = 'country';
        $locationOptions = [];

        if ($tournament->organization) {
            $locationFields = $tournament->organization->getLocationFields();
            $locationLevel = $tournament->organization->getEffectiveLocationLevel();
            $locationOptions = $tournament->organization->getLocationArray();
        }

        // Get country list
        $countries = \App\Models\Organization::COUNTRIES;

        return view('admin.tournaments.teams.create',
            compact('tournament', 'availableTeams', 'locationFields', 'locationLevel', 'locationOptions', 'countries'));
    }

    /**
     * Register a team in a tournament.
     * Handles dynamic location fields based on tournament's organization.
     */
    public function store(Request $request, Tournament $tournament)
    {
        // Check if user has access to this tournament's organization
        $this->authorizeTournamentAccess($tournament);

        \Log::info('AdminTournamentTeamController::store called', [
            'tournament_id' => $tournament->id,
            'team_id' => $request->team_id,
            'team_name' => $request->team_name,
            'user_id' => auth()->id()
        ]);

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

        // Get location fields from tournament's organization for validation
        $locationFields = [];
        if ($tournament->organization) {
            $locationFields = $tournament->organization->getLocationFields();
        }

        // Build validation rules dynamically
        $validationRules = [
            'team_id' => 'nullable|exists:teams,id',
            'team_name' => 'nullable|string|max:255',
            'team_contact_name' => 'nullable|string|max:255',
            'team_contact_email' => 'nullable|email|max:255',
            'team_contact_phone' => 'nullable|string|max:50',
        ];

        // Add location field validation rules
        foreach ($locationFields as $field) {
            if ($field === 'country') {
                $validationRules['country'] = 'required|string|max:100';
            } else {
                $validationRules[$field] = 'nullable|string|max:100';
            }
        }

        // Validate - either team_id OR team_name must be provided
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check that either team_id OR team_name is provided
        if (empty($request->team_id) && empty($request->team_name)) {
            return redirect()->back()
                ->with('error', 'Please select an existing team or enter a new team name.')
                ->withInput();
        }

        // Handle existing team registration
        $teamId = $request->team_id;
        $teamName = $request->team_name;

        if ($teamId) {
            // Check if team is already registered
            $existingRegistration = TournamentTeam::where('tournament_id', $tournament->id)
                ->where('team_id', $teamId)
                ->first();

            if ($existingRegistration) {
                return redirect()->back()
                    ->with('error', 'This team is already registered in the tournament.');
            }

            // Get team details
            $team = Team::findOrFail($teamId);
            $teamName = $team->name;
        } else {
            // Creating new independent team - check if team_name already exists as registration (case-insensitive)
            $existingRegistration = TournamentTeam::where('tournament_id', $tournament->id)
                ->whereRaw('LOWER(team_name) = ?', [strtolower($teamName)])
                ->first();

            if ($existingRegistration) {
                return redirect()->back()
                    ->with('error', 'A team with this name is already registered in the tournament.');
            }

            // For new independent teams, team_id remains null
            $teamId = null;
        }

        // Create tournament team registration with location data
        $tournamentTeam = TournamentTeam::create([
            'tournament_id' => $tournament->id,
            'team_id' => $teamId,
            'team_name' => $teamName,
            'team_contact_name' => $request->team_contact_name ?? $user->name,
            'team_contact_email' => $request->team_contact_email ?? $user->email,
            'team_contact_phone' => $request->team_contact_phone ?? $user->phone ?? '',
            'approval_status' => TournamentTeam::STATUS_PENDING,
            'registration_date' => now(),
            // Location fields
            'country' => $request->country ?? null,
            'county' => $request->county ?? null,
            'sub_county' => $request->sub_county ?? null,
            'ward' => $request->ward ?? null,
        ]);

        \Log::info('AdminTournamentTeamController::store: team created', [
            'tournament_team_id' => $tournamentTeam->id,
            'team_id' => $teamId,
            'team_name' => $teamName
        ]);

        // Check if tournament was closed (shuffled) and reopen for reshuffling
        $reshuffleMessage = '';
        if ($tournament->status === Tournament::STATUS_CLOSED && $tournament->matches()->count() > 0) {
            $result = $tournament->reopenForReshuffle();
            if ($result['reopened']) {
                $reshuffleMessage = ' ' . $result['message'];
            }
        }

        return redirect()->route('admin.tournaments.teams.index', $tournament->id)
            ->with('success', 'Team registered successfully. Pending approval.' . $reshuffleMessage);
    }

    /**
     * Display tournament team details.
     */
    public function show(Tournament $tournament, TournamentTeam $team)
    {
        // Check if user has access to this tournament's organization
        $this->authorizeTournamentAccess($tournament);

        $team->load(['team', 'tournament', 'squads.player']);

        return view('admin.tournaments.teams.show', compact('tournament', 'team'));
    }

    /**
     * Show the form for editing team registration.
     */
    public function edit(Tournament $tournament, TournamentTeam $team)
    {
        // Check if user has access to this tournament's organization
        $this->authorizeTournamentAccess($tournament);

        return view('admin.tournaments.teams.edit', compact('tournament', 'team'));
    }

    /**
     * Update team registration.
     */
    public function update(Request $request, Tournament $tournament, TournamentTeam $team)
    {
        // Check if user has access to this tournament's organization
        $this->authorizeTournamentAccess($tournament);

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
        // Check if user has access to this tournament's organization
        $this->authorizeTournamentAccess($tournament);

        // Check if team has players in squad
        if ($team->squads()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot remove team with registered players. Please remove players first.');
        }

        $teamName = $team->team_name ?? $team->team->name ?? 'Team';

        // Delete the team
        $team->delete();

        $message = "Team '{$teamName}' removed from tournament.";

        // Check if tournament was closed (shuffled) and reopen for reshuffling
        if ($tournament->status === Tournament::STATUS_CLOSED && $tournament->matches()->count() > 0) {
            $result = $tournament->reopenForReshuffle();
            if ($result['reopened']) {
                $message .= ' ' . $result['message'];
            }
        }

        return redirect()->route('admin.tournaments.teams.index', $tournament->id)
            ->with('success', $message);
    }

    /**
     * Approve team registration.
     */
    public function approve(Request $request, Tournament $tournament, TournamentTeam $team)
    {
        // Check if user has access to this tournament's organization
        $this->authorizeTournamentAccess($tournament);

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
        // Check if user has access to this tournament's organization
        $this->authorizeTournamentAccess($tournament);

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
        // Check if user has access to this tournament's organization
        $this->authorizeTournamentAccess($tournament);

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
        // Check if user has access to this tournament's organization
        $this->authorizeTournamentAccess($tournament);

        $user = Auth::user();
        $organizationId = $user->organization_id;

        // Get teams from user's organization
        $teams = Team::where('organization_id', $organizationId)
            ->active()
            ->orderBy('name')
            ->get();

        // Get registered team IDs (only those with team_id not null)
        $registeredTeamIds = $tournament->teams()->whereNotNull('team_id')->pluck('team_id')->toArray();

        // Filter out registered teams
        $availableTeams = $teams->whereNotIn('id', $registeredTeamIds);

        return response()->json($availableTeams);
    }

    /**
     * Download team template for bulk upload.
     * Dynamically generates columns based on tournament's organization location level.
     */
    public function downloadTeamTemplate(Tournament $tournament)
    {
        // Check if user has access to this tournament's organization
        $this->authorizeTournamentAccess($tournament);

        // Get location fields from tournament's organization
        $locationFields = [];
        $locationLevels = [];

        if ($tournament->organization) {
            $locationFields = $tournament->organization->getLocationFields();
            $locationLevels = $tournament->organization->getLocationArray();
        }

        // Build dynamic headers based on location level
        $headers = ['Team Name'];

        // Add location headers based on organization's location level
        foreach ($locationFields as $field) {
            $headers[] = ucfirst(str_replace('_', ' ', $field));
        }

        // Add contact fields
        $headers[] = 'Contact Name';
        $headers[] = 'Contact Email';
        $headers[] = 'Contact Phone';

        $sampleData = [];
        $sampleRow = ['FC Example United'];

        // Add sample location data
        if (in_array('country', $locationFields)) {
            $sampleRow[] = 'Kenya';
        }
        if (in_array('county', $locationFields)) {
            $sampleRow[] = 'Nairobi';
        }
        if (in_array('sub_county', $locationFields)) {
            $sampleRow[] = 'Westlands';
        }
        if (in_array('ward', $locationFields)) {
            $sampleRow[] = 'Kitisuru';
        }

        $sampleRow[] = 'John Doe';
        $sampleRow[] = 'john@example.com';
        $sampleRow[] = '+254712345678';

        $sampleData[] = $sampleRow;

        $filename = 'tournament_teams_template.csv';
        $handle = fopen('php://memory', 'w');

        // Add BOM for Excel UTF-8 compatibility
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        // Add headers
        fputcsv($handle, $headers);

        // Add sample data
        foreach ($sampleData as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Bulk upload teams from Excel/CSV file.
     * Optimized for performance with bulk inserts and N+1 prevention.
     */
    public function bulkUploadTeams(Request $request, Tournament $tournament)
    {
        // Check if user has access to this tournament's organization
        $this->authorizeTournamentAccess($tournament);

        \Log::info('bulkUploadTeams called', [
            'tournament_id' => $tournament->id,
            'file_name' => $request->file('teams_file')?->getClientOriginalName(),
            'user_id' => auth()->id()
        ]);

        $validator = Validator::make($request->all(), [
            'teams_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
            'auto_approve' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('teams_file');
        $autoApprove = $request->boolean('auto_approve', false);
        $batchSize = 500;

        // Read the file
        $extension = $file->getClientOriginalExtension();
        $path = $file->getRealPath();

        // Pre-load all organizations and ALL teams to avoid N+1 queries
        $organizations = \App\Models\Organization::all()->keyBy('name');
        $existingTeams = \App\Models\Team::with('organization')->get();

        // Get already registered team names and IDs for this tournament to prevent duplicates
        $registeredTeamIds = $tournament->teams()->whereNotNull('team_id')->pluck('team_id')->toArray();
        $registeredTeamNames = $tournament->teams()->whereNotNull('team_name')->pluck('team_name')->toArray();

        $insertData = [];
        $successCount = 0;
        $errorRows = [];
        $skippedDuplicates = 0;

        if (in_array($extension, ['xlsx', 'xls'])) {
            // Use PhpSpreadsheet for Excel files
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
            $worksheet = $spreadsheet->getActiveSheet();
            $totalRows = $worksheet->getHighestRow();

            // Skip header row
            $startRow = 2;

            // Process in batches to manage memory
            while ($startRow <= $totalRows) {
                $endRow = min($startRow + $batchSize - 1, $totalRows);
                $batchRows = $worksheet->rangeToArray("A{$startRow}:E{$endRow}");

                foreach ($batchRows as $rowIndex => $row) {
                    if (empty(array_filter($row))) continue;

                    $result = $this->processTeamRow($row, $tournament, $organizations, $existingTeams, $autoApprove, $registeredTeamIds, $registeredTeamNames);

                    if ($result['duplicate']) {
                        $skippedDuplicates++;
                    } elseif ($result['success']) {
                        $insertData[] = $result['data'];
                        $successCount++;
                        // Add to registered lists so subsequent rows don't duplicate
                        if ($result['data']['team_id']) {
                            $registeredTeamIds[] = $result['data']['team_id'];
                        }
                        if ($result['data']['team_name']) {
                            $registeredTeamNames[] = $result['data']['team_name'];
                        }

                        // Bulk insert when batch is full
                        if (count($insertData) >= $batchSize) {
                            \App\Models\TournamentTeam::insert($insertData);
                            $insertData = [];
                        }
                    } else {
                        $errorRows[] = $result['error'];
                    }
                }

                $startRow = $endRow + 1;
            }
        } else {
            // CSV file
            $handle = fopen($path, 'r');

            // Skip header row
            fgetcsv($handle, 1000, ',');

            $rowIndex = 1;
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $rowIndex++;
                if (empty(array_filter($row))) continue;

                $result = $this->processTeamRow($row, $tournament, $organizations, $existingTeams, $autoApprove, $registeredTeamIds, $registeredTeamNames);

                if ($result['duplicate']) {
                    $skippedDuplicates++;
                } elseif ($result['success']) {
                    $insertData[] = $result['data'];
                    $successCount++;
                    // Add to registered lists so subsequent rows don't duplicate
                    if ($result['data']['team_id']) {
                        $registeredTeamIds[] = $result['data']['team_id'];
                    }
                    if ($result['data']['team_name']) {
                        $registeredTeamNames[] = $result['data']['team_name'];
                    }

                    // Bulk insert when batch is full
                    if (count($insertData) >= $batchSize) {
                        \App\Models\TournamentTeam::insert($insertData);
                        $insertData = [];
                    }
                } else {
                    $errorRows[] = $result['error'];
                }
            }
            fclose($handle);
        }

        // Insert any remaining records
        if (!empty($insertData)) {
            \App\Models\TournamentTeam::insert($insertData);
        }

        \Log::info('bulkUploadTeams completed', [
            'success' => $successCount,
            'duplicates' => $skippedDuplicates,
            'errors' => count($errorRows)
        ]);

        $messageParts = [];
        if ($successCount > 0) {
            $messageParts[] = "{$successCount} team(s) uploaded successfully";
            if ($autoApprove) {
                $messageParts[] = "all auto-approved";
            }
        }
        if ($skippedDuplicates > 0) {
            $messageParts[] = "{$skippedDuplicates} duplicate(s) skipped";
        }

        $message = implode(". ", $messageParts) . ".";

        if (empty($messageParts)) {
            $message = "No teams were uploaded.";
        }

        if (!empty($errorRows)) {
            return redirect()->back()
                ->with('warning', $message . " Errors: " . implode("; ", array_slice($errorRows, 0, 3)));
        }

        // Check if tournament was closed (shuffled) and reopen for reshuffling
        if ($successCount > 0 && $tournament->status === Tournament::STATUS_CLOSED && $tournament->matches()->count() > 0) {
            $result = $tournament->reopenForReshuffle();
            if ($result['reopened']) {
                $message .= ' ' . $result['message'];
            }
        }

        return redirect()->back()
            ->with('success', $message);
    }

    /**
     * Process a single team row from the uploaded file.
     *
     * @param array $row Row data from Excel/CSV
     * @param Tournament $tournament Target tournament
     * @param \Illuminate\Support\Collection $organizations Pre-loaded organizations
     * @param \Illuminate\Support\Collection $existingTeams Pre-loaded teams (ALL teams)
     * @param bool $autoApprove Whether to auto-approve teams
     * @param array $registeredTeamIds Already registered team IDs
     * @param array $registeredTeamNames Already registered team names
     * @return array ['success' => bool, 'data' => array|null, 'error' => string|null, 'duplicate' => bool]
     */
    private function processTeamRow(array $row, Tournament $tournament, $organizations, $existingTeams, bool $autoApprove, array $registeredTeamIds = [], array $registeredTeamNames = []): array
    {
        try {
            $teamName = trim($row[0] ?? '');
            $contactName = trim($row[1] ?? '');
            $contactEmail = trim($row[2] ?? '');
            $contactPhone = trim($row[3] ?? '');
            $orgName = trim($row[4] ?? '');

            if (empty($teamName)) {
                return ['success' => false, 'data' => null, 'error' => 'Team name is required', 'duplicate' => false];
            }

            // Check for duplicate by team name (case-insensitive)
            $teamNameLower = strtolower($teamName);
            foreach ($registeredTeamNames as $registeredName) {
                if (strtolower($registeredName) === $teamNameLower) {
                    return ['success' => false, 'data' => null, 'error' => "Team '{$teamName}' already registered", 'duplicate' => true];
                }
            }

            // Find organization from pre-loaded data
            $teamId = null;
            $matchedOrg = null;

            if (!empty($orgName)) {
                // Try exact match
                $matchedOrg = $organizations->get($orgName);

                // Then try case-insensitive partial match
                if (!$matchedOrg) {
                    $matchedOrg = $organizations->first(function ($org) use ($orgName) {
                        return stripos($org->name, $orgName) !== false;
                    });
                }

                if ($matchedOrg) {
                    // Find team by name within the matched organization
                    $team = $existingTeams->filter(function ($team) use ($teamName, $matchedOrg) {
                        return $team->organization_id === $matchedOrg->id
                            && strtolower($team->name) === strtolower($teamName);
                    })->first();

                    if ($team) {
                        // Check if this team ID is already registered
                        if (!in_array($team->id, $registeredTeamIds)) {
                            $teamId = $team->id;
                        } else {
                            return ['success' => false, 'data' => null, 'error' => "Team '{$teamName}' already registered in this tournament", 'duplicate' => true];
                        }
                    }
                }
            }

            // If no team_id found but we have a team name, create as independent registration
            return [
                'success' => true,
                'data' => [
                    'tournament_id' => $tournament->id,
                    'team_id' => $teamId,
                    'team_name' => $teamName,
                    'team_contact_name' => $contactName,
                    'team_contact_email' => $contactEmail,
                    'team_contact_phone' => $contactPhone,
                    'approval_status' => $autoApprove ? \App\Models\TournamentTeam::STATUS_APPROVED : \App\Models\TournamentTeam::STATUS_PENDING,
                    'registration_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                'error' => null,
                'duplicate' => false,
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'data' => null, 'error' => $e->getMessage(), 'duplicate' => false];
        }
    }

    /**
     * Authorize access to tournament for org-admin users.
     * Super admins can access all tournaments, org-admins can only access their organization's tournaments.
     */
    private function authorizeTournamentAccess(Tournament $tournament)
    {
        $user = Auth::user();

        // Check if user is super-admin
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Check if user is org-admin and tournament belongs to their organization
        if ($user->hasRole('org-admin') && $user->organization_id) {
            if ($tournament->organization_id === $user->organization_id) {
                return true;
            }
        }

        // Deny access
        abort(403, 'You do not have permission to manage this tournament.');
    }
}
