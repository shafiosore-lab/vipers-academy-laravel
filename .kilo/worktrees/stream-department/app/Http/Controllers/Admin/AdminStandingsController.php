<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeagueStanding;
use App\Models\TopScorer;
use App\Models\CleanSheet;
use App\Models\GoalkeeperRanking;
use App\Http\Requests\StandingsFormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class AdminStandingsController extends Controller
{
    // Model mapping for easy access
    protected $models = [
        'league' => LeagueStanding::class,
        'scorers' => TopScorer::class,
        'cleansheets' => CleanSheet::class,
        'goalkeepers' => GoalkeeperRanking::class,
    ];

    // Storage paths for file uploads
    protected $storagePaths = [
        'league' => [
            'team_logo' => 'team-logos',
        ],
        'scorers' => [
            'player_image' => 'player-images',
            'team_logo' => 'team-logos',
        ],
        'cleansheets' => [
            'goalkeeper_image' => 'player-images',
            'team_logo' => 'team-logos',
        ],
        'goalkeepers' => [
            'goalkeeper_image' => 'player-images',
            'team_logo' => 'team-logos',
        ],
    ];

    public function index(Request $request)
    {
        $type = $request->get('type', 'league');
        $model = $this->getModel($type);

        $data = $model::orderBy('season', 'desc')
            ->orderBy($type === 'scorers' ? 'ranking_position' : 'position')
            ->paginate(20);

        $stats = [
            'league_teams' => LeagueStanding::count(),
            'top_scorers' => TopScorer::count(),
            'clean_sheets' => CleanSheet::count(),
            'goalkeepers' => GoalkeeperRanking::count(),
            'seasons' => LeagueStanding::distinct('season')->count(),
            'leagues' => LeagueStanding::distinct('league_name')->count(),
        ];

        return view('admin.standings.index', compact('data', 'type', 'stats'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'league');
        return view('admin.standings.create', compact('type'));
    }

    public function store(StandingsFormRequest $request)
    {
        $type = $request->get('type');
        $model = $this->getModel($type);

        $validated = $request->validated();
        $validated = $this->handleFileUploads($request, $validated, $type);
        $validated = $request->prepareData($validated);

        $model::create($validated);

        $this->clearCaches($type);

        return redirect()->route('admin.standings.index', ['type' => $type])
            ->with('success', ucfirst($type) . ' data added successfully!');
    }

    public function show(string $id, Request $request)
    {
        $type = $request->get('type', 'league');
        $model = $this->getModel($type);
        $item = $model::findOrFail($id);

        return view('admin.standings.show', compact('item', 'type'));
    }

    public function edit(string $id, Request $request)
    {
        $type = $request->get('type', 'league');
        $model = $this->getModel($type);
        $item = $model::findOrFail($id);

        return view('admin.standings.edit', compact('item', 'type'));
    }

    public function update(StandingsFormRequest $request, string $id)
    {
        $type = $request->get('type');
        $model = $this->getModel($type);
        $item = $model::findOrFail($id);

        $validated = $request->validated();
        $validated = $this->handleFileUploads($request, $validated, $type, $item);
        $validated = $request->prepareData($validated);

        $item->update($validated);

        $this->clearCaches($type);

        return redirect()->route('admin.standings.index', ['type' => $type])
            ->with('success', ucfirst($type) . ' data updated successfully!');
    }

    public function destroy(string $id, Request $request)
    {
        $type = $request->get('type');
        $model = $this->getModel($type);
        $item = $model::findOrFail($id);

        $this->deleteFiles($item, $type);
        $item->delete();

        $this->clearCaches($type);

        return redirect()->route('admin.standings.index', ['type' => $type])
            ->with('success', ucfirst($type) . ' data deleted successfully!');
    }

    public function bulkImport(Request $request)
    {
        $type = $request->get('type');
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        return redirect()->route('admin.standings.index', ['type' => $type])
            ->with('success', 'Bulk import feature coming soon!');
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'league');
        $season = $request->get('season');
        $league = $request->get('league');

        return redirect()->route('admin.standings.index', ['type' => $type])
            ->with('success', 'Export feature coming soon!');
    }

    protected function getModel($type)
    {
        if (!isset($this->models[$type])) {
            return LeagueStanding::class;
        }

        return $this->models[$type];
    }

    protected function handleFileUploads(Request $request, array $validated, $type, $item = null)
    {
        if (!isset($this->storagePaths[$type])) {
            return $validated;
        }

        foreach ($this->storagePaths[$type] as $field => $path) {
            if ($request->hasFile($field)) {
                if ($item && $item->$field) {
                    Storage::disk('public')->delete($item->$field);
                }

                $validated[$field] = $request->file($field)->store($path, 'public');
            }
        }

        return $validated;
    }

    protected function deleteFiles($item, $type)
    {
        if (!isset($this->storagePaths[$type])) {
            return;
        }

        foreach ($this->storagePaths[$type] as $field => $path) {
            if ($item->$field) {
                Storage::disk('public')->delete($item->$field);
            }
        }
    }

    protected function clearCaches($type)
    {
        Cache::tags(['standings', 'standings_' . $type])->flush();
        Cache::forget('standings_stats');
    }
}
