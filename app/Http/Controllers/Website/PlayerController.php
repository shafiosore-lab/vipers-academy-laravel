<?php

namespace App\Http\Controllers\Website;

use App\Models\Player;
use App\Models\WebsitePlayer;
use App\Models\PlayerGameStats;
use App\Models\AiInsight;
use App\Services\AiStatisticsService;
use App\Services\AiInsightsService;
use App\Services\AiInsightsGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PlayerController extends \App\Http\Controllers\Controller
{
    /**
     * Display all players
     */
    public function index(Request $request)
    {
        try {
            // Check if the table exists before querying
            if (!\Schema::hasTable('website_uploaded_players')) {
                \Log::warning('PlayerController@index: website_uploaded_players table does not exist');
                $players = collect();
                return view('website.players.index', compact('players'));
            }

            // Get players with pagination to avoid loading too many at once
            $perPage = 50; // Adjust as needed
            $players = WebsitePlayer::orderBy('last_name')
                ->orderBy('first_name')
                ->paginate($perPage);

            // Debug logging
            \Log::info('PlayerController@index: Fetched website players', [
                'total_players' => $players->total(),
                'current_page' => $players->currentPage(),
                'per_page' => $perPage
            ]);
        } catch (\Exception $e) {
            // If table doesn't exist or other error, return empty collection
            $players = collect();

            // Only log if it's not a "table doesn\'t exist" error (we already handle that above)
            if (strpos($e->getMessage(), ' doesn\'t exist') === false) {
                \Log::error('PlayerController@index: Exception occurred', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        return view('website.players.index', compact('players'));
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

        return view('website.players.overview', compact('player'));
    }

    /**
     * Show player statistics
     */
    public function statistics($id)
    {
        $player = WebsitePlayer::findOrFail($id);

        return view('website.players.statistics', compact('player'));
    }

    /**
     * Show player AI insights
     *
     * Generates real-time AI insights based on player's game statistics and performance data.
     */
    public function aiInsights($id)
    {
        $player = WebsitePlayer::findOrFail($id);

        // Generate insights based on player's game statistics
        $insights = $this->generatePlayerInsights($player);

        return view('website.players.ai-insights', compact('player', 'insights'));
    }

    /**
     * Generate AI insights for WebsitePlayer based on their statistics
     */
    protected function generatePlayerInsights(WebsitePlayer $player): array
    {
        $gameStats = $player->gameStats;
        $dataPoints = $gameStats->count();

        $insights = [];

        // Generate strengths insight
        $insights['strengths'] = $this->generateStrengthsInsight($player, $gameStats, $dataPoints);

        // Generate development areas insight
        $insights['development'] = $this->generateDevelopmentInsight($player, $gameStats, $dataPoints);

        // Generate performance trend insight
        $insights['trend'] = $this->generateTrendInsight($player, $gameStats, $dataPoints);

        // Generate playing style insight
        $insights['style'] = $this->generateStyleInsight($player, $gameStats, $dataPoints);

        return $insights;
    }

    protected function generateStrengthsInsight(WebsitePlayer $player, $gameStats, int $dataPoints): array
    {
        $strengths = [];

        if ($dataPoints === 0) {
            return [
                'insight_content' => 'Insufficient game data to analyze strengths. More match statistics needed.',
                'confidence_level' => 'low',
                'confidence_score' => 30,
                'insight_data' => ['strengths' => []]
            ];
        }

        // Analyze goals and assists
        $totalGoals = $player->goals ?? 0;
        $totalAssists = $player->assists ?? 0;
        $appearances = $player->appearances ?? 0;

        if ($appearances > 0) {
            $goalsPerGame = $totalGoals / $appearances;
            $assistsPerGame = $totalAssists / $appearances;

            if ($goalsPerGame > 0.5) {
                $strengths[] = "Strong goal-scoring ability with {$totalGoals} goals in {$appearances} appearances";
            }
            if ($assistsPerGame > 0.3) {
                $strengths[] = "Creative playmaking with {$totalAssists} assists across {$appearances} matches";
            }
        }

        // Analyze position-specific strengths
        $position = strtolower($player->position ?? '');
        if ($position === 'defender') {
            $avgTackles = $gameStats->avg('tackles') ?? 0;
            $avgInterceptions = $gameStats->avg('interceptions') ?? 0;
            if ($avgTackles > 2 || $avgInterceptions > 1.5) {
                $strengths[] = "Solid defensive capabilities with strong tackling and interception skills";
            }
        } elseif (in_array($position, ['midfielder', 'central midfielder'])) {
            $avgPasses = $gameStats->avg('passes_completed') ?? 0;
            if ($avgPasses > 25) {
                $strengths[] = "Reliable passing range with consistent ball distribution";
            }
        } elseif (in_array($position, ['forward', 'striker'])) {
            $avgShots = $gameStats->avg('shots_on_target') ?? 0;
            if ($avgShots > 1.5) {
                $strengths[] = "Good finishing ability with accurate shot placement";
            }
        }

        $content = empty($strengths)
            ? "Player shows consistent performance across matches. Continued development will help identify specific strengths."
            : implode('. ', $strengths) . '.';

        $confidenceScore = $dataPoints >= 5 ? 85 : ($dataPoints >= 3 ? 65 : 40);
        $confidenceLevel = $confidenceScore >= 70 ? 'high' : ($confidenceScore >= 50 ? 'medium' : 'low');

        return [
            'insight_content' => $content,
            'confidence_level' => $confidenceLevel,
            'confidence_score' => $confidenceScore,
            'insight_data' => [
                'strengths' => $strengths,
                'game_metrics' => [
                    'total_goals' => $totalGoals,
                    'total_assists' => $totalAssists,
                    'appearances' => $appearances,
                    'data_points' => $dataPoints,
                ]
            ]
        ];
    }

    protected function generateDevelopmentInsight(WebsitePlayer $player, $gameStats, int $dataPoints): array
    {
        $areas = [];

        if ($dataPoints === 0) {
            return [
                'insight_content' => 'More match data required to identify development areas.',
                'confidence_level' => 'low',
                'confidence_score' => 25,
                'insight_data' => ['areas' => []]
            ];
        }

        // Analyze discipline
        $yellowCards = $player->yellow_cards ?? 0;
        $redCards = $player->red_cards ?? 0;
        $appearances = $player->appearances ?? 0;

        if ($appearances > 0) {
            $cardsPerGame = ($yellowCards + $redCards) / $appearances;
            if ($cardsPerGame > 0.2) {
                $areas[] = "Improve disciplinary consistency to reduce booking frequency";
            }
        }

        // Analyze conversion rate
        $totalGoals = $player->goals ?? 0;
        $totalShotsOnTarget = $gameStats->sum('shots_on_target') ?? 0;

        if ($totalShotsOnTarget > 10) {
            $conversionRate = ($totalGoals / $totalShotsOnTarget) * 100;
            if ($conversionRate < 25) {
                $areas[] = "Work on finishing technique to improve goal conversion rate";
            }
        }

        // Position-specific development areas
        $position = strtolower($player->position ?? '');
        if ($position === 'goalkeeper') {
            $avgSaves = $gameStats->avg('saves') ?? 0;
            if ($avgSaves < 2 && $dataPoints > 3) {
                $areas[] = "Develop shot-stopping skills and positioning";
            }
        }

        $content = empty($areas)
            ? "No major development areas identified. Focus on maintaining current performance levels."
            : implode('. ', $areas) . '.';

        $confidenceScore = $dataPoints >= 5 ? 80 : ($dataPoints >= 3 ? 60 : 35);
        $confidenceLevel = $confidenceScore >= 70 ? 'high' : ($confidenceScore >= 50 ? 'medium' : 'low');

        return [
            'insight_content' => $content,
            'confidence_level' => $confidenceLevel,
            'confidence_score' => $confidenceScore,
            'insight_data' => [
                'areas' => $areas,
                'discipline_metrics' => [
                    'yellow_cards' => $yellowCards,
                    'red_cards' => $redCards,
                    'cards_per_game' => $appearances > 0 ? round(($yellowCards + $redCards) / $appearances, 2) : 0,
                ],
                'conversion_rate' => $totalShotsOnTarget > 0 ? round(($totalGoals / $totalShotsOnTarget) * 100, 1) : 0,
                'data_points' => $dataPoints,
            ]
        ];
    }

    protected function generateTrendInsight(WebsitePlayer $player, $gameStats, int $dataPoints): array
    {
        if ($dataPoints < 3) {
            return [
                'insight_content' => 'Insufficient match data for trend analysis.',
                'confidence_level' => 'low',
                'confidence_score' => 20,
                'insight_data' => ['trend' => 'insufficient_data']
            ];
        }

        // Sort games by date and analyze recent performance
        $sortedGames = $gameStats->sortBy('game_date');
        $recentGames = $sortedGames->take(5);
        $olderGames = $sortedGames->slice(5)->take(5);

        $recentAvgRating = $recentGames->avg('rating') ?? 0;
        $olderAvgRating = $olderGames->avg('rating') ?? 0;

        $trend = 'stable';
        $description = 'Performance has been consistent across recent matches.';

        if ($recentAvgRating > $olderAvgRating + 0.5) {
            $trend = 'improving';
            $description = 'Showing positive development with improved ratings in recent matches.';
        } elseif ($recentAvgRating < $olderAvgRating - 0.5) {
            $trend = 'declining';
            $description = 'Recent performances show a slight decline. Consider reviewing training and match preparation.';
        }

        $confidenceScore = $dataPoints >= 10 ? 75 : ($dataPoints >= 5 ? 55 : 35);
        $confidenceLevel = $confidenceScore >= 70 ? 'high' : ($confidenceScore >= 50 ? 'medium' : 'low');

        return [
            'insight_content' => $description,
            'confidence_level' => $confidenceLevel,
            'confidence_score' => $confidenceScore,
            'insight_data' => [
                'trend' => $trend,
                'recent_avg_rating' => round($recentAvgRating, 2),
                'older_avg_rating' => round($olderAvgRating, 2),
                'rating_change' => round($recentAvgRating - $olderAvgRating, 2),
                'data_points' => $dataPoints,
            ]
        ];
    }

    protected function generateStyleInsight(WebsitePlayer $player, $gameStats, int $dataPoints): array
    {
        $position = strtolower($player->position ?? '');
        $description = '';

        // Analyze contribution patterns
        $totalGoals = $player->goals ?? 0;
        $totalAssists = $player->assists ?? 0;
        $avgTackles = $gameStats->avg('tackles') ?? 0;
        $avgInterceptions = $gameStats->avg('interceptions') ?? 0;

        $attackingContribution = $totalGoals + $totalAssists;
        $defensiveContribution = $avgTackles + $avgInterceptions;

        if ($attackingContribution > $defensiveContribution * 1.5) {
            $description = "Primarily contributes through attacking actions and creative play.";
        } elseif ($defensiveContribution > $attackingContribution * 1.5) {
            $description = "Strong focus on defensive duties and team protection.";
        } else {
            $description = "Balanced player who contributes effectively in both attacking and defensive phases.";
        }

        // Add position context
        if ($position) {
            $positionDescriptions = [
                'goalkeeper' => 'Operates as the last line of defense with shot-stopping and distribution duties.',
                'defender' => 'Provides defensive stability and occasionally contributes to attacking moves.',
                'midfielder' => 'Acts as the engine of the team, linking defense to attack.',
                'forward' => 'Focuses on goal-scoring opportunities and creating chances.',
                'striker' => 'Specializes in finishing and holding up play for teammates.'
            ];

            if (isset($positionDescriptions[$position])) {
                $description .= ' ' . $positionDescriptions[$position];
            }
        }

        $confidenceScore = $dataPoints >= 5 ? 65 : ($dataPoints >= 3 ? 45 : 30);
        $confidenceLevel = $confidenceScore >= 60 ? 'medium' : 'low';

        return [
            'insight_content' => $description,
            'confidence_level' => $confidenceLevel,
            'confidence_score' => $confidenceScore,
            'insight_data' => [
                'primary_role' => $attackingContribution > $defensiveContribution * 1.5 ? 'Attacking' :
                                 ($defensiveContribution > $attackingContribution * 1.5 ? 'Defensive' : 'Balanced'),
                'attacking_contribution' => $attackingContribution,
                'defensive_contribution' => round($defensiveContribution, 2),
                'position' => $position,
                'data_points' => $dataPoints,
            ]
        ];
    }

    /**
     * Show player biography
     */
    public function biography($id)
    {
        $player = WebsitePlayer::findOrFail($id);

        return view('website.players.biography', compact('player'));
    }

    /**
     * Show player career
     */
    public function career($id)
    {
        $player = WebsitePlayer::findOrFail($id);

        return view('website.players.career', compact('player'));
    }

    /**
     * Show player details
     */
    public function show($id)
    {
        $player = WebsitePlayer::findOrFail($id);
        return view('website.players.show', compact('player'));
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

        // Debug logging
        \Log::info('PlayerController@rankings: Fetched rankings', [
            'top_scorers_count' => $topScorers->count(),
            'top_assists_count' => $topAssists->count(),
            'top_scorer' => $topScorers->first() ? $topScorers->first()->full_name : null,
            'top_assist' => $topAssists->first() ? $topAssists->first()->full_name : null
        ]);

        return view('website.players.rankings', compact('topScorers', 'topAssists'));
    }
}
