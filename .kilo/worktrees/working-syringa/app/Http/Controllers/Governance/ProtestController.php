<?php

namespace App\Http\Controllers\Governance;

use App\Http\Controllers\Controller;
use App\Models\Protest;
use App\Models\TournamentMatch;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\Organization;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProtestController extends Controller
{
    /**
     * ProtestController constructor.
     */
    public function __construct()
    {
        // Apply authorization check to all methods
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            $permissionService = new PermissionService();

            if (!$permissionService->hasRoleOrHigher($user, 'admin')) {
                abort(403, 'Unauthorized access to protest management');
            }

            return $next($request);
        });
    }

    /**
     * Display protests dashboard
     */
    public function index()
    {
        $organization = Auth::user()->organization;

        $stats = [
            'total_protests' => Protest::whereHas('match', function($query) use ($organization) {
                $query->whereHas('tournament', function($q) use ($organization) {
                    $q->where('organization_id', $organization->id);
                });
            })->count(),
            'pending_protests' => Protest::whereHas('match', function($query) use ($organization) {
                $query->whereHas('tournament', function($q) use ($organization) {
                    $q->where('organization_id', $organization->id);
                });
            })->pending()->count(),
            'resolved_protests' => Protest::whereHas('match', function($query) use ($organization) {
                $query->whereHas('tournament', function($q) use ($organization) {
                    $q->where('organization_id', $organization->id);
                });
            })->resolved()->count(),
            'upheld_protests' => Protest::whereHas('match', function($query) use ($organization) {
                $query->whereHas('tournament', function($q) use ($organization) {
                    $q->where('organization_id', $organization->id);
                });
            })->where('status', Protest::STATUS_UPHELD)->count(),
        ];

        return view('admin.governance.protests.index', compact('stats'));
    }

    /**
     * Display pending protests
     */
    public function pending()
    {
        $organization = Auth::user()->organization;
        $protests = Protest::whereHas('match', function($query) use ($organization) {
            $query->whereHas('tournament', function($q) use ($organization) {
                $q->where('organization_id', $organization->id);
            });
        })->pending()
        ->with(['match', 'team', 'tournament'])
        ->latest()
        ->paginate(20);

        return view('admin.governance.protests.pending', compact('protests'));
    }

    /**
     * Display resolved protests
     */
    public function resolved()
    {
        $organization = Auth::user()->organization;
        $protests = Protest::whereHas('match', function($query) use ($organization) {
            $query->whereHas('tournament', function($q) use ($organization) {
                $q->where('organization_id', $organization->id);
            });
        })->resolved()
        ->with(['match', 'team', 'tournament'])
        ->latest()
        ->paginate(20);

        return view('admin.governance.protests.resolved', compact('protests'));
    }

    /**
     * Show create protest form
     */
    public function create()
    {
        $organization = Auth::user()->organization;
        $matches = TournamentMatch::whereHas('tournament', function($query) use ($organization) {
            $query->where('organization_id', $organization->id);
        })->where('status', 'completed')
        ->orWhere('status', 'in_progress')
        ->get();

        $teams = Team::where('organization_id', $organization->id)->get();

        return view('admin.governance.protests.create', compact('matches', 'teams'));
    }

    /**
     * Store new protest
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'match_id' => 'required|exists:tournament_matches,id',
            'team_id' => 'required|exists:teams,id',
            'protest_type' => 'required|string',
            'description' => 'required|string|max:2000',
            'grounds' => 'required|string|max:1000',
            'evidence' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $match = TournamentMatch::find($request->match_id);
        $team = Team::find($request->team_id);

        // Check if team participated in the match
        if ($match->home_team_id !== $team->id && $match->away_team_id !== $team->id) {
            return redirect()->back()
                ->with('error', 'Team did not participate in this match.')
                ->withInput();
        }

        $protest = Protest::submit(
            $request->match_id,
            $request->team_id,
            $team->organization_id,
            $match->tournament_id,
            $request->protest_type,
            $request->description,
            $request->grounds,
            $request->evidence ?? [],
            Auth::id()
        );

        return redirect()->route('admin.protests.show', $protest)
            ->with('success', 'Protest submitted successfully.');
    }

    /**
     * Show protest details
     */
    public function show(Protest $protest)
    {
        $this->authorizeProtestAccess($protest);

        $protest->load(['match', 'team', 'tournament']);

        return view('admin.governance.protests.show', compact('protest'));
    }

    /**
     * Show edit protest form
     */
    public function edit(Protest $protest)
    {
        $this->authorizeProtestAccess($protest);

        if (!$protest->isPending()) {
            return redirect()->route('admin.protests.show', $protest)
                ->with('error', 'Cannot edit a resolved protest.');
        }

        $matches = TournamentMatch::whereHas('tournament', function($query) use ($protest) {
            $query->where('organization_id', $protest->team->organization_id);
        })->get();

        $teams = Team::where('organization_id', $protest->team->organization_id)->get();

        return view('admin.governance.protests.edit', compact('protest', 'matches', 'teams'));
    }

    /**
     * Update protest
     */
    public function update(Request $request, Protest $protest)
    {
        $this->authorizeProtestAccess($protest);

        if (!$protest->isPending()) {
            return redirect()->route('admin.protests.show', $protest)
                ->with('error', 'Cannot update a resolved protest.');
        }

        $validator = Validator::make($request->all(), [
            'match_id' => 'required|exists:tournament_matches,id',
            'team_id' => 'required|exists:teams,id',
            'protest_type' => 'required|string',
            'description' => 'required|string|max:2000',
            'grounds' => 'required|string|max:1000',
            'evidence' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $protest->update([
            'match_id' => $request->match_id,
            'team_id' => $request->team_id,
            'protest_type' => $request->protest_type,
            'description' => $request->description,
            'grounds' => $request->grounds,
            'evidence' => $request->evidence,
        ]);

        return redirect()->route('admin.protests.show', $protest)
            ->with('success', 'Protest updated successfully.');
    }

    /**
     * Delete protest
     */
    public function destroy(Protest $protest)
    {
        $this->authorizeProtestAccess($protest);

        if (!$protest->isPending()) {
            return redirect()->route('admin.protests.resolved')
                ->with('error', 'Cannot delete a resolved protest.');
        }

        $protest->delete();

        return redirect()->route('admin.protests.pending')
            ->with('success', 'Protest deleted successfully.');
    }

    /**
     * Start protest review
     */
    public function startReview(Protest $protest)
    {
        $this->authorizeProtestAccess($protest);

        if (!$protest->isPending()) {
            return redirect()->route('admin.protests.show', $protest)
                ->with('error', 'Cannot start review on a resolved protest.');
        }

        $protest->startReview();

        return redirect()->route('admin.protests.show', $protest)
            ->with('success', 'Protest review started.');
    }

    /**
     * Uphold protest
     */
    public function uphold(Request $request, Protest $protest)
    {
        $this->authorizeProtestAccess($protest);

        if (!$protest->isUnderReview()) {
            return redirect()->route('admin.protests.show', $protest)
                ->with('error', 'Cannot uphold a protest that is not under review.');
        }

        $validator = Validator::make($request->all(), [
            'resolution' => 'required|string',
            'reason' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $protest->uphold($request->resolution, $request->reason, Auth::user());

        return redirect()->route('admin.protests.show', $protest)
            ->with('success', 'Protest upheld successfully.');
    }

    /**
     * Reject protest
     */
    public function reject(Request $request, Protest $protest)
    {
        $this->authorizeProtestAccess($protest);

        if (!$protest->isUnderReview()) {
            return redirect()->route('admin.protests.show', $protest)
                ->with('error', 'Cannot reject a protest that is not under review.');
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $protest->reject($request->reason, Auth::user());

        return redirect()->route('admin.protests.show', $protest)
            ->with('success', 'Protest rejected successfully.');
    }

    /**
     * Withdraw protest
     */
    public function withdraw(Protest $protest)
    {
        $this->authorizeProtestAccess($protest);

        if (!$protest->canWithdraw()) {
            return redirect()->route('admin.protests.show', $protest)
                ->with('error', 'Cannot withdraw this protest.');
        }

        $protest->withdraw();

        return redirect()->route('admin.protests.pending')
            ->with('success', 'Protest withdrawn successfully.');
    }

    /**
     * Protest summary report
     */
    public function protestSummaryReport()
    {
        $organization = Auth::user()->organization;

        $report = [
            'total_protests' => Protest::whereHas('match', function($query) use ($organization) {
                $query->whereHas('tournament', function($q) use ($organization) {
                    $q->where('organization_id', $organization->id);
                });
            })->count(),
            'pending_protests' => Protest::whereHas('match', function($query) use ($organization) {
                $query->whereHas('tournament', function($q) use ($organization) {
                    $q->where('organization_id', $organization->id);
                });
            })->pending()->count(),
            'upheld_protests' => Protest::whereHas('match', function($query) use ($organization) {
                $query->whereHas('tournament', function($q) use ($organization) {
                    $q->where('organization_id', $organization->id);
                });
            })->where('status', Protest::STATUS_UPHELD)->count(),
            'rejected_protests' => Protest::whereHas('match', function($query) use ($organization) {
                $query->whereHas('tournament', function($q) use ($organization) {
                    $q->where('organization_id', $organization->id);
                });
            })->where('status', Protest::STATUS_REJECTED)->count(),
            'protests_by_type' => Protest::whereHas('match', function($query) use ($organization) {
                $query->whereHas('tournament', function($q) use ($organization) {
                    $q->where('organization_id', $organization->id);
                });
            })->selectRaw('protest_type, count(*) as count')
            ->groupBy('protest_type')
            ->get(),
        ];

        return view('admin.governance.protests.reports.summary', compact('report'));
    }

    /**
     * Protest types report
     */
    public function protestTypesReport()
    {
        $organization = Auth::user()->organization;

        $protests = Protest::whereHas('match', function($query) use ($organization) {
            $query->whereHas('tournament', function($q) use ($organization) {
                $q->where('organization_id', $organization->id);
            });
        })->with(['match', 'team'])
        ->latest()
        ->get();

        return view('admin.governance.protests.reports.types', compact('protests'));
    }

    /**
     * Protest outcomes report
     */
    public function protestOutcomesReport()
    {
        $organization = Auth::user()->organization;

        $protests = Protest::whereHas('match', function($query) use ($organization) {
            $query->whereHas('tournament', function($q) use ($organization) {
                $q->where('organization_id', $organization->id);
            });
        })->with(['match', 'team'])
        ->whereNotNull('outcome')
        ->latest()
        ->get();

        return view('admin.governance.protests.reports.outcomes', compact('protests'));
    }

    // Super Admin Methods

    /**
     * Super admin dashboard
     */
    public function superAdminIndex()
    {
        $stats = [
            'total_protests' => Protest::count(),
            'pending_protests' => Protest::pending()->count(),
            'resolved_protests' => Protest::resolved()->count(),
            'upheld_protests' => Protest::where('status', Protest::STATUS_UPHELD)->count(),
        ];

        return view('super-admin.governance.protests.index', compact('stats'));
    }

    /**
     * Organization protests for super admin
     */
    public function organizationProtests(Organization $organization)
    {
        $protests = Protest::whereHas('match', function($query) use ($organization) {
            $query->whereHas('tournament', function($q) use ($organization) {
                $q->where('organization_id', $organization->id);
            });
        })->with(['match', 'team', 'tournament'])
        ->latest()
        ->get();

        return view('super-admin.governance.protests.index', compact('organization', 'protests'));
    }

    /**
     * Multi-organization summary report
     */
    public function multiOrgSummaryReport()
    {
        $organizations = Organization::withCount([
            'tournaments as total_tournaments',
            'tournaments as tournaments_with_protests' => function($query) {
                $query->whereHas('matches.protests');
            },
            'tournaments as protests_count' => function($query) {
                $query->withCount('matches as protest_count');
            },
        ])->get();

        return view('super-admin.governance.protests.reports.multi-org', compact('organizations'));
    }

    // API Methods

    /**
     * Get match protests
     */
    public function getMatchProtests(TournamentMatch $match)
    {
        $protests = Protest::where('match_id', $match->id)
            ->with(['team', 'player', 'reviewer'])
            ->latest()
            ->get();

        return response()->json($protests);
    }

    /**
     * Get team protests
     */
    public function getTeamProtests(Team $team)
    {
        $this->authorizeTeamAccess($team);

        $protests = Protest::where('team_id', $team->id)
            ->with(['match', 'player', 'reviewer'])
            ->latest()
            ->get();

        return response()->json($protests);
    }

    // Helper Methods

    /**
     * Authorize protest access
     */
    protected function authorizeProtestAccess(Protest $protest)
    {
        $organization = Auth::user()->organization;

        if ($protest->organization_id !== $organization->id) {
            abort(403, 'Unauthorized access to protest.');
        }
    }

    /**
     * Authorize team access
     */
    protected function authorizeTeamAccess(Team $team)
    {
        $organization = Auth::user()->organization;

        if ($team->organization_id !== $organization->id) {
            abort(403, 'Unauthorized access to team.');
        }
    }
}
