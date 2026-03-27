<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\TournamentStanding;
use App\Models\Player;
use App\Models\PlayerGameStats;
use App\Models\TournamentMatch;
use App\Models\TournamentTeam;
use App\Models\TournamentPool;
use App\Models\PlayerSuspension;
use App\Models\PlayerCard;
use App\Models\Attendance;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TournamentStatisticsController extends Controller
{
    /**
     * Display the main statistics dashboard
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\View\View
     */
    public function index(Tournament $tournament)
    {
        $this->authorize('view', $tournament);

        // Get tournament summary
        $summary = $this->getTournamentSummary($tournament);

        // Get top scorers
        $topScorers = $this->getTopScorers($tournament);

        // Get team cards
        $teamCards = $this->getTeamCards($tournament);

        // Get groups
        $groups = $this->getGroups($tournament);

        return view('admin.tournaments.statistics.index', compact(
            'tournament', 'summary', 'topScorers', 'teamCards', 'groups'
        ));
    }

    /**
     * Display top scorers statistics
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\View\View
     */
    public function topScorers(Tournament $tournament)
    {
        $this->authorize('view', $tournament);

        $summary = $this->getTournamentSummary($tournament);
        $topScorers = $this->getTopScorers($tournament);
        $topAssists = $this->getTopAssists($tournament);
        $topGoalContributions = $this->getTopGoalContributions($tournament);

        return view('admin.tournaments.statistics.top-scorers', compact(
            'tournament', 'summary', 'topScorers', 'topAssists', 'topGoalContributions'
        ));
    }

    /**
     * Display discipline statistics
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\View\View
     */
    public function discipline(Tournament $tournament)
    {
        $this->authorize('view', $tournament);

        $summary = $this->getTournamentSummary($tournament);
        $teamCards = $this->getTeamCards($tournament);
        $playerCards = $this->getPlayerCards($tournament);
        $suspensions = $this->getSuspensions($tournament);

        return view('admin.tournaments.statistics.discipline', compact(
            'tournament', 'summary', 'teamCards', 'playerCards', 'suspensions'
        ));
    }

    /**
     * Display groups/pools statistics
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\View\View
     */
    public function groups(Tournament $tournament)
    {
        $this->authorize('view', $tournament);

        $groups = $this->getGroups($tournament);
        $groupMatches = $this->getGroupMatches($tournament);
        $formatInfo = $this->getFormatInfo($tournament);

        return view('admin.tournaments.statistics.groups', compact(
            'tournament', 'groups', 'groupMatches', 'formatInfo'
        ));
    }

    /**
     * Display rankings statistics
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\View\View
     */
    public function rankings(Tournament $tournament)
    {
        $this->authorize('view', $tournament);

        $rankings = $this->getRankings($tournament);
        $eloRankings = $this->getEloRankings($tournament);
        $formTable = $this->getFormTable($tournament);
        $formatInfo = $this->getFormatInfo($tournament);

        return view('admin.tournaments.statistics.rankings', compact(
            'tournament', 'rankings', 'eloRankings', 'formTable', 'formatInfo'
        ));
    }

    /**
     * Display tournament summary
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\View\View
     */
    public function summary(Tournament $tournament)
    {
        $this->authorize('view', $tournament);

        $summary = $this->getTournamentSummary($tournament);
        $topScorers = $this->getTopScorers($tournament);
        $topAssists = $this->getTopAssists($tournament);
        $cleanSheets = $this->getCleanSheets($tournament);
        $rankings = $this->getRankings($tournament);
        $formatInfo = $this->getFormatInfo($tournament);

        return view('admin.tournaments.statistics.summary', compact(
            'tournament', 'summary', 'topScorers', 'topAssists', 'cleanSheets', 'rankings', 'formatInfo'
        ));
    }

    /**
     * Get live statistics for real-time updates
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Http\JsonResponse
     */
    public function liveApi(Tournament $tournament)
    {
        $this->authorize('view', $tournament);

        $summary = $this->getTournamentSummary($tournament);
        $topScorers = $this->getTopScorers($tournament);
        $teamCards = $this->getTeamCards($tournament);
        $rankings = $this->getRankings($tournament);

        return response()->json([
            'summary' => $summary,
            'top_scorers' => $topScorers->take(5),
            'team_cards' => $teamCards->take(5),
            'rankings' => $rankings->take(5),
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Live API endpoint for real-time statistics updates
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\JsonResponse
     */
    public function live(Tournament $tournament)
    {
        // Check if user has permission to view tournament statistics
        if (!auth()->user()->can('view statistics', $tournament)) {
            abort(403, 'Unauthorized action.');
        }

        // Get real-time statistics data
        $data = $this->getTournamentStatistics($tournament);

        return response()->json([
            'success' => true,
            'data' => $data,
            'timestamp' => now()->toIso8601String(),
            'tournament' => [
                'id' => $tournament->id,
                'name' => $tournament->name,
                'status' => $tournament->status,
                'current_matchday' => $tournament->current_matchday,
            ]
        ]);
    }

    /**
     * Export tournament statistics
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function export(Tournament $tournament)
    {
        // Check if user has permission to view tournament statistics
        if (!auth()->user()->can('view statistics', $tournament)) {
            abort(403, 'Unauthorized action.');
        }

        // Get tournament statistics data
        $data = $this->getTournamentStatistics($tournament);

        // Create export filename
        $filename = 'tournament_statistics_' . $tournament->id . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        // Return Excel export
        return Excel::download(new TournamentStatisticsExport($data, $tournament), $filename);
    }

    /**
     * Get tournament summary statistics
     *
     * @param  Tournament  $tournament
     * @return array
     */
    private function getTournamentSummary(Tournament $tournament)
    {
        $totalTeams = $tournament->teams()->count();
        $registeredTeams = $tournament->teams()->where('status', 'registered')->count();

        $matches = TournamentMatch::where('tournament_id', $tournament->id);
        $totalMatches = $matches->count();
        $completedMatches = $matches->where('status', 'completed')->count();

        $totalGoals = $matches->where('status', 'completed')
            ->sum(DB::raw('COALESCE(home_score, 0) + COALESCE(away_score, 0)'));

        $avgGoalsPerMatch = $completedMatches > 0 ? round($totalGoals / $completedMatches, 2) : 0;

        // Card statistics
        $totalCards = PlayerCard::whereHas('player.tournamentSquads', function($query) use ($tournament) {
            $query->where('tournament_id', $tournament->id);
        })->count();

        $yellowCards = PlayerCard::where('card_type', 'yellow')->whereHas('player.tournamentSquads', function($query) use ($tournament) {
            $query->where('tournament_id', $tournament->id);
        })->count();

        $redCards = PlayerCard::where('card_type', 'red')->whereHas('player.tournamentSquads', function($query) use ($tournament) {
            $query->where('tournament_id', $tournament->id);
        })->count();

        // Attendance statistics
        $totalAttendance = Attendance::whereHas('match', function($query) use ($tournament) {
            $query->where('tournament_id', $tournament->id);
        })->sum('attendance');

        $avgAttendance = $completedMatches > 0 ? round($totalAttendance / $completedMatches) : 0;

        // Financial statistics
        $totalRevenue = Payment::where('tournament_id', $tournament->id)
            ->where('status', 'completed')
            ->sum('amount');

        return [
            'total_teams' => $totalTeams,
            'registered_teams' => $registeredTeams,
            'total_matches' => $totalMatches,
            'completed_matches' => $completedMatches,
            'total_goals' => $totalGoals,
            'avg_goals_per_match' => $avgGoalsPerMatch,
            'total_cards' => $totalCards,
            'yellow_cards' => $yellowCards,
            'red_cards' => $redCards,
            'total_attendance' => $totalAttendance,
            'avg_attendance' => $avgAttendance,
            'total_revenue' => $totalRevenue,
            'progress_percentage' => $totalMatches > 0 ? round(($completedMatches / $totalMatches) * 100) : 0,
            'current_stage' => $this->getCurrentStage($tournament)
        ];
    }

    /**
     * Get top scorers
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Support\Collection
     */
    private function getTopScorers(Tournament $tournament)
    {
        return Player::whereHas('tournamentSquads', function($query) use ($tournament) {
            $query->where('tournament_id', $tournament->id);
        })
        ->withSum(['gameStats' => function($query) use ($tournament) {
            $query->whereHas('match', function($matchQuery) use ($tournament) {
                $matchQuery->where('tournament_id', $tournament->id);
            });
        }], 'goals')
        ->withSum(['gameStats' => function($query) use ($tournament) {
            $query->whereHas('match', function($matchQuery) use ($tournament) {
                $matchQuery->where('tournament_id', $tournament->id);
            });
        }], 'assists')
        ->withCount(['gameStats' => function($query) use ($tournament) {
            $query->whereHas('match', function($matchQuery) use ($tournament) {
                $matchQuery->where('tournament_id', $tournament->id);
            });
        }])
        ->having('game_stats_sum_goals', '>', 0)
        ->orderByDesc('game_stats_sum_goals')
        ->orderByDesc('game_stats_sum_assists')
        ->limit(20)
        ->get();
    }

    /**
     * Get top assists
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Support\Collection
     */
    private function getTopAssists(Tournament $tournament)
    {
        return Player::whereHas('tournamentSquads', function($query) use ($tournament) {
            $query->where('tournament_id', $tournament->id);
        })
        ->withSum(['gameStats' => function($query) use ($tournament) {
            $query->whereHas('match', function($matchQuery) use ($tournament) {
                $matchQuery->where('tournament_id', $tournament->id);
            });
        }], 'assists')
        ->withSum(['gameStats' => function($query) use ($tournament) {
            $query->whereHas('match', function($matchQuery) use ($tournament) {
                $matchQuery->where('tournament_id', $tournament->id);
            });
        }], 'goals')
        ->withCount(['gameStats' => function($query) use ($tournament) {
            $query->whereHas('match', function($matchQuery) use ($tournament) {
                $matchQuery->where('tournament_id', $tournament->id);
            });
        }])
        ->having('game_stats_sum_assists', '>', 0)
        ->orderByDesc('game_stats_sum_assists')
        ->orderByDesc('game_stats_sum_goals')
        ->limit(20)
        ->get();
    }

    /**
     * Get top goal contributors (goals + assists)
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Support\Collection
     */
    private function getTopGoalContributions(Tournament $tournament)
    {
        $summary = $this->getTournamentSummary($tournament);

        return Player::whereHas('tournamentSquads', function($query) use ($tournament) {
            $query->where('tournament_id', $tournament->id);
        })
        ->withSum(['gameStats' => function($query) use ($tournament) {
            $query->whereHas('match', function($matchQuery) use ($tournament) {
                $matchQuery->where('tournament_id', $tournament->id);
            });
        }], 'goals')
        ->withSum(['gameStats' => function($query) use ($tournament) {
            $query->whereHas('match', function($matchQuery) use ($tournament) {
                $matchQuery->where('tournament_id', $tournament->id);
            });
        }], 'assists')
        ->withCount(['gameStats' => function($query) use ($tournament) {
            $query->whereHas('match', function($matchQuery) use ($tournament) {
                $matchQuery->where('tournament_id', $tournament->id);
            });
        }])
        ->get()
        ->map(function($player) {
            $player->goals = $player->game_stats_sum_goals ?? 0;
            $player->assists = $player->game_stats_sum_assists ?? 0;
            $player->total_contributions = $player->goals + $player->assists;
            return $player;
        })
        ->filter(function($player) {
            return $player->total_contributions > 0;
        })
        ->sortByDesc('total_contributions')
        ->take(20);
    }

    /**
     * Get team cards statistics
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Support\Collection
     */
    private function getTeamCards(Tournament $tournament)
    {
        return TournamentTeam::where('tournament_id', $tournament->id)
            ->withCount(['players.cards' => function($query) {
                $query->where('card_type', 'yellow');
            }])
            ->withCount(['players.cards' => function($query) {
                $query->where('card_type', 'red');
            }])
            ->withCount(['matches'])
            ->having('players_cards_count', '>', 0)
            ->orderByDesc('players_cards_count')
            ->get();
    }

    /**
     * Get player cards statistics
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Support\Collection
     */
    private function getPlayerCards(Tournament $tournament)
    {
        return Player::whereHas('tournamentSquads', function($query) use ($tournament) {
            $query->where('tournament_id', $tournament->id);
        })
        ->withCount(['cards' => function($query) {
            $query->where('card_type', 'yellow');
        }])
        ->withCount(['cards' => function($query) {
            $query->where('card_type', 'red');
        }])
        ->withCount(['gameStats' => function($query) use ($tournament) {
            $query->whereHas('match', function($matchQuery) use ($tournament) {
                $matchQuery->where('tournament_id', $tournament->id);
            });
        }])
        ->having('cards_count', '>', 0)
        ->orderByDesc('cards_count')
        ->get();
    }

    /**
     * Get suspensions
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Support\Collection
     */
    private function getSuspensions(Tournament $tournament)
    {
        return PlayerSuspension::whereHas('player.tournamentSquads', function($query) use ($tournament) {
            $query->where('tournament_id', $tournament->id);
        })
        ->with('player')
        ->orderByDesc('created_at')
        ->get();
    }

    /**
     * Get groups/pools
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Support\Collection
     */
    private function getGroups(Tournament $tournament)
    {
        return TournamentPool::where('tournament_id', $tournament->id)
            ->with(['teams.standings'])
            ->get();
    }

    /**
     * Get group matches
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Support\Collection
     */
    private function getGroupMatches(Tournament $tournament)
    {
        return TournamentMatch::where('tournament_id', $tournament->id)
            ->with(['homeTeam', 'awayTeam', 'pool', 'venueModel'])
            ->orderBy('kickoff_time')
            ->get();
    }

    /**
     * Get rankings
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Support\Collection
     */
    private function getRankings(Tournament $tournament)
    {
        return TournamentStanding::where('tournament_id', $tournament->id)
            ->with('team')
            ->orderBy('position')
            ->get();
    }

    /**
     * Get ELO rankings
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Support\Collection
     */
    private function getEloRankings(Tournament $tournament)
    {
        return TournamentTeam::where('tournament_id', $tournament->id)
            ->whereNotNull('elo_rating')
            ->orderByDesc('elo_rating')
            ->get();
    }

    /**
     * Get form table (last 5 matches)
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Support\Collection
     */
    private function getFormTable(Tournament $tournament)
    {
        return TournamentStanding::where('tournament_id', $tournament->id)
            ->with('team')
            ->orderByDesc('points')
            ->get();
    }

    /**
     * Get clean sheets
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Support\Collection
     */
    private function getCleanSheets(Tournament $tournament)
    {
        return Player::whereHas('tournamentSquads', function($query) use ($tournament) {
            $query->where('tournament_id', $tournament->id);
        })
        ->where('position', 'goalkeeper')
        ->withCount(['gameStats' => function($query) use ($tournament) {
            $query->whereHas('match', function($matchQuery) use ($tournament) {
                $matchQuery->where('tournament_id', $tournament->id);
            })->where('goals_conceded', 0);
        }])
        ->having('game_stats_count', '>', 0)
        ->orderByDesc('game_stats_count')
        ->get();
    }

    /**
     * Get tournament format information
     *
     * @param  Tournament  $tournament
     * @return array
     */
    private function getFormatInfo(Tournament $tournament)
    {
        $hasGroups = $tournament->pools()->exists();
        $isKnockout = $tournament->format === 'knockout' || $tournament->format === 'knockout_with_third_place';

        return [
            'name' => ucfirst(str_replace('_', ' ', $tournament->format)),
            'description' => $this->getFormatDescription($tournament->format),
            'has_groups' => $hasGroups,
            'is_knockout' => $isKnockout
        ];
    }

    /**
     * Get format description
     *
     * @param  string  $format
     * @return string
     */
    private function getFormatDescription($format)
    {
        $descriptions = [
            'round_robin' => 'All teams play each other once',
            'double_round_robin' => 'All teams play each other twice',
            'group_stage' => 'Teams divided into groups, top teams advance',
            'knockout' => 'Single elimination tournament',
            'knockout_with_third_place' => 'Single elimination with third place playoff',
            'hybrid' => 'Combination of group stage and knockout'
        ];

        return $descriptions[$format] ?? 'Tournament format';
    }

    /**
     * Get current tournament stage
     *
     * @param  Tournament  $tournament
     * @return string
     */
    private function getCurrentStage(Tournament $tournament)
    {
        $completedMatches = TournamentMatch::where('tournament_id', $tournament->id)
            ->where('status', 'completed')
            ->count();

        $totalMatches = TournamentMatch::where('tournament_id', $tournament->id)->count();

        if ($totalMatches === 0) {
            return 'Registration';
        }

        $progress = $totalMatches > 0 ? ($completedMatches / $totalMatches) * 100 : 0;

        if ($progress < 25) {
            return 'Group Stage';
        } elseif ($progress < 75) {
            return 'Knockout Stage';
        } else {
            return 'Final Stage';
        }
    }

    /**
     * Export as PDF
     *
     * @param  Tournament  $tournament
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    private function exportPdf(Tournament $tournament)
    {
        // Implementation would use a PDF library like DomPDF or Snappy
        return response()->download(storage_path('app/public/tournament-summary.pdf'));
    }

    /**
     * Export as Excel
     *
     * @param  Tournament  $tournament
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    private function exportExcel(Tournament $tournament)
    {
        // Implementation would use a library like Maatwebsite/Laravel-Excel
        return response()->download(storage_path('app/public/tournament-summary.xlsx'));
    }

    /**
     * Export as CSV
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    private function exportCsv(Tournament $tournament)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="tournament-summary.csv"',
        ];

        $callback = function() use ($tournament) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Statistic', 'Value']);

            $summary = $this->getTournamentSummary($tournament);

            fputcsv($file, ['Total Teams', $summary['total_teams']]);
            fputcsv($file, ['Completed Matches', $summary['completed_matches']]);
            fputcsv($file, ['Total Goals', $summary['total_goals']]);
            fputcsv($file, ['Total Cards', $summary['total_cards']]);
            fputcsv($file, ['Total Attendance', $summary['total_attendance']]);
            fputcsv($file, ['Total Revenue', $summary['total_revenue']]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
