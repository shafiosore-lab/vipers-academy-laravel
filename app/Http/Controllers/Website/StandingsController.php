<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Models\LeagueStanding;
use App\Models\TopScorer;
use App\Models\CleanSheet;
use App\Models\GoalkeeperRanking;
use App\Models\FootballMatch;

class StandingsController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $season = $request->get('season', '2024/25');
        $league = $request->get('league', 'Premier League');

        // Map league query params to actual league names
        $leagueMap = [
            'premier' => 'Premier League',
            'laliga' => 'La Liga',
            'seriea' => 'Serie A',
            'bundesliga' => 'Bundesliga'
        ];

        if (isset($leagueMap[$league])) {
            $league = $leagueMap[$league];
        }

        // Get league standings
        $standings = LeagueStanding::where('season', $season)
            ->where('league_name', $league)
            ->orderBy('position')
            ->get();

        // Handle sorting for different statistics
        $sortBy = $request->get('sort', 'default');

        // Get top scorers
        if ($sortBy === 'goals') {
            $topScorers = TopScorer::where('season', $season)
                ->where('league_name', $league)
                ->orderBy('goals', 'desc')
                ->take(20)
                ->get();
        } else {
            $topScorers = TopScorer::where('season', $season)
                ->where('league_name', $league)
                ->orderBy('ranking_position')
                ->take(20)
                ->get();
        }

        // Get clean sheets
        if ($sortBy === 'clean_sheets') {
            $cleanSheets = CleanSheet::where('season', $season)
                ->where('league_name', $league)
                ->orderBy('clean_sheets', 'desc')
                ->take(20)
                ->get();
        } else {
            $cleanSheets = CleanSheet::where('season', $season)
                ->where('league_name', $league)
                ->orderBy('position')
                ->take(20)
                ->get();
        }

        // Get goalkeeper rankings
        $goalkeeperRankings = GoalkeeperRanking::where('season', $season)
            ->where('league_name', $league)
            ->orderBy('position')
            ->take(20)
            ->get();

        // Get available seasons and leagues
        $availableSeasons = LeagueStanding::distinct()->pluck('season')->sort()->reverse();
        $availableLeagues = LeagueStanding::distinct()->pluck('league_name');

        // Get matches for fixtures and results
        $matches = FootballMatch::orderBy('match_date')->get();

        // Get stats for overview
        $stats = [
            'tournament_matches' => FootballMatch::where('type', 'tournament')->count(),
            'victories' => FootballMatch::where('status', 'completed')
                ->whereRaw('vipers_score > opponent_score')
                ->count(),
            'goals_scored' => FootballMatch::sum('vipers_score'),
            'completed_matches' => FootballMatch::where('status', 'completed')->count(),
            'upcoming_matches' => FootballMatch::where('status', 'upcoming')->count(),
        ];

        return view('standings.index', compact(
            'standings',
            'topScorers',
            'cleanSheets',
            'goalkeeperRankings',
            'season',
            'league',
            'availableSeasons',
            'availableLeagues',
            'matches',
            'stats'
        ));
    }

    public function leagueTable(Request $request)
    {
        $season = $request->get('season', '2024/25');
        $league = $request->get('league', 'Premier League');

        $standings = LeagueStanding::where('season', $season)
            ->where('league_name', $league)
            ->orderBy('position')
            ->get();

        $availableSeasons = LeagueStanding::distinct()->pluck('season')->sort()->reverse();
        $availableLeagues = LeagueStanding::distinct()->pluck('league_name');

        return view('standings.league-table', compact(
            'standings',
            'season',
            'league',
            'availableSeasons',
            'availableLeagues'
        ));
    }

    public function topScorers(Request $request)
    {
        $season = $request->get('season', '2024/25');
        $league = $request->get('league', 'Premier League');

        $topScorers = TopScorer::where('season', $season)
            ->where('league_name', $league)
            ->orderBy('ranking_position')
            ->get();

        $availableSeasons = TopScorer::distinct()->pluck('season')->sort()->reverse();
        $availableLeagues = TopScorer::distinct()->pluck('league_name');

        return view('standings.top-scorers', compact(
            'topScorers',
            'season',
            'league',
            'availableSeasons',
            'availableLeagues'
        ));
    }

    public function cleanSheets(Request $request)
    {
        $season = $request->get('season', '2024/25');
        $league = $request->get('league', 'Premier League');

        $cleanSheets = CleanSheet::where('season', $season)
            ->where('league_name', $league)
            ->orderBy('position')
            ->get();

        $availableSeasons = CleanSheet::distinct()->pluck('season')->sort()->reverse();
        $availableLeagues = CleanSheet::distinct()->pluck('league_name');

        return view('standings.clean-sheets', compact(
            'cleanSheets',
            'season',
            'league',
            'availableSeasons',
            'availableLeagues'
        ));
    }

    public function goalkeepers(Request $request)
    {
        $season = $request->get('season', '2024/25');
        $league = $request->get('league', 'Premier League');

        $goalkeeperRankings = GoalkeeperRanking::where('season', $season)
            ->where('league_name', $league)
            ->orderBy('position')
            ->get();

        $availableSeasons = GoalkeeperRanking::distinct()->pluck('season')->sort()->reverse();
        $availableLeagues = GoalkeeperRanking::distinct()->pluck('league_name');

        return view('standings.goalkeepers', compact(
            'goalkeeperRankings',
            'season',
            'league',
            'availableSeasons',
            'availableLeagues'
        ));
    }

    public function statisticsHub(Request $request)
    {
        $season = $request->get('season', '2024/25');
        $league = $request->get('league', 'Premier League');

        // Get all statistics data
        $standings = LeagueStanding::where('season', $season)
            ->where('league_name', $league)
            ->orderBy('position')
            ->get();

        $topScorers = TopScorer::where('season', $season)
            ->where('league_name', $league)
            ->orderBy('ranking_position')
            ->take(10)
            ->get();

        $cleanSheets = CleanSheet::where('season', $season)
            ->where('league_name', $league)
            ->orderBy('position')
            ->take(10)
            ->get();

        $goalkeeperRankings = GoalkeeperRanking::where('season', $season)
            ->where('league_name', $league)
            ->orderBy('position')
            ->take(10)
            ->get();

        // Additional statistics
        $totalTeams = $standings->count();
        $totalMatches = FootballMatch::where('season', $season)->count();
        $completedMatches = FootballMatch::where('season', $season)->where('status', 'completed')->count();
        $totalGoals = FootballMatch::where('season', $season)->sum('vipers_score') + FootballMatch::where('season', $season)->sum('opponent_score');
        $avgGoalsPerMatch = $completedMatches > 0 ? round($totalGoals / $completedMatches, 2) : 0;

        // Player statistics from database
        $playerStats = [
            'total_players' => \App\Models\Player::count(),
            'avg_age' => round(\App\Models\Player::avg('age'), 1),
            'top_performer' => \App\Models\Player::orderBy('performance_rating', 'desc')->first(),
            'position_distribution' => \App\Models\Player::select('position')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('position')
                ->get()
        ];

        $availableSeasons = LeagueStanding::distinct()->pluck('season')->sort()->reverse();
        $availableLeagues = LeagueStanding::distinct()->pluck('league_name');

        return view('standings.index', compact(
            'standings',
            'topScorers',
            'cleanSheets',
            'goalkeeperRankings',
            'season',
            'league',
            'availableSeasons',
            'availableLeagues',
            'totalTeams',
            'totalMatches',
            'completedMatches',
            'totalGoals',
            'avgGoalsPerMatch',
            'playerStats'
        ))->with('pageTitle', 'Statistics Hub');
    }
}
