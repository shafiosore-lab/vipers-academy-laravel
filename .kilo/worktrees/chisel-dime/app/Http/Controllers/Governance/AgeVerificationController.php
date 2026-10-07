<?php

namespace App\Http\Controllers\Governance;

use App\Http\Controllers\Controller;
use App\Models\AgeAlertRule;
use App\Models\Player;
use App\Models\Organization;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AgeVerificationController extends Controller
{
    /**
     * AgeVerificationController constructor.
     */
    public function __construct()
    {
        // Apply authorization check to all methods
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            $permissionService = new PermissionService();

            if (!$permissionService->hasRoleOrHigher($user, 'admin')) {
                abort(403, 'Unauthorized access to age verification management');
            }

            return $next($request);
        });
    }

    /**
     * Display age verification dashboard
     */
    public function index()
    {
        $organization = Auth::user()->organization;

        $stats = [
            'total_rules' => AgeAlertRule::forOrganization($organization->id)->count(),
            'active_rules' => AgeAlertRule::forOrganization($organization->id)->active()->count(),
            'players_needing_verification' => $this->getPlayersNeedingVerification($organization->id)->count(),
            'players_with_alerts' => $this->getPlayersWithAlerts($organization->id)->count(),
        ];

        return view('admin.governance.age-verification.index', compact('stats'));
    }

    /**
     * Display age verification rules
     */
    public function rules()
    {
        $organization = Auth::user()->organization;
        $rules = AgeAlertRule::forOrganization($organization->id)->get();

        return view('admin.governance.age-verification.rules', compact('rules'));
    }

    /**
     * Show create rule form
     */
    public function createRule()
    {
        return view('admin.governance.age-verification.create-rule');
    }

    /**
     * Store new age alert rule
     */
    public function storeRule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'min_age' => 'required|integer|min:0|max:100',
            'max_age' => 'required|integer|min:0|max:100|gte:min_age',
            'alert_threshold_days' => 'required|integer|min:1|max:365',
            'is_active' => 'boolean',
            'auto_flag' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $organization = Auth::user()->organization;

        AgeAlertRule::create([
            'organization_id' => $organization->id,
            'name' => $request->name,
            'category' => $request->category,
            'min_age' => $request->min_age,
            'max_age' => $request->max_age,
            'alert_threshold_days' => $request->alert_threshold_days,
            'is_active' => $request->has('is_active'),
            'auto_flag' => $request->has('auto_flag'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.age-verification.rules')
            ->with('success', 'Age alert rule created successfully.');
    }

    /**
     * Show edit rule form
     */
    public function editRule(AgeAlertRule $rule)
    {
        $this->authorizeRuleAccess($rule);

        return view('admin.governance.age-verification.edit-rule', compact('rule'));
    }

    /**
     * Update age alert rule
     */
    public function updateRule(Request $request, AgeAlertRule $rule)
    {
        $this->authorizeRuleAccess($rule);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'min_age' => 'required|integer|min:0|max:100',
            'max_age' => 'required|integer|min:0|max:100|gte:min_age',
            'alert_threshold_days' => 'required|integer|min:1|max:365',
            'is_active' => 'boolean',
            'auto_flag' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $rule->update([
            'name' => $request->name,
            'category' => $request->category,
            'min_age' => $request->min_age,
            'max_age' => $request->max_age,
            'alert_threshold_days' => $request->alert_threshold_days,
            'is_active' => $request->has('is_active'),
            'auto_flag' => $request->has('auto_flag'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.age-verification.rules')
            ->with('success', 'Age alert rule updated successfully.');
    }

    /**
     * Delete age alert rule
     */
    public function destroyRule(AgeAlertRule $rule)
    {
        $this->authorizeRuleAccess($rule);

        $rule->delete();

        return redirect()->route('admin.age-verification.rules')
            ->with('success', 'Age alert rule deleted successfully.');
    }

    /**
     * Toggle rule status
     */
    public function toggleRule(AgeAlertRule $rule)
    {
        $this->authorizeRuleAccess($rule);

        $rule->update([
            'is_active' => !$rule->is_active,
        ]);

        return redirect()->route('admin.age-verification.rules')
            ->with('success', 'Rule status updated successfully.');
    }

    /**
     * Display players for verification
     */
    public function players()
    {
        $organization = Auth::user()->organization;
        $players = Player::where('organization_id', $organization->id)
            ->with(['ageAlertRules'])
            ->get();

        return view('admin.governance.age-verification.players', compact('players'));
    }

    /**
     * Show player verification form
     */
    public function verifyPlayer(Player $player)
    {
        $this->authorizePlayerAccess($player);

        $verificationHistory = $player->ageVerifications()->latest()->get();

        return view('admin.governance.age-verification.verify-player', compact('player', 'verificationHistory'));
    }

    /**
     * Store player verification
     */
    public function storeVerification(Request $request, Player $player)
    {
        $this->authorizePlayerAccess($player);

        $validator = Validator::make($request->all(), [
            'verification_date' => 'required|date',
            'verified_by' => 'required|string|max:255',
            'verification_method' => 'required|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'is_verified' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create verification record
        $player->ageVerifications()->create([
            'verification_date' => $request->verification_date,
            'verified_by' => $request->verified_by,
            'verification_method' => $request->verification_method,
            'notes' => $request->notes,
            'is_verified' => $request->is_verified,
            'verified_at' => now(),
            'verified_by_user_id' => Auth::id(),
        ]);

        // Update player verification status
        $player->update([
            'is_age_verified' => $request->is_verified,
            'age_verification_date' => $request->is_verified ? $request->verification_date : null,
        ]);

        return redirect()->route('admin.age-verification.players')
            ->with('success', 'Player verification recorded successfully.');
    }

    /**
     * Show player verification history
     */
    public function verificationHistory(Player $player)
    {
        $this->authorizePlayerAccess($player);

        $verifications = $player->ageVerifications()->latest()->get();

        return view('admin.governance.age-verification.history', compact('player', 'verifications'));
    }

    /**
     * Flag player for age verification
     */
    public function flagPlayer(Player $player)
    {
        $this->authorizePlayerAccess($player);

        $player->update([
            'needs_age_verification' => true,
            'age_verification_flagged_at' => now(),
            'age_verification_flagged_by' => Auth::id(),
        ]);

        return redirect()->route('admin.age-verification.players')
            ->with('success', 'Player flagged for age verification.');
    }

    /**
     * Unflag player from age verification
     */
    public function unflagPlayer(Player $player)
    {
        $this->authorizePlayerAccess($player);

        $player->update([
            'needs_age_verification' => false,
            'age_verification_flagged_at' => null,
            'age_verification_flagged_by' => null,
        ]);

        return redirect()->route('admin.age-verification.players')
            ->with('success', 'Player unflagged from age verification.');
    }

    /**
     * Age compliance report
     */
    public function ageComplianceReport()
    {
        $organization = Auth::user()->organization;

        $report = [
            'total_players' => Player::where('organization_id', $organization->id)->count(),
            'verified_players' => Player::where('organization_id', $organization->id)
                ->where('is_age_verified', true)
                ->count(),
            'unverified_players' => Player::where('organization_id', $organization->id)
                ->where('is_age_verified', false)
                ->count(),
            'flagged_players' => Player::where('organization_id', $organization->id)
                ->where('needs_age_verification', true)
                ->count(),
            'players_with_alerts' => $this->getPlayersWithAlerts($organization->id)->count(),
        ];

        return view('admin.governance.age-verification.reports.compliance', compact('report'));
    }

    /**
     * Verification status report
     */
    public function verificationStatusReport()
    {
        $organization = Auth::user()->organization;

        $players = Player::where('organization_id', $organization->id)
            ->with(['ageVerifications' => function($query) {
                $query->latest()->limit(1);
            }])
            ->get();

        return view('admin.governance.age-verification.reports.status', compact('players'));
    }

    /**
     * Age alerts report
     */
    public function ageAlertsReport()
    {
        $organization = Auth::user()->organization;
        $players = $this->getPlayersWithAlerts($organization->id);

        return view('admin.governance.age-verification.reports.alerts', compact('players'));
    }

    // Super Admin Methods

    /**
     * Super admin dashboard
     */
    public function superAdminIndex()
    {
        $stats = [
            'total_organizations' => Organization::count(),
            'organizations_with_rules' => Organization::has('ageAlertRules')->count(),
            'total_rules' => AgeAlertRule::count(),
            'active_rules' => AgeAlertRule::active()->count(),
        ];

        return view('super-admin.governance.age-verification.index', compact('stats'));
    }

    /**
     * Organization rules for super admin
     */
    public function organizationRules(Organization $organization)
    {
        $rules = AgeAlertRule::forOrganization($organization->id)->get();

        return view('super-admin.governance.age-verification.rules', compact('organization', 'rules'));
    }

    /**
     * Organization players for super admin
     */
    public function organizationPlayers(Organization $organization)
    {
        $players = Player::where('organization_id', $organization->id)->get();

        return view('super-admin.governance.age-verification.players', compact('organization', 'players'));
    }

    /**
     * Multi-organization compliance report
     */
    public function multiOrgComplianceReport()
    {
        $organizations = Organization::withCount([
            'players',
            'players as verified_players' => function($query) {
                $query->where('is_age_verified', true);
            },
            'players as unverified_players' => function($query) {
                $query->where('is_age_verified', false);
            },
            'players as flagged_players' => function($query) {
                $query->where('needs_age_verification', true);
            },
        ])->get();

        return view('super-admin.governance.age-verification.reports.multi-org', compact('organizations'));
    }

    /**
     * Organization summary report
     */
    public function organizationSummaryReport()
    {
        $organizations = Organization::with([
            'ageAlertRules',
            'players' => function($query) {
                $query->with(['ageVerifications' => function($q) {
                    $q->latest()->limit(1);
                }]);
            }
        ])->get();

        return view('super-admin.governance.age-verification.reports.summary', compact('organizations'));
    }

    // API Methods

    /**
     * Get player age verification status
     */
    public function getPlayerStatus(Player $player)
    {
        $this->authorizePlayerAccess($player);

        $status = [
            'is_verified' => $player->is_age_verified,
            'verification_date' => $player->age_verification_date,
            'needs_verification' => $player->needs_age_verification,
            'verification_flagged_at' => $player->age_verification_flagged_at,
            'verification_flagged_by' => $player->age_verification_flagged_by,
            'age_alerts' => $player->ageAlertRules()->get(),
            'verification_history' => $player->ageVerifications()->latest()->get(),
        ];

        return response()->json($status);
    }

    /**
     * Get active age verification rules for organization
     */
    public function getActiveRules(Organization $organization)
    {
        $rules = AgeAlertRule::forOrganization($organization->id)->active()->get();

        return response()->json($rules);
    }

    /**
     * API endpoint for player verification
     */
    public function apiVerifyPlayer(Request $request, Player $player)
    {
        $this->authorizePlayerAccess($player);

        $validator = Validator::make($request->all(), [
            'verification_date' => 'required|date',
            'verified_by' => 'required|string|max:255',
            'verification_method' => 'required|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'is_verified' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Create verification record
        $player->ageVerifications()->create([
            'verification_date' => $request->verification_date,
            'verified_by' => $request->verified_by,
            'verification_method' => $request->verification_method,
            'notes' => $request->notes,
            'is_verified' => $request->is_verified,
            'verified_at' => now(),
            'verified_by_user_id' => Auth::id(),
        ]);

        // Update player verification status
        $player->update([
            'is_age_verified' => $request->is_verified,
            'age_verification_date' => $request->is_verified ? $request->verification_date : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Player verification recorded successfully.',
            'player' => $player->refresh()
        ]);
    }

    // Helper Methods

    /**
     * Get players needing verification
     */
    protected function getPlayersNeedingVerification(int $organizationId)
    {
        return Player::where('organization_id', $organizationId)
            ->where('needs_age_verification', true)
            ->get();
    }

    /**
     * Get players with age alerts
     */
    protected function getPlayersWithAlerts(int $organizationId)
    {
        $rules = AgeAlertRule::forOrganization($organizationId)->active()->get();

        $players = collect();

        foreach ($rules as $rule) {
            $rulePlayers = $rule->getPlayersNearCutoff();
            $players = $players->merge($rulePlayers);
        }

        return $players->unique('id');
    }

    /**
     * Authorize rule access
     */
    protected function authorizeRuleAccess(AgeAlertRule $rule)
    {
        $organization = Auth::user()->organization;

        if ($rule->organization_id !== $organization->id) {
            abort(403, 'Unauthorized access to age alert rule.');
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
