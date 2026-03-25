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

    public function showExportPage(Request $request)
    {
        $type = $request->get('type', 'league');

        // Get available seasons and leagues for filters
        $seasons = LeagueStanding::distinct('season')->pluck('season')->sort()->reverse();
        $leagues = LeagueStanding::distinct('league_name')->pluck('league_name')->sort();

        return view('admin.standings.export', compact('type', 'seasons', 'leagues'));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'league');
        $season = $request->get('season');
        $league = $request->get('league');

        $model = $this->getModel($type);

        // Build query with filters
        $query = $model::query();

        if ($season) {
            $query->where('season', $season);
        }

        if ($league && $type === 'league') {
            $query->where('league_name', $league);
        }

        $data = $query->orderBy('season', 'desc')
            ->orderBy($type === 'scorers' ? 'ranking_position' : 'position')
            ->get();

        if ($data->isEmpty()) {
            return redirect()->route('admin.standings.index', ['type' => $type])
                ->with('warning', 'No data available for export.');
        }

        $filename = $type . '_standings_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($data, $type) {
            $file = fopen('php://output', 'w');

            // Write headers based on type
            switch ($type) {
                case 'league':
                    fputcsv($file, ['Position', 'Team', 'Played', 'Won', 'Drawn', 'Lost', 'GF', 'GA', 'GD', 'Points', 'Season', 'League']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->position,
                            $item->team_name,
                            $item->played,
                            $item->won,
                            $item->drawn,
                            $item->lost,
                            $item->goals_for,
                            $item->goals_against,
                            $item->goal_difference,
                            $item->points,
                            $item->season,
                            $item->league_name
                        ]);
                    }
                    break;

                case 'scorers':
                    fputcsv($file, ['Rank', 'Player', 'Team', 'Goals', 'Assists', 'Matches', 'Season', 'League']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->ranking_position,
                            $item->player_name,
                            $item->team_name,
                            $item->goals,
                            $item->assists,
                            $item->matches_played,
                            $item->season,
                            $item->league_name
                        ]);
                    }
                    break;

                case 'cleansheets':
                    fputcsv($file, ['Rank', 'Goalkeeper', 'Team', 'Clean Sheets', 'Matches', 'Season', 'League']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->ranking_position,
                            $item->goalkeeper_name,
                            $item->team_name,
                            $item->clean_sheets,
                            $item->matches_played,
                            $item->season,
                            $item->league_name
                        ]);
                    }
                    break;

                case 'goalkeepers':
                    fputcsv($file, ['Rank', 'Goalkeeper', 'Team', 'Goals Conceded', 'Matches', 'Save Percentage', 'Season', 'League']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->ranking_position,
                            $item->goalkeeper_name,
                            $item->team_name,
                            $item->goals_conceded,
                            $item->matches_played,
                            $item->save_percentage,
                            $item->season,
                            $item->league_name
                        ]);
                    }
                    break;
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
