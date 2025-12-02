<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameStatistic;
use App\Models\Player;
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
        $statistics = GameStatistic::with('player')->latest()->paginate(20);
        return view('admin.game_statistics', compact('statistics'));
    }

    public function create()
    {
        $players = Player::select('id', 'name', 'position')->orderBy('name')->get();
        return view('admin.game_statistic_create', compact('players'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'game_date' => 'required|date',
            'opponent' => 'required|string|max:255',
            'tournament' => 'nullable|string|max:255',
            'game_summary' => 'nullable|string',
            'use_ai' => 'boolean',
            // Manual stats fields
            'goals_scored' => 'nullable|integer|min:0',
            'assists' => 'nullable|integer|min:0',
            'minutes_played' => 'nullable|integer|min:0|max:120',
            'shots_on_target' => 'nullable|integer|min:0',
            'passes_completed' => 'nullable|integer|min:0',
            'tackles' => 'nullable|integer|min:0',
            'interceptions' => 'nullable|integer|min:0',
            'saves' => 'nullable|integer|min:0',
            'yellow_cards' => 'nullable|integer|min:0|max:2',
            'red_cards' => 'nullable|integer|min:0|max:1',
            'rating' => 'nullable|numeric|min:0|max:10',
        ]);

        $data = $request->only([
            'player_id', 'game_date', 'opponent', 'tournament',
            'goals_scored', 'assists', 'minutes_played', 'shots_on_target',
            'passes_completed', 'tackles', 'interceptions', 'saves',
            'yellow_cards', 'red_cards', 'rating'
        ]);

        // If AI processing is requested and game summary is provided
        if ($request->boolean('use_ai') && $request->filled('game_summary')) {
            $aiStats = $this->aiService->extractStatisticsFromSummary($request->game_summary);
            $data = array_merge($data, $aiStats);
            $data['ai_generated'] = true;
            $data['game_summary'] = $request->game_summary;
        } else {
            $data['ai_generated'] = false;
            $data['game_summary'] = $request->game_summary;
        }

        // Set defaults for null values
        $data = array_merge([
            'goals_scored' => 0,
            'assists' => 0,
            'minutes_played' => 0,
            'shots_on_target' => 0,
            'passes_completed' => 0,
            'tackles' => 0,
            'interceptions' => 0,
            'saves' => 0,
            'yellow_cards' => 0,
            'red_cards' => 0,
        ], $data);

        GameStatistic::create($data);

        // Update player's cumulative statistics
        $this->updatePlayerStats($request->player_id);

        return redirect()->route('admin.game-statistics.index')->with('success', 'Game statistics added successfully.');
    }

    public function show($id)
    {
        $statistic = GameStatistic::with('player')->findOrFail($id);
        return view('admin.game_statistic_show', compact('statistic'));
    }

    public function edit($id)
    {
        $statistic = GameStatistic::findOrFail($id);
        $players = Player::select('id', 'name', 'position')->orderBy('name')->get();
        return view('admin.game_statistic_edit', compact('statistic', 'players'));
    }

    public function update(Request $request, $id)
    {
        $statistic = GameStatistic::findOrFail($id);

        $request->validate([
            'player_id' => 'required|exists:players,id',
            'game_date' => 'required|date',
            'opponent' => 'required|string|max:255',
            'tournament' => 'nullable|string|max:255',
            'game_summary' => 'nullable|string',
            'goals_scored' => 'nullable|integer|min:0',
            'assists' => 'nullable|integer|min:0',
            'minutes_played' => 'nullable|integer|min:0|max:120',
            'shots_on_target' => 'nullable|integer|min:0',
            'passes_completed' => 'nullable|integer|min:0',
            'tackles' => 'nullable|integer|min:0',
            'interceptions' => 'nullable|integer|min:0',
            'saves' => 'nullable|integer|min:0',
            'yellow_cards' => 'nullable|integer|min:0|max:2',
            'red_cards' => 'nullable|integer|min:0|max:1',
            'rating' => 'nullable|numeric|min:0|max:10',
        ]);

        $data = $request->only([
            'player_id', 'game_date', 'opponent', 'tournament',
            'goals_scored', 'assists', 'minutes_played', 'shots_on_target',
            'passes_completed', 'tackles', 'interceptions', 'saves',
            'yellow_cards', 'red_cards', 'rating', 'game_summary'
        ]);

        // Set defaults for null values
        $data = array_merge([
            'goals_scored' => 0,
            'assists' => 0,
            'minutes_played' => 0,
            'shots_on_target' => 0,
            'passes_completed' => 0,
            'tackles' => 0,
            'interceptions' => 0,
            'saves' => 0,
            'yellow_cards' => 0,
            'red_cards' => 0,
        ], $data);

        $statistic->update($data);

        // Update player's cumulative statistics
        $this->updatePlayerStats($request->player_id);

        return redirect()->route('admin.game-statistics.index')->with('success', 'Game statistics updated successfully.');
    }

    public function destroy($id)
    {
        $statistic = GameStatistic::findOrFail($id);
        $playerId = $statistic->player_id;

        $statistic->delete();

        // Update player's cumulative statistics
        $this->updatePlayerStats($playerId);

        return redirect()->route('admin.game-statistics.index')->with('success', 'Game statistics deleted successfully.');
    }

    /**
     * Update player's cumulative statistics based on all their game statistics
     */
    private function updatePlayerStats($playerId)
    {
        $player = Player::findOrFail($playerId);

        $stats = GameStatistic::where('player_id', $playerId)->get();

        $totalMatches = $stats->count();
        $totalGoals = $stats->sum('goals_scored');
        $totalAssists = $stats->sum('assists');
        $averageRating = $stats->avg('rating');

        $player->update([
            'matches_played' => $totalMatches,
            'goals_scored' => $totalGoals,
            'assists' => $totalAssists,
            'performance_rating' => $averageRating ? round($averageRating, 2) : null,
        ]);
    }
}
