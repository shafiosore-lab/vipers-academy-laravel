<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\WebsitePlayer;
use App\Models\PlayerGameStats;
use App\Services\AiStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PlayerController extends Controller
{
    /**
     * Display all players grouped by category
     */
    public function index()
    {
        // Get website players grouped by category
        $categories = WebsitePlayer::orderBy('category')
            ->orderBy('last_name')
            ->get()
            ->groupBy('category');

        return view('players.index', compact('categories'));
    }

    /**
     * Show player statistics - redirect to overview for better modularity
     */
    public function stats($id)
    {
        return redirect()->route('players.overview', $id);
    }

    /**
     * Show player overview
     */
    public function overview($id)
    {
        $player = WebsitePlayer::findOrFail($id);
        $allPlayers = WebsitePlayer::orderBy('last_name')->orderBy('first_name')->get();

        return view('players.overview', compact('player', 'allPlayers'));
    }

    /**
     * Show player statistics
     */
    public function statistics($id)
    {
        $player = WebsitePlayer::findOrFail($id);
        $allPlayers = WebsitePlayer::orderBy('last_name')->orderBy('first_name')->get();

        return view('players.statistics', compact('player', 'allPlayers'));
    }

    /**
     * Show player AI insights
     */
    public function aiInsights($id)
    {
        $player = WebsitePlayer::findOrFail($id);

        return view('players.ai-insights', compact('player'));
    }

    /**
     * Show player biography
     */
    public function biography($id)
    {
        $player = WebsitePlayer::findOrFail($id);

        return view('players.biography', compact('player'));
    }

    /**
     * Show player career
     */
    public function career($id)
    {
        $player = WebsitePlayer::findOrFail($id);

        return view('players.career', compact('player'));
    }

    /**
     * Show player details
     */
    public function show($id)
    {
        $player = WebsitePlayer::findOrFail($id);
        return view('players.show', compact('player'));
    }





    /**
     * Record comprehensive game statistics for a website player
     */
    public function recordGameStats(Request $request, $id)
    {
        // Validate basic required fields
        $request->validate([
            'player_id' => 'required|integer|exists:website_players,id',
            'game_date' => 'required|date|before_or_equal:today',
            'opponent_team' => 'required|string|max:255',
            'minutes_played' => 'required|integer|min:0|max:120',
            'tournament' => 'nullable|string|max:255',
            'goals_scored' => 'nullable|integer|min:0',
            'assists' => 'nullable|integer|min:0',
            'shots_on_target' => 'nullable|integer|min:0',
            'passes_completed' => 'nullable|integer|min:0',
            'tackles' => 'nullable|integer|min:0',
            'interceptions' => 'nullable|integer|min:0',
            'saves' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:10',
            'yellow_cards' => 'nullable|integer|min:0|max:2',
            'red_cards' => 'nullable|integer|min:0|max:1',
            'game_summary' => 'nullable|string|max:1000',
        ]);

        $player = WebsitePlayer::findOrFail($request->player_id);

        // Handle AI-powered statistics extraction if enabled
        $gameStats = $request->except(['_token', 'player_id']);
        if ($request->has('use_ai_extraction') && !empty($request->game_summary)) {
            $aiService = new AiStatisticsService();
            $aiStats = $aiService->extractStatisticsFromSummary($request->game_summary);

            // Merge AI-extracted stats with manual input (manual input takes precedence)
            $gameStats = array_merge($aiStats, array_filter($gameStats));
        }

        // Create game statistics record
        $gameRecord = PlayerGameStats::create([
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

        // Check if this is an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Game statistics recorded successfully!',
                'player' => $player->fresh()->toArray(),
                'game_record' => $gameRecord->toArray()
            ]);
        }

        return redirect()->back()->with('success', 'Game statistics recorded successfully!');
    }

    /**
     * Search players for AJAX requests
     */
    public function searchPlayers(Request $request)
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 10);

        $players = WebsitePlayer::query()
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQuery) use ($query) {
                    $subQuery->where('first_name', 'like', "%{$query}%")
                            ->orWhere('last_name', 'like', "%{$query}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"]);
                });
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->limit($limit)
            ->get()
            ->map(function ($player) {
                return [
                    'id' => $player->id,
                    'text' => $player->full_name . ' - ' . ucfirst($player->position) . ' (' . $player->formatted_category . ')',
                    'first_name' => $player->first_name,
                    'last_name' => $player->last_name,
                    'position' => $player->position,
                    'category' => $player->category,
                ];
            });

        return response()->json(['results' => $players]);
    }

    /**
     * Show player rankings
     */
    public function rankings()
    {
        $topScorers = WebsitePlayer::orderBy('goals', 'desc')->take(10)->get();
        $topAssists = WebsitePlayer::orderBy('assists', 'desc')->take(10)->get();

        return view('players.rankings', compact('topScorers', 'topAssists'));
    }
}
