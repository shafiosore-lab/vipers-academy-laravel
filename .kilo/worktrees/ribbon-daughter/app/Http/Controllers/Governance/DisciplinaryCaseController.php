<?php

namespace App\Http\Controllers\Governance;

use App\Http\Controllers\Controller;
use App\Models\DisciplinaryCase;
use App\Models\Player;
use App\Models\TournamentMatch;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\Organization;
use App\Models\PlayerSuspension;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DisciplinaryCaseController extends Controller
{
    /**
     * DisciplinaryCaseController constructor.
     */
    public function __construct()
    {
        // Apply authorization check to all methods
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            $permissionService = new PermissionService();

            if (!$permissionService->hasRoleOrHigher($user, 'admin')) {
                abort(403, 'Unauthorized access to disciplinary case management');
            }

            return $next($request);
        });
    }

    /**
     * Display disciplinary dashboard
     */
    public function index()
    {
        $organization = Auth::user()->organization;

        $stats = [
            'total_cases' => DisciplinaryCase::byOrganization($organization->id)->count(),
            'open_cases' => DisciplinaryCase::byOrganization($organization->id)->open()->count(),
            'closed_cases' => DisciplinaryCase::byOrganization($organization->id)->closed()->count(),
            'active_suspensions' => PlayerSuspension::where('organization_id', $organization->id)
                ->active()
                ->count(),
        ];

        return view('admin.governance.disciplinary.index', compact('stats'));
    }

    /**
     * Display disciplinary cases
     */
    public function cases()
    {
        $organization = Auth::user()->organization;
        $cases = DisciplinaryCase::byOrganization($organization->id)
            ->with(['player', 'team', 'match', 'tournament'])
            ->latest()
            ->paginate(20);

        return view('admin.governance.disciplinary.cases', compact('cases'));
    }

    /**
     * Show create case form
     */
    public function createCase()
    {
        $players = Player::where('organization_id', Auth::user()->organization->id)->get();
        $teams = Team::where('organization_id', Auth::user()->organization->id)->get();
        $tournaments = Tournament::where('organization_id', Auth::user()->organization->id)->get();
        $matches = TournamentMatch::whereHas('tournament', function($query) {
            $query->where('organization_id', Auth::user()->organization->id);
        })->get();

        return view('admin.governance.disciplinary.create-case', compact('players', 'teams', 'tournaments', 'matches'));
    }

    /**
     * Store new disciplinary case
     */
    public function storeCase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:players,id',
            'team_id' => 'nullable|exists:teams,id',
            'tournament_id' => 'nullable|exists:tournaments,id',
            'match_id' => 'nullable|exists:tournament_matches,id',
            'incident_type' => 'required|string',
            'description' => 'required|string|max:2000',
            'incident_date' => 'required|date',
            'incident_location' => 'nullable|string|max:500',
            'evidence' => 'nullable|array',
            'witness_statements' => 'nullable|array',
            'card_shown' => 'nullable|string|max:50',
            'offense_type' => 'required|string',
            'reported_by' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $organization = Auth::user()->organization;

        $case = DisciplinaryCase::create([
            'player_id' => $request->player_id,
            'organization_id' => $organization->id,
            'team_id' => $request->team_id,
            'tournament_id' => $request->tournament_id,
            'case_number' => DisciplinaryCase::generateCaseNumber(),
            'incident_type' => $request->incident_type,
            'description' => $request->description,
            'incident_date' => $request->incident_date,
            'incident_location' => $request->incident_location,
            'match_id' => $request->match_id,
            'evidence' => $request->evidence,
            'witness_statements' => $request->witness_statements,
            'card_shown' => $request->card_shown,
            'offense_type' => $request->offense_type,
            'status' => DisciplinaryCase::STATUS_OPEN,
            'reported_by' => $request->reported_by ?? Auth::user()->name,
        ]);

        return redirect()->route('admin.disciplinary.cases.show', $case)
            ->with('success', 'Disciplinary case created successfully.');
    }

    /**
     * Show disciplinary case
     */
    public function showCase(DisciplinaryCase $case)
    {
        $this->authorizeCaseAccess($case);

        $case->load(['player', 'team', 'match', 'tournament', 'suspension', 'appeal']);

        return view('admin.governance.disciplinary.show-case', compact('case'));
    }

    /**
     * Show edit case form
     */
    public function editCase(DisciplinaryCase $case)
    {
        $this->authorizeCaseAccess($case);

        $players = Player::where('organization_id', Auth::user()->organization->id)->get();
        $teams = Team::where('organization_id', Auth::user()->organization->id)->get();
        $tournaments = Tournament::where('organization_id', Auth::user()->organization->id)->get();
        $matches = TournamentMatch::whereHas('tournament', function($query) {
            $query->where('organization_id', Auth::user()->organization->id);
        })->get();

        return view('admin.governance.disciplinary.edit-case', compact('case', 'players', 'teams', 'tournaments', 'matches'));
    }

    /**
     * Update disciplinary case
     */
    public function updateCase(Request $request, DisciplinaryCase $case)
    {
        $this->authorizeCaseAccess($case);

        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:players,id',
            'team_id' => 'nullable|exists:teams,id',
            'tournament_id' => 'nullable|exists:tournaments,id',
            'match_id' => 'nullable|exists:tournament_matches,id',
            'incident_type' => 'required|string',
            'description' => 'required|string|max:2000',
            'incident_date' => 'required|date',
            'incident_location' => 'nullable|string|max:500',
            'evidence' => 'nullable|array',
            'witness_statements' => 'nullable|array',
            'card_shown' => 'nullable|string|max:50',
            'offense_type' => 'required|string',
            'reported_by' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $case->update([
            'player_id' => $request->player_id,
            'team_id' => $request->team_id,
            'tournament_id' => $request->tournament_id,
            'incident_type' => $request->incident_type,
            'description' => $request->description,
            'incident_date' => $request->incident_date,
            'incident_location' => $request->incident_location,
            'match_id' => $request->match_id,
            'evidence' => $request->evidence,
            'witness_statements' => $request->witness_statements,
            'card_shown' => $request->card_shown,
            'offense_type' => $request->offense_type,
            'reported_by' => $request->reported_by,
        ]);

        return redirect()->route('admin.disciplinary.cases.show', $case)
            ->with('success', 'Disciplinary case updated successfully.');
    }

    /**
     * Delete disciplinary case
     */
    public function destroyCase(DisciplinaryCase $case)
    {
        $this->authorizeCaseAccess($case);

        // Delete associated suspension and appeal if they exist
        if ($case->suspension) {
            $case->suspension->delete();
        }

        if ($case->appeal) {
            $case->appeal->delete();
        }

        $case->delete();

        return redirect()->route('admin.disciplinary.cases')
            ->with('success', 'Disciplinary case deleted successfully.');
    }

    /**
     * Start case review
     */
    public function startReview(DisciplinaryCase $case)
    {
        $this->authorizeCaseAccess($case);

        $case->startReview();

        return redirect()->route('admin.disciplinary.cases.show', $case)
            ->with('success', 'Case review started.');
    }

    /**
     * Make decision on case
     */
    public function makeDecision(Request $request, DisciplinaryCase $case)
    {
        $this->authorizeCaseAccess($case);

        $validator = Validator::make($request->all(), [
            'decision' => 'required|string',
            'reason' => 'required|string|max:1000',
            'suspension_matches' => 'nullable|integer|min:0',
            'suspension_days' => 'nullable|integer|min:0',
            'fine_amount' => 'nullable|numeric|min:0',
            'effective_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $case->makeDecision(
            $request->decision,
            $request->reason,
            Auth::id(),
            $request->suspension_matches,
            $request->suspension_days,
            $request->fine_amount,
            $request->effective_date
        );

        return redirect()->route('admin.disciplinary.cases.show', $case)
            ->with('success', 'Decision made successfully.');
    }

    /**
     * Create appeal for case
     */
    public function createAppeal(Request $request, DisciplinaryCase $case)
    {
        $this->authorizeCaseAccess($case);

        if (!$case->canAppeal()) {
            return redirect()->route('admin.disciplinary.cases.show', $case)
                ->with('error', 'This case cannot be appealed.');
        }

        $validator = Validator::make($request->all(), [
            'grounds' => 'required|string|max:1000',
            'evidence' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $appeal = \App\Models\Appeal::submit(
            $case->id,
            $case->player_id,
            $case->organization_id,
            $request->grounds,
            $request->evidence,
            Auth::id()
        );

        return redirect()->route('admin.disciplinary.cases.show', $case)
            ->with('success', 'Appeal created successfully.');
    }

    /**
     * Show case history
     */
    public function caseHistory(DisciplinaryCase $case)
    {
        $this->authorizeCaseAccess($case);

        $case->load(['player', 'team', 'match', 'tournament', 'suspension', 'appeal']);

        return view('admin.governance.disciplinary.history', compact('case'));
    }

    /**
     * Show case documents
     */
    public function caseDocuments(DisciplinaryCase $case)
    {
        $this->authorizeCaseAccess($case);

        return view('admin.governance.disciplinary.documents', compact('case'));
    }

    /**
     * Add evidence to case
     */
    public function addEvidence(Request $request, DisciplinaryCase $case)
    {
        $this->authorizeCaseAccess($case);

        $validator = Validator::make($request->all(), [
            'evidence_type' => 'required|string',
            'evidence_description' => 'required|string|max:500',
            'evidence_file' => 'nullable|file',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $evidence = $case->evidence ?? [];
        $evidence[] = [
            'type' => $request->evidence_type,
            'description' => $request->evidence_description,
            'added_at' => now()->toDateTimeString(),
            'added_by' => Auth::user()->name,
        ];

        $case->update(['evidence' => $evidence]);

        return redirect()->route('admin.disciplinary.cases.show', $case)
            ->with('success', 'Evidence added successfully.');
    }

    /**
     * Add witness statement
     */
    public function addWitness(Request $request, DisciplinaryCase $case)
    {
        $this->authorizeCaseAccess($case);

        $validator = Validator::make($request->all(), [
            'witness_name' => 'required|string|max:255',
            'witness_statement' => 'required|string|max:1000',
            'witness_contact' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $witnessStatements = $case->witness_statements ?? [];
        $witnessStatements[] = [
            'name' => $request->witness_name,
            'statement' => $request->witness_statement,
            'contact' => $request->witness_contact,
            'added_at' => now()->toDateTimeString(),
            'added_by' => Auth::user()->name,
        ];

        $case->update(['witness_statements' => $witnessStatements]);

        return redirect()->route('admin.disciplinary.cases.show', $case)
            ->with('success', 'Witness statement added successfully.');
    }

    /**
     * Case summary report
     */
    public function caseSummaryReport()
    {
        $organization = Auth::user()->organization;

        $report = [
            'total_cases' => DisciplinaryCase::byOrganization($organization->id)->count(),
            'open_cases' => DisciplinaryCase::byOrganization($organization->id)->open()->count(),
            'closed_cases' => DisciplinaryCase::byOrganization($organization->id)->closed()->count(),
            'cases_by_type' => DisciplinaryCase::byOrganization($organization->id)
                ->selectRaw('incident_type, count(*) as count')
                ->groupBy('incident_type')
                ->get(),
            'cases_by_decision' => DisciplinaryCase::byOrganization($organization->id)
                ->selectRaw('decision, count(*) as count')
                ->whereNotNull('decision')
                ->groupBy('decision')
                ->get(),
        ];

        return view('admin.governance.disciplinary.reports.summary', compact('report'));
    }

    /**
     * Suspensions report
     */
    public function suspensionsReport()
    {
        $organization = Auth::user()->organization;

        $suspensions = PlayerSuspension::where('organization_id', $organization->id)
            ->with(['player', 'disciplinaryCase'])
            ->latest()
            ->get();

        return view('admin.governance.disciplinary.reports.suspensions', compact('suspensions'));
    }

    /**
     * Incident types report
     */
    public function incidentTypesReport()
    {
        $organization = Auth::user()->organization;

        $incidentTypes = DisciplinaryCase::byOrganization($organization->id)
            ->selectRaw('incident_type, count(*) as count, offense_type')
            ->groupBy('incident_type', 'offense_type')
            ->orderBy('count', 'desc')
            ->get();

        return view('admin.governance.disciplinary.reports.incident-types', compact('incidentTypes'));
    }

    /**
     * Trends report
     */
    public function trendsReport()
    {
        $organization = Auth::user()->organization;

        $cases = DisciplinaryCase::byOrganization($organization->id)
            ->selectRaw('DATE(incident_date) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.governance.disciplinary.reports.trends', compact('cases'));
    }

    // Super Admin Methods

    /**
     * Super admin dashboard
     */
    public function superAdminIndex()
    {
        $stats = [
            'total_cases' => DisciplinaryCase::count(),
            'open_cases' => DisciplinaryCase::open()->count(),
            'closed_cases' => DisciplinaryCase::closed()->count(),
            'active_suspensions' => PlayerSuspension::active()->count(),
        ];

        return view('super-admin.governance.disciplinary.index', compact('stats'));
    }

    /**
     * Organization cases for super admin
     */
    public function organizationCases(Organization $organization)
    {
        $cases = DisciplinaryCase::byOrganization($organization->id)
            ->with(['player', 'team', 'match', 'tournament'])
            ->latest()
            ->get();

        return view('super-admin.governance.disciplinary.cases', compact('organization', 'cases'));
    }

    /**
     * Organization suspensions for super admin
     */
    public function organizationSuspensions(Organization $organization)
    {
        $suspensions = PlayerSuspension::where('organization_id', $organization->id)
            ->with(['player', 'disciplinaryCase'])
            ->latest()
            ->get();

        return view('super-admin.governance.disciplinary.suspensions', compact('organization', 'suspensions'));
    }

    /**
     * Multi-organization summary report
     */
    public function multiOrgSummaryReport()
    {
        $organizations = Organization::withCount([
            'disciplinaryCases',
            'disciplinaryCases as open_cases' => function($query) {
                $query->open();
            },
            'disciplinaryCases as closed_cases' => function($query) {
                $query->closed();
            },
            'playerSuspensions as active_suspensions' => function($query) {
                $query->active();
            },
        ])->get();

        return view('super-admin.governance.disciplinary.reports.multi-org', compact('organizations'));
    }

    /**
     * Cross-organization trends report
     */
    public function crossOrgTrendsReport()
    {
        $cases = DisciplinaryCase::selectRaw('DATE(incident_date) as date, organization_id, count(*) as count')
            ->groupBy('date', 'organization_id')
            ->orderBy('date')
            ->get();

        $organizations = Organization::all();

        return view('super-admin.governance.disciplinary.reports.cross-org', compact('cases', 'organizations'));
    }

    // API Methods

    /**
     * Get case status
     */
    public function getCaseStatus(DisciplinaryCase $case)
    {
        $this->authorizeCaseAccess($case);

        $status = [
            'id' => $case->id,
            'case_number' => $case->case_number,
            'status' => $case->status,
            'decision' => $case->decision,
            'decision_date' => $case->decision_date,
            'review_started_at' => $case->review_started_at,
            'review_started_by' => $case->review_started_by,
            'has_suspension' => $case->suspension ? true : false,
            'suspension_details' => $case->suspension,
            'has_appeal' => $case->appeal ? true : false,
            'appeal_details' => $case->appeal,
        ];

        return response()->json($status);
    }

    /**
     * Get player disciplinary history
     */
    public function getPlayerHistory(Player $player)
    {
        $this->authorizePlayerAccess($player);

        $history = DisciplinaryCase::where('player_id', $player->id)
            ->with(['suspension', 'appeal'])
            ->latest()
            ->get();

        return response()->json($history);
    }

    /**
     * Get active suspensions
     */
    public function getActiveSuspensions()
    {
        $organization = Auth::user()->organization;

        $suspensions = PlayerSuspension::where('organization_id', $organization->id)
            ->active()
            ->with(['player', 'disciplinaryCase'])
            ->get();

        return response()->json($suspensions);
    }

    // Helper Methods

    /**
     * Authorize case access
     */
    protected function authorizeCaseAccess(DisciplinaryCase $case)
    {
        $organization = Auth::user()->organization;

        if ($case->organization_id !== $organization->id) {
            abort(403, 'Unauthorized access to disciplinary case.');
        }
    }

    /**
     * Authorize player access
     */
    protected function authorizePlayerAccess(Player $player)
    {
        $organization = Auth::user()->organization;

        if ($player->organization_id !== $organization->id) {
            abort(403, 'Unauthorized access to player.');
        }
    }
}
