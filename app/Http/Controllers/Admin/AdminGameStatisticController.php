<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlayerGameStats;
use App\Models\WebsitePlayer;
use App\Services\AiStatisticsService;
use Illuminate\Http\Request;

class AdminGameStatisticController extends Controller
{
    protected $aiService;

    public function __construct(AiStatisticsService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $statistics = PlayerGameStats::with('player')->latest()->paginate(20);
        return view('admin.game_statistics', compact('statistics'));
    }

    public function create()
    {
        $players = WebsitePlayer::select('id', 'first_name', 'last_name', 'position')->orderBy('last_name')->orderBy('first_name')->get();
        return view('admin.game_statistic_create', compact('players'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:website_players,id',
            'game_date' => 'required|date|before_or_equal:today',
            'opponent_team' => 'required|string|max:255',
            'tournament' => 'nullable|string|max:255',
            'minutes_played' => 'required|integer|min:0|max:120',
            'game_summary' => 'nullable|string|max:1000',
            'use_ai' => 'boolean',
            // Manual stats fields
            'goals_scored' => 'nullable|integer|min:0',
            'assists' => 'nullable|integer|min:0',
            'shots_on_target' => 'nullable|integer|min:0',
            'passes_completed' => 'nullable|integer|min:0',
            'tackles' => 'nullable|integer|min:0',
            'interceptions' => 'nullable|integer|min:0',
            'saves' => 'nullable|integer|min:0',
            'yellow_cards' => 'nullable|integer|min:0|max:2',
            'red_cards' => 'nullable|integer|min:0|max:1',
            'rating' => 'nullable|numeric|min:0|max:10',
        ]);

        $player = WebsitePlayer::findOrFail($request->player_id);

        // Handle AI-powered statistics extraction if enabled
        $gameStats = $request->except(['_token', 'player_id']);
        if ($request->has('use_ai') && !empty($request->game_summary)) {
            $aiStats = $this->aiService->extractStatisticsFromSummary($request->game_summary);

            // Merge AI-extracted stats with manual input (manual input takes precedence)
            $gameStats = array_merge($aiStats, array_filter($gameStats));
        }

        // Create game statistics record
        PlayerGameStats::create([
            'player_id' => $player->id,
            'game_date' => $request->game_date,
            'minutes_played' => $request->minutes_played,
            'opponent_team' => $request->opponent_team,
            'tournament' => $request->tournament,
            'goals_scored' => $gameStats['goals_scored'] ?? 0,
            'assists' => $gameStats['assists'] ?? 0,
            'shots_on_target' => $gameStats['shots_on_target'] ?? 0,
            'passes_completed' => $gameStats['passes_completed'] ?? 0,
            'tackles' => $gameStats['tackles'] ?? 0,
            'interceptions' => $gameStats['interceptions'] ?? 0,
            'saves' => $gameStats['saves'] ?? 0,
            'rating' => $gameStats['rating'] ?? null,
            'yellow_cards' => $gameStats['yellow_cards'] ?? 0,
            'red_cards' => $gameStats['red_cards'] ?? 0,
            'game_summary' => $request->game_summary,
        ]);

        // Recalculate cumulative statistics
        $player->recalculateCumulativeStats();

        return redirect()->route('admin.game-statistics.index')->with('success', 'Game statistics added successfully.');
    }

    public function show($id)
    {
        $statistic = PlayerGameStats::with('player')->findOrFail($id);
        return view('admin.game_statistic_show', compact('statistic'));
    }

    public function edit($id)
    {
        $statistic = PlayerGameStats::findOrFail($id);
        $players = WebsitePlayer::select('id', 'first_name', 'last_name', 'position')->orderBy('last_name')->orderBy('first_name')->get();
        return view('admin.game_statistic_edit', compact('statistic', 'players'));
    }

    public function update(Request $request, $id)
    {
        $statistic = PlayerGameStats::findOrFail($id);

        $request->validate([
            'player_id' => 'required|exists:website_players,id',
            'game_date' => 'required|date|before_or_equal:today',
            'opponent_team' => 'required|string|max:255',
            'tournament' => 'nullable|string|max:255',
            'minutes_played' => 'required|integer|min:0|max:120',
            'game_summary' => 'nullable|string|max:1000',
            'goals_scored' => 'nullable|integer|min:0',
            'assists' => 'nullable|integer|min:0',
            'shots_on_target' => 'nullable|integer|min:0',
            'passes_completed' => 'nullable|integer|min:0',
            'tackles' => 'nullable|integer|min:0',
            'interceptions' => 'nullable|integer|min:0',
            'saves' => 'nullable|integer|min:0',
            'yellow_cards' => 'nullable|integer|min:0|max:2',
            'red_cards' => 'nullable|integer|min:0|max:1',
            'rating' => 'nullable|numeric|min:0|max:10',
        ]);

        $player = WebsitePlayer::findOrFail($request->player_id);

        // Handle AI-powered statistics extraction if enabled
        $gameStats = $request->except(['_token', 'player_id']);
        if ($request->has('use_ai') && !empty($request->game_summary)) {
            $aiStats = $this->aiService->extractStatisticsFromSummary($request->game_summary);

            // Merge AI-extracted stats with manual input (manual input takes precedence)
            $gameStats = array_merge($aiStats, array_filter($gameStats));
        }

        $statistic->update([
            'player_id' => $player->id,
            'game_date' => $request->game_date,
            'minutes_played' => $request->minutes_played,
            'opponent_team' => $request->opponent_team,
            'tournament' => $request->tournament,
            'goals_scored' => $gameStats['goals_scored'] ?? 0,
            'assists' => $gameStats['assists'] ?? 0,
            'shots_on_target' => $gameStats['shots_on_target'] ?? 0,
            'passes_completed' => $gameStats['passes_completed'] ?? 0,
            'tackles' => $gameStats['tackles'] ?? 0,
            'interceptions' => $gameStats['interceptions'] ?? 0,
            'saves' => $gameStats['saves'] ?? 0,
            'rating' => $gameStats['rating'] ?? null,
            'yellow_cards' => $gameStats['yellow_cards'] ?? 0,
            'red_cards' => $gameStats['red_cards'] ?? 0,
            'game_summary' => $request->game_summary,
        ]);

        // Recalculate cumulative statistics
        $player->recalculateCumulativeStats();

        return redirect()->route('admin.game-statistics.index')->with('success', 'Game statistics updated successfully.');
    }

    public function destroy($id)
    {
        $statistic = PlayerGameStats::findOrFail($id);
        $player = $statistic->player;

        $statistic->delete();

        // Recalculate cumulative statistics
        $player->recalculateCumulativeStats();

        return redirect()->route('admin.game-statistics.index')->with('success', 'Game statistics deleted successfully.');
    }

}
