<?php

namespace App\Http\Controllers\Governance;

use App\Http\Controllers\Controller;
use App\Models\Appeal;
use App\Models\DisciplinaryCase;
use App\Models\Player;
use App\Models\Organization;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AppealController extends Controller
{
    /**
     * AppealController constructor.
     */
    public function __construct()
    {
        // Apply authorization check to all methods
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            $permissionService = new PermissionService();

            if (!$permissionService->hasRoleOrHigher($user, 'admin')) {
                abort(403, 'Unauthorized access to appeal management');
            }

            return $next($request);
        });
    }

    /**
     * Display appeals dashboard
     */
    public function index()
    {
        $organization = Auth::user()->organization;

        $stats = [
            'total_appeals' => Appeal::whereHas('disciplinaryCase', function($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })->count(),
            'pending_appeals' => Appeal::whereHas('disciplinaryCase', function($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })->pending()->count(),
            'resolved_appeals' => Appeal::whereHas('disciplinaryCase', function($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })->resolved()->count(),
            'accepted_appeals' => Appeal::whereHas('disciplinaryCase', function($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })->where('status', Appeal::STATUS_ACCEPTED)->count(),
        ];

        return view('admin.governance.appeals.index', compact('stats'));
    }

    /**
     * Display pending appeals
     */
    public function pending()
    {
        $organization = Auth::user()->organization;
        $appeals = Appeal::whereHas('disciplinaryCase', function($query) use ($organization) {
            $query->where('organization_id', $organization->id);
        })->pending()
        ->with(['disciplinaryCase.player', 'disciplinaryCase.team'])
        ->latest()
        ->paginate(20);

        return view('admin.governance.appeals.pending', compact('appeals'));
    }

    /**
     * Display resolved appeals
     */
    public function resolved()
    {
        $organization = Auth::user()->organization;
        $appeals = Appeal::whereHas('disciplinaryCase', function($query) use ($organization) {
            $query->where('organization_id', $organization->id);
        })->resolved()
        ->with(['disciplinaryCase.player', 'disciplinaryCase.team'])
        ->latest()
        ->paginate(20);

        return view('admin.governance.appeals.resolved', compact('appeals'));
    }

    /**
     * Show create appeal form
     */
    public function create()
    {
        $organization = Auth::user()->organization;
        $cases = DisciplinaryCase::byOrganization($organization->id)
            ->closed()
            ->whereDoesntHave('appeal')
            ->get();

        return view('admin.governance.appeals.create', compact('cases'));
    }

    /**
     * Store new appeal
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'disciplinary_case_id' => 'required|exists:disciplinary_cases,id',
            'grounds' => 'required|string|max:1000',
            'evidence' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $case = DisciplinaryCase::find($request->disciplinary_case_id);

        if (!$case->canAppeal()) {
            return redirect()->back()
                ->with('error', 'This case cannot be appealed.');
        }

        $appeal = Appeal::submit(
            $case->id,
            $case->player_id,
            $case->organization_id,
            $request->grounds,
            $request->evidence ?? [],
            Auth::id()
        );

        return redirect()->route('admin.appeals.show', $appeal)
            ->with('success', 'Appeal submitted successfully.');
    }

    /**
     * Show appeal details
     */
    public function show(Appeal $appeal)
    {
        $this->authorizeAppealAccess($appeal);

        $appeal->load(['disciplinaryCase.player', 'disciplinaryCase.team', 'disciplinaryCase.tournament']);

        return view('admin.governance.appeals.show', compact('appeal'));
    }

    /**
     * Show edit appeal form
     */
    public function edit(Appeal $appeal)
    {
        $this->authorizeAppealAccess($appeal);

        if (!$appeal->isPending()) {
            return redirect()->route('admin.appeals.show', $appeal)
                ->with('error', 'Cannot edit a resolved appeal.');
        }

        return view('admin.governance.appeals.edit', compact('appeal'));
    }

    /**
     * Update appeal
     */
    public function update(Request $request, Appeal $appeal)
    {
        $this->authorizeAppealAccess($appeal);

        if (!$appeal->isPending()) {
            return redirect()->route('admin.appeals.show', $appeal)
                ->with('error', 'Cannot update a resolved appeal.');
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

        $appeal->update([
            'grounds' => $request->grounds,
            'evidence' => $request->evidence,
        ]);

        return redirect()->route('admin.appeals.show', $appeal)
            ->with('success', 'Appeal updated successfully.');
    }

    /**
     * Delete appeal
     */
    public function destroy(Appeal $appeal)
    {
        $this->authorizeAppealAccess($appeal);

        if (!$appeal->isPending()) {
            return redirect()->route('admin.appeals.resolved')
                ->with('error', 'Cannot delete a resolved appeal.');
        }

        $appeal->delete();

        return redirect()->route('admin.appeals.pending')
            ->with('success', 'Appeal deleted successfully.');
    }

    /**
     * Start appeal review
     */
    public function startReview(Appeal $appeal)
    {
        $this->authorizeAppealAccess($appeal);

        if (!$appeal->isPending()) {
            return redirect()->route('admin.appeals.show', $appeal)
                ->with('error', 'Cannot start review on a resolved appeal.');
        }

        $appeal->startReview(Auth::user());

        return redirect()->route('admin.appeals.show', $appeal)
            ->with('success', 'Appeal review started.');
    }

    /**
     * Accept appeal (uphold)
     */
    public function accept(Appeal $appeal)
    {
        $this->authorizeAppealAccess($appeal);

        if (!$appeal->isUnderReview()) {
            return redirect()->route('admin.appeals.show', $appeal)
                ->with('error', 'Cannot accept an appeal that is not under review.');
        }

        $appeal->accept('Appeal upheld by administrator', Auth::user());

        return redirect()->route('admin.appeals.show', $appeal)
            ->with('success', 'Appeal accepted successfully.');
    }

    /**
     * Dismiss appeal
     */
    public function dismiss(Request $request, Appeal $appeal)
    {
        $this->authorizeAppealAccess($appeal);

        if (!$appeal->isUnderReview()) {
            return redirect()->route('admin.appeals.show', $appeal)
                ->with('error', 'Cannot dismiss an appeal that is not under review.');
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $appeal->dismiss($request->reason, Auth::user());

        return redirect()->route('admin.appeals.show', $appeal)
            ->with('success', 'Appeal dismissed successfully.');
    }

    /**
     * Modify decision
     */
    public function modify(Request $request, Appeal $appeal)
    {
        $this->authorizeAppealAccess($appeal);

        if (!$appeal->isUnderReview()) {
            return redirect()->route('admin.appeals.show', $appeal)
                ->with('error', 'Cannot modify an appeal that is not under review.');
        }

        $validator = Validator::make($request->all(), [
            'new_decision' => 'required|string',
            'new_matches' => 'nullable|integer|min:0',
            'new_days' => 'nullable|integer|min:0',
            'new_fine' => 'nullable|numeric|min:0',
            'reason' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $appeal->modify(
            $request->new_decision,
            $request->new_matches,
            $request->new_days,
            $request->new_fine,
            $request->reason,
            Auth::user()
        );

        return redirect()->route('admin.appeals.show', $appeal)
            ->with('success', 'Decision modified successfully.');
    }

    /**
     * Withdraw appeal
     */
    public function withdraw(Appeal $appeal)
    {
        $this->authorizeAppealAccess($appeal);

        if (!$appeal->canWithdraw()) {
            return redirect()->route('admin.appeals.show', $appeal)
                ->with('error', 'Cannot withdraw this appeal.');
        }

        $appeal->withdraw();

        return redirect()->route('admin.appeals.pending')
            ->with('success', 'Appeal withdrawn successfully.');
    }

    /**
     * Appeal summary report
     */
    public function appealSummaryReport()
    {
        $organization = Auth::user()->organization;

        $report = [
            'total_appeals' => Appeal::whereHas('disciplinaryCase', function($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })->count(),
            'pending_appeals' => Appeal::whereHas('disciplinaryCase', function($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })->pending()->count(),
            'accepted_appeals' => Appeal::whereHas('disciplinaryCase', function($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })->where('status', Appeal::STATUS_ACCEPTED)->count(),
            'rejected_appeals' => Appeal::whereHas('disciplinaryCase', function($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })->where('status', Appeal::STATUS_REJECTED)->count(),
            'appeals_by_outcome' => Appeal::whereHas('disciplinaryCase', function($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })->selectRaw('outcome, count(*) as count')
            ->whereNotNull('outcome')
            ->groupBy('outcome')
            ->get(),
        ];

        return view('admin.governance.appeals.reports.summary', compact('report'));
    }

    /**
     * Appeal outcomes report
     */
    public function appealOutcomesReport()
    {
        $organization = Auth::user()->organization;

        $appeals = Appeal::whereHas('disciplinaryCase', function($query) use ($organization) {
            $query->where('organization_id', $organization->id);
        })->with(['disciplinaryCase'])
        ->whereNotNull('outcome')
        ->latest()
        ->get();

        return view('admin.governance.appeals.reports.outcomes', compact('appeals'));
    }

    /**
     * Appeal trends report
     */
    public function appealTrendsReport()
    {
        $organization = Auth::user()->organization;

        $appeals = Appeal::whereHas('disciplinaryCase', function($query) use ($organization) {
            $query->where('organization_id', $organization->id);
        })->selectRaw('DATE(created_at) as date, count(*) as count')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return view('admin.governance.appeals.reports.trends', compact('appeals'));
    }

    // Super Admin Methods

    /**
     * Super admin dashboard
     */
    public function superAdminIndex()
    {
        $stats = [
            'total_appeals' => Appeal::count(),
            'pending_appeals' => Appeal::pending()->count(),
            'resolved_appeals' => Appeal::resolved()->count(),
            'accepted_appeals' => Appeal::where('status', Appeal::STATUS_ACCEPTED)->count(),
        ];

        return view('super-admin.governance.appeals.index', compact('stats'));
    }

    /**
     * Organization appeals for super admin
     */
    public function organizationAppeals(Organization $organization)
    {
        $appeals = Appeal::whereHas('disciplinaryCase', function($query) use ($organization) {
            $query->where('organization_id', $organization->id);
        })->with(['disciplinaryCase.player', 'disciplinaryCase.team'])
        ->latest()
        ->get();

        return view('super-admin.governance.appeals.index', compact('organization', 'appeals'));
    }

    /**
     * Multi-organization summary report
     */
    public function multiOrgSummaryReport()
    {
        $organizations = Organization::withCount([
            'disciplinaryCases as total_cases',
            'disciplinaryCases as appealed_cases' => function($query) {
                $query->whereHas('appeal');
            },
            'disciplinaryCases as appealed_and_accepted' => function($query) {
                $query->whereHas('appeal', function($q) {
                    $q->where('status', Appeal::STATUS_ACCEPTED);
                });
            },
            'disciplinaryCases as appealed_and_rejected' => function($query) {
                $query->whereHas('appeal', function($q) {
                    $q->where('status', Appeal::STATUS_REJECTED);
                });
            },
        ])->get();

        return view('super-admin.governance.appeals.reports.multi-org', compact('organizations'));
    }

    // API Methods

    /**
     * Get case appeals
     */
    public function getCaseAppeals(DisciplinaryCase $case)
    {
        $appeals = \App\Models\Appeal::where('disciplinary_case_id', $case->id)
            ->with(['player', 'reviewer'])
            ->latest()
            ->get();

        return response()->json($appeals);
    }

    /**
     * Get player appeals
     */
    public function getPlayerAppeals(Player $player)
    {
        $this->authorizePlayerAccess($player);

        $appeals = \App\Models\Appeal::where('player_id', $player->id)
            ->with(['disciplinaryCase', 'reviewer'])
            ->latest()
            ->get();

        return response()->json($appeals);
    }

    // Helper Methods

    /**
     * Authorize appeal access
     */
    protected function authorizeAppealAccess(\App\Models\Appeal $appeal)
    {
        $organization = Auth::user()->organization;

        if ($appeal->organization_id !== $organization->id) {
            abort(403, 'Unauthorized access to appeal.');
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
