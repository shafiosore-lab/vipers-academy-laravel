<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeagueStanding;
use App\Models\TopScorer;
use App\Models\CleanSheet;
use App\Models\GoalkeeperRanking;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class AdminStandingsController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'league');

        switch ($type) {
            case 'league':
                $data = LeagueStanding::orderBy('season', 'desc')
                    ->orderBy('position')
                    ->paginate(20);
                break;
            case 'scorers':
                $data = TopScorer::orderBy('season', 'desc')
                    ->orderBy('ranking_position')
                    ->paginate(20);
                break;
            case 'cleansheets':
                $data = CleanSheet::orderBy('season', 'desc')
                    ->orderBy('position')
                    ->paginate(20);
                break;
            case 'goalkeepers':
                $data = GoalkeeperRanking::orderBy('season', 'desc')
                    ->orderBy('position')
                    ->paginate(20);
                break;
            default:
                $data = LeagueStanding::orderBy('season', 'desc')
                    ->orderBy('position')
                    ->paginate(20);
        }

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

    public function store(Request $request)
    {
        $type = $request->get('type');

        switch ($type) {
            case 'league':
                $validated = $request->validate([
                    'season' => 'required|string|max:20',
                    'league_name' => 'required|string|max:255',
                    'team_name' => 'required|string|max:255',
                    'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'position' => 'required|integer|min:1',
                    'played' => 'required|integer|min:0',
                    'won' => 'required|integer|min:0',
                    'drawn' => 'required|integer|min:0',
                    'lost' => 'required|integer|min:0',
                    'goals_for' => 'required|integer|min:0',
                    'goals_against' => 'required|integer|min:0',
                    'points' => 'required|integer|min:0',
                    'clean_sheets' => 'nullable|integer|min:0',
                    'failed_to_score' => 'nullable|integer|min:0',
                    'form' => 'nullable|string|max:10',
                    'status' => 'nullable|string|max:50',
                    'notes' => 'nullable|string',
                    'is_vipers_team' => 'boolean',
                ]);

                if ($request->hasFile('team_logo')) {
                    $validated['team_logo'] = $request->file('team_logo')->store('team-logos', 'public');
                }

                $validated['goal_difference'] = $validated['goals_for'] - $validated['goals_against'];
                $validated['points_per_game'] = $validated['played'] > 0 ? $validated['points'] / $validated['played'] : 0;

                LeagueStanding::create($validated);
                break;

            case 'scorers':
                $validated = $request->validate([
                    'season' => 'required|string|max:20',
                    'league_name' => 'required|string|max:255',
                    'player_name' => 'required|string|max:255',
                    'player_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'team_name' => 'required|string|max:255',
                    'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'ranking_position' => 'required|integer|min:1',
                    'goals' => 'required|integer|min:0',
                    'assists' => 'required|integer|min:0',
                    'appearances' => 'required|integer|min:0',
                    'minutes_played' => 'required|integer|min:0',
                    'shots_on_target' => 'nullable|integer|min:0',
                    'shots_total' => 'nullable|integer|min:0',
                    'penalties_scored' => 'nullable|integer|min:0',
                    'penalties_missed' => 'nullable|integer|min:0',
                    'free_kicks' => 'nullable|integer|min:0',
                    'headers' => 'nullable|integer|min:0',
                    'left_foot' => 'nullable|integer|min:0',
                    'right_foot' => 'nullable|integer|min:0',
                    'inside_box' => 'nullable|integer|min:0',
                    'outside_box' => 'nullable|integer|min:0',
                    'nationality' => 'nullable|string|max:100',
                    'age' => 'nullable|integer|min:15|max:50',
                    'player_position' => 'required|string|max:50',
                    'notes' => 'nullable|string',
                    'is_vipers_player' => 'boolean',
                ]);

                if ($request->hasFile('player_image')) {
                    $validated['player_image'] = $request->file('player_image')->store('player-images', 'public');
                }
                if ($request->hasFile('team_logo')) {
                    $validated['team_logo'] = $request->file('team_logo')->store('team-logos', 'public');
                }

                $validated['goals_per_game'] = $validated['appearances'] > 0 ? $validated['goals'] / $validated['appearances'] : 0;
                $validated['shot_accuracy'] = $validated['shots_total'] > 0 ? ($validated['shots_on_target'] / $validated['shots_total']) * 100 : null;

                TopScorer::create($validated);

                // Clear relevant caches for immediate content updates
                Cache::tags(['standings', 'top_scorers'])->flush();
                Cache::forget('standings_stats');
                break;

            case 'cleansheets':
                $validated = $request->validate([
                    'season' => 'required|string|max:20',
                    'league_name' => 'required|string|max:255',
                    'goalkeeper_name' => 'required|string|max:255',
                    'goalkeeper_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'team_name' => 'required|string|max:255',
                    'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'position' => 'required|integer|min:1',
                    'clean_sheets' => 'required|integer|min:0',
                    'appearances' => 'required|integer|min:0',
                    'saves' => 'required|integer|min:0',
                    'goals_conceded' => 'required|integer|min:0',
                    'penalties_saved' => 'nullable|integer|min:0',
                    'penalties_faced' => 'nullable|integer|min:0',
                    'minutes_played' => 'required|integer|min:0',
                    'shots_faced' => 'nullable|integer|min:0',
                    'shots_on_target_faced' => 'nullable|integer|min:0',
                    'nationality' => 'nullable|string|max:100',
                    'age' => 'nullable|integer|min:15|max:50',
                    'height_cm' => 'nullable|decimal:0,2',
                    'dominant_hand' => 'required|string|max:20',
                    'notes' => 'nullable|string',
                    'is_vipers_player' => 'boolean',
                ]);

                if ($request->hasFile('goalkeeper_image')) {
                    $validated['goalkeeper_image'] = $request->file('goalkeeper_image')->store('player-images', 'public');
                }
                if ($request->hasFile('team_logo')) {
                    $validated['team_logo'] = $request->file('team_logo')->store('team-logos', 'public');
                }

                $validated['goals_conceded_per_game'] = $validated['appearances'] > 0 ? $validated['goals_conceded'] / $validated['appearances'] : 0;
                $validated['save_percentage'] = $validated['shots_on_target_faced'] > 0 ? ($validated['saves'] / $validated['shots_on_target_faced']) * 100 : null;
                $validated['clean_sheet_percentage'] = $validated['appearances'] > 0 ? ($validated['clean_sheets'] / $validated['appearances']) * 100 : null;
                $validated['shots_faced_per_game'] = $validated['appearances'] > 0 ? $validated['shots_faced'] / $validated['appearances'] : 0;

                CleanSheet::create($validated);

                // Clear relevant caches for immediate content updates
                Cache::tags(['standings', 'clean_sheets'])->flush();
                Cache::forget('standings_stats');
                break;

            case 'goalkeepers':
                $validated = $request->validate([
                    'season' => 'required|string|max:20',
                    'league_name' => 'required|string|max:255',
                    'goalkeeper_name' => 'required|string|max:255',
                    'goalkeeper_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'team_name' => 'required|string|max:255',
                    'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'position' => 'required|integer|min:1',
                    'appearances' => 'required|integer|min:0',
                    'minutes_played' => 'required|integer|min:0',
                    'saves' => 'required|integer|min:0',
                    'goals_conceded' => 'required|integer|min:0',
                    'clean_sheets' => 'required|integer|min:0',
                    'shots_faced' => 'nullable|integer|min:0',
                    'shots_on_target_faced' => 'nullable|integer|min:0',
                    'penalties_saved' => 'nullable|integer|min:0',
                    'penalties_faced' => 'nullable|integer|min:0',
                    'catches' => 'nullable|integer|min:0',
                    'punches' => 'nullable|integer|min:0',
                    'distribution_completed' => 'nullable|integer|min:0',
                    'distribution_attempted' => 'nullable|integer|min:0',
                    'crosses_claimed' => 'nullable|integer|min:0',
                    'errors_leading_to_goal' => 'nullable|integer|min:0',
                    'overall_rating' => 'nullable|decimal:0,2',
                    'nationality' => 'nullable|string|max:100',
                    'age' => 'nullable|integer|min:15|max:50',
                    'height_cm' => 'nullable|decimal:0,2',
                    'dominant_hand' => 'required|string|max:20',
                    'notes' => 'nullable|string',
                    'is_vipers_player' => 'boolean',
                ]);

                if ($request->hasFile('goalkeeper_image')) {
                    $validated['goalkeeper_image'] = $request->file('goalkeeper_image')->store('player-images', 'public');
                }
                if ($request->hasFile('team_logo')) {
                    $validated['team_logo'] = $request->file('team_logo')->store('team-logos', 'public');
                }

                $validated['goals_conceded_per_game'] = $validated['appearances'] > 0 ? $validated['goals_conceded'] / $validated['appearances'] : 0;
                $validated['save_percentage'] = $validated['shots_on_target_faced'] > 0 ? ($validated['saves'] / $validated['shots_on_target_faced']) * 100 : null;
                $validated['clean_sheet_percentage'] = $validated['appearances'] > 0 ? ($validated['clean_sheets'] / $validated['appearances']) * 100 : null;
                $validated['shots_faced_per_game'] = $validated['appearances'] > 0 ? $validated['shots_faced'] / $validated['appearances'] : 0;
                $validated['penalty_save_percentage'] = $validated['penalties_faced'] > 0 ? ($validated['penalties_saved'] / $validated['penalties_faced']) * 100 : null;
                $validated['distribution_accuracy'] = $validated['distribution_attempted'] > 0 ? ($validated['distribution_completed'] / $validated['distribution_attempted']) * 100 : null;

                GoalkeeperRanking::create($validated);

                // Clear relevant caches for immediate content updates
                Cache::tags(['standings', 'goalkeeper_rankings'])->flush();
                Cache::forget('standings_stats');
                break;
        }

        return redirect()->route('admin.standings.index', ['type' => $type])
            ->with('success', ucfirst($type) . ' data added successfully!');
    }

    public function show(string $id, Request $request)
    {
        $type = $request->get('type', 'league');

        switch ($type) {
            case 'league':
                $item = LeagueStanding::findOrFail($id);
                break;
            case 'scorers':
                $item = TopScorer::findOrFail($id);
                break;
            case 'cleansheets':
                $item = CleanSheet::findOrFail($id);
                break;
            case 'goalkeepers':
                $item = GoalkeeperRanking::findOrFail($id);
                break;
        }

        return view('admin.standings.show', compact('item', 'type'));
    }

    public function edit(string $id, Request $request)
    {
        $type = $request->get('type', 'league');

        switch ($type) {
            case 'league':
                $item = LeagueStanding::findOrFail($id);
                break;
            case 'scorers':
                $item = TopScorer::findOrFail($id);
                break;
            case 'cleansheets':
                $item = CleanSheet::findOrFail($id);
                break;
            case 'goalkeepers':
                $item = GoalkeeperRanking::findOrFail($id);
                break;
        }

        return view('admin.standings.edit', compact('item', 'type'));
    }

    public function update(Request $request, string $id)
    {
        $type = $request->get('type');

        switch ($type) {
            case 'league':
                $item = LeagueStanding::findOrFail($id);
                $validated = $request->validate([
                    'season' => 'required|string|max:20',
                    'league_name' => 'required|string|max:255',
                    'team_name' => 'required|string|max:255',
                    'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'position' => 'required|integer|min:1',
                    'played' => 'required|integer|min:0',
                    'won' => 'required|integer|min:0',
                    'drawn' => 'required|integer|min:0',
                    'lost' => 'required|integer|min:0',
                    'goals_for' => 'required|integer|min:0',
                    'goals_against' => 'required|integer|min:0',
                    'points' => 'required|integer|min:0',
                    'clean_sheets' => 'nullable|integer|min:0',
                    'failed_to_score' => 'nullable|integer|min:0',
                    'form' => 'nullable|string|max:10',
                    'status' => 'nullable|string|max:50',
                    'notes' => 'nullable|string',
                    'is_vipers_team' => 'boolean',
                ]);

                if ($request->hasFile('team_logo')) {
                    if ($item->team_logo) {
                        Storage::disk('public')->delete($item->team_logo);
                    }
                    $validated['team_logo'] = $request->file('team_logo')->store('team-logos', 'public');
                }

                $validated['goal_difference'] = $validated['goals_for'] - $validated['goals_against'];
                $validated['points_per_game'] = $validated['played'] > 0 ? $validated['points'] / $validated['played'] : 0;

                $item->update($validated);
                break;

            case 'scorers':
                $item = TopScorer::findOrFail($id);
                $validated = $request->validate([
                    'season' => 'required|string|max:20',
                    'league_name' => 'required|string|max:255',
                    'player_name' => 'required|string|max:255',
                    'player_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'team_name' => 'required|string|max:255',
                    'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'ranking_position' => 'required|integer|min:1',
                    'goals' => 'required|integer|min:0',
                    'assists' => 'required|integer|min:0',
                    'appearances' => 'required|integer|min:0',
                    'minutes_played' => 'required|integer|min:0',
                    'shots_on_target' => 'nullable|integer|min:0',
                    'shots_total' => 'nullable|integer|min:0',
                    'penalties_scored' => 'nullable|integer|min:0',
                    'penalties_missed' => 'nullable|integer|min:0',
                    'free_kicks' => 'nullable|integer|min:0',
                    'headers' => 'nullable|integer|min:0',
                    'left_foot' => 'nullable|integer|min:0',
                    'right_foot' => 'nullable|integer|min:0',
                    'inside_box' => 'nullable|integer|min:0',
                    'outside_box' => 'nullable|integer|min:0',
                    'nationality' => 'nullable|string|max:100',
                    'age' => 'nullable|integer|min:15|max:50',
                    'player_position' => 'required|string|max:50',
                    'notes' => 'nullable|string',
                    'is_vipers_player' => 'boolean',
                ]);

                if ($request->hasFile('player_image')) {
                    if ($item->player_image) {
                        Storage::disk('public')->delete($item->player_image);
                    }
                    $validated['player_image'] = $request->file('player_image')->store('player-images', 'public');
                }
                if ($request->hasFile('team_logo')) {
                    if ($item->team_logo) {
                        Storage::disk('public')->delete($item->team_logo);
                    }
                    $validated['team_logo'] = $request->file('team_logo')->store('team-logos', 'public');
                }

                $validated['goals_per_game'] = $validated['appearances'] > 0 ? $validated['goals'] / $validated['appearances'] : 0;
                $validated['shot_accuracy'] = $validated['shots_total'] > 0 ? ($validated['shots_on_target'] / $validated['shots_total']) * 100 : null;

                $item->update($validated);
                break;

            case 'cleansheets':
                $item = CleanSheet::findOrFail($id);
                $validated = $request->validate([
                    'season' => 'required|string|max:20',
                    'league_name' => 'required|string|max:255',
                    'goalkeeper_name' => 'required|string|max:255',
                    'goalkeeper_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'team_name' => 'required|string|max:255',
                    'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'position' => 'required|integer|min:1',
                    'clean_sheets' => 'required|integer|min:0',
                    'appearances' => 'required|integer|min:0',
                    'saves' => 'required|integer|min:0',
                    'goals_conceded' => 'required|integer|min:0',
                    'penalties_saved' => 'nullable|integer|min:0',
                    'penalties_faced' => 'nullable|integer|min:0',
                    'minutes_played' => 'required|integer|min:0',
                    'shots_faced' => 'nullable|integer|min:0',
                    'shots_on_target_faced' => 'nullable|integer|min:0',
                    'nationality' => 'nullable|string|max:100',
                    'age' => 'nullable|integer|min:15|max:50',
                    'height_cm' => 'nullable|decimal:0,2',
                    'dominant_hand' => 'required|string|max:20',
                    'notes' => 'nullable|string',
                    'is_vipers_player' => 'boolean',
                ]);

                if ($request->hasFile('goalkeeper_image')) {
                    if ($item->goalkeeper_image) {
                        Storage::disk('public')->delete($item->goalkeeper_image);
                    }
                    $validated['goalkeeper_image'] = $request->file('goalkeeper_image')->store('player-images', 'public');
                }
                if ($request->hasFile('team_logo')) {
                    if ($item->team_logo) {
                        Storage::disk('public')->delete($item->team_logo);
                    }
                    $validated['team_logo'] = $request->file('team_logo')->store('team-logos', 'public');
                }

                $validated['goals_conceded_per_game'] = $validated['appearances'] > 0 ? $validated['goals_conceded'] / $validated['appearances'] : 0;
                $validated['save_percentage'] = $validated['shots_on_target_faced'] > 0 ? ($validated['saves'] / $validated['shots_on_target_faced']) * 100 : null;
                $validated['clean_sheet_percentage'] = $validated['appearances'] > 0 ? ($validated['clean_sheets'] / $validated['appearances']) * 100 : null;
                $validated['shots_faced_per_game'] = $validated['appearances'] > 0 ? $validated['shots_faced'] / $validated['appearances'] : 0;

                $item->update($validated);
                break;

            case 'goalkeepers':
                $item = GoalkeeperRanking::findOrFail($id);
                $validated = $request->validate([
                    'season' => 'required|string|max:20',
                    'league_name' => 'required|string|max:255',
                    'goalkeeper_name' => 'required|string|max:255',
                    'goalkeeper_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'team_name' => 'required|string|max:255',
                    'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'position' => 'required|integer|min:1',
                    'appearances' => 'required|integer|min:0',
                    'minutes_played' => 'required|integer|min:0',
                    'saves' => 'required|integer|min:0',
                    'goals_conceded' => 'required|integer|min:0',
                    'clean_sheets' => 'required|integer|min:0',
                    'shots_faced' => 'nullable|integer|min:0',
                    'shots_on_target_faced' => 'nullable|integer|min:0',
                    'penalties_saved' => 'nullable|integer|min:0',
                    'penalties_faced' => 'nullable|integer|min:0',
                    'catches' => 'nullable|integer|min:0',
                    'punches' => 'nullable|integer|min:0',
                    'distribution_completed' => 'nullable|integer|min:0',
                    'distribution_attempted' => 'nullable|integer|min:0',
                    'crosses_claimed' => 'nullable|integer|min:0',
                    'errors_leading_to_goal' => 'nullable|integer|min:0',
                    'overall_rating' => 'nullable|decimal:0,2',
                    'nationality' => 'nullable|string|max:100',
                    'age' => 'nullable|integer|min:15|max:50',
                    'height_cm' => 'nullable|decimal:0,2',
                    'dominant_hand' => 'required|string|max:20',
                    'notes' => 'nullable|string',
                    'is_vipers_player' => 'boolean',
                ]);

                if ($request->hasFile('goalkeeper_image')) {
                    if ($item->goalkeeper_image) {
                        Storage::disk('public')->delete($item->goalkeeper_image);
                    }
                    $validated['goalkeeper_image'] = $request->file('goalkeeper_image')->store('player-images', 'public');
                }
                if ($request->hasFile('team_logo')) {
                    if ($item->team_logo) {
                        Storage::disk('public')->delete($item->team_logo);
                    }
                    $validated['team_logo'] = $request->file('team_logo')->store('team-logos', 'public');
                }

                $validated['goals_conceded_per_game'] = $validated['appearances'] > 0 ? $validated['goals_conceded'] / $validated['appearances'] : 0;
                $validated['save_percentage'] = $validated['shots_on_target_faced'] > 0 ? ($validated['saves'] / $validated['shots_on_target_faced']) * 100 : null;
                $validated['clean_sheet_percentage'] = $validated['appearances'] > 0 ? ($validated['clean_sheets'] / $validated['appearances']) * 100 : null;
                $validated['shots_faced_per_game'] = $validated['appearances'] > 0 ? $validated['shots_faced'] / $validated['appearances'] : 0;
                $validated['penalty_save_percentage'] = $validated['penalties_faced'] > 0 ? ($validated['penalties_saved'] / $validated['penalties_faced']) * 100 : null;
                $validated['distribution_accuracy'] = $validated['distribution_attempted'] > 0 ? ($validated['distribution_completed'] / $validated['distribution_attempted']) * 100 : null;

                $item->update($validated);
                break;
        }

        // Clear relevant caches for immediate content updates
        Cache::tags(['standings', 'standings_' . $type])->flush();
        Cache::forget('standings_stats');

        return redirect()->route('admin.standings.index', ['type' => $type])
            ->with('success', ucfirst($type) . ' data updated successfully!');
    }

    public function destroy(string $id, Request $request)
    {
        $type = $request->get('type');

        switch ($type) {
            case 'league':
                $item = LeagueStanding::findOrFail($id);
                if ($item->team_logo) {
                    Storage::disk('public')->delete($item->team_logo);
                }
                $item->delete();
                break;
            case 'scorers':
                $item = TopScorer::findOrFail($id);
                if ($item->player_image) {
                    Storage::disk('public')->delete($item->player_image);
                }
                if ($item->team_logo) {
                    Storage::disk('public')->delete($item->team_logo);
                }
                $item->delete();
                break;
            case 'cleansheets':
                $item = CleanSheet::findOrFail($id);
                if ($item->goalkeeper_image) {
                    Storage::disk('public')->delete($item->goalkeeper_image);
                }
                if ($item->team_logo) {
                    Storage::disk('public')->delete($item->team_logo);
                }
                $item->delete();
                break;
            case 'goalkeepers':
                $item = GoalkeeperRanking::findOrFail($id);
                if ($item->goalkeeper_image) {
                    Storage::disk('public')->delete($item->goalkeeper_image);
                }
                if ($item->team_logo) {
                    Storage::disk('public')->delete($item->team_logo);
                }
                $item->delete();
                break;
        }

        // Clear relevant caches for immediate content updates
        Cache::tags(['standings', 'standings_' . $type])->flush();
        Cache::forget('standings_stats');

        return redirect()->route('admin.standings.index', ['type' => $type])
            ->with('success', ucfirst($type) . ' data deleted successfully!');
    }

    public function bulkImport(Request $request)
    {
        $type = $request->get('type');
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // Handle CSV import logic here
        // This would be a complex implementation for bulk importing standings data

        return redirect()->route('admin.standings.index', ['type' => $type])
            ->with('success', 'Bulk import feature coming soon!');
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'league');
        $season = $request->get('season');
        $league = $request->get('league');

        // Export logic would go here
        // Return CSV or Excel file

        return redirect()->route('admin.standings.index', ['type' => $type])
            ->with('success', 'Export feature coming soon!');
    }
}
