<?php

namespace App\Http\Controllers\Website;

use App\Models\FootballMatch;
use Illuminate\Http\Request;

class MatchCenterController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        // Get all matches for the comprehensive table (ordered by date)
        $matches = FootballMatch::orderBy('match_date', 'desc')
            ->paginate(15);

        // Get upcoming friendlies (for backward compatibility)
        $friendlies = FootballMatch::friendlies()
            ->upcoming()
            ->orderBy('match_date', 'asc')
            ->take(6)
            ->get();

        // Get past matches (for backward compatibility)
        $pastMatches = FootballMatch::completed()
            ->orderBy('match_date', 'desc')
            ->take(6)
            ->get();

        // Get planned tournaments (for backward compatibility)
        $tournaments = FootballMatch::tournaments()
            ->planned()
            ->orderBy('match_date', 'asc')
            ->take(6)
            ->get();

        // Get highlights/videos (for now, we'll use matches with highlights_link)
        $highlights = FootballMatch::whereNotNull('highlights_link')
            ->orderBy('match_date', 'desc')
            ->take(5)
            ->get();

        // Get next upcoming match for the ticker
        $nextMatch = FootballMatch::upcoming()
            ->orderBy('match_date', 'asc')
            ->first();

        // Calculate statistics
        $stats = [
            'total_matches' => FootballMatch::count(),
            'completed_matches' => FootballMatch::completed()->count(),
            'upcoming_matches' => FootballMatch::upcoming()->count(),
            'tournament_matches' => FootballMatch::tournaments()->count(),
            'friendly_matches' => FootballMatch::friendlies()->count(),
            'victories' => FootballMatch::completed()
                ->whereColumn('vipers_score', '>', 'opponent_score')
                ->count(),
            'goals_scored' => FootballMatch::completed()->sum('vipers_score'),
        ];

        return view('website.matches.index', compact(
            'matches',
            'friendlies',
            'pastMatches',
            'tournaments',
            'highlights',
            'nextMatch',
            'stats'
        ));
    }

    public function show($id)
    {
        $match = FootballMatch::findOrFail($id);

        // Get related matches (same type)
        $relatedMatches = FootballMatch::where('type', $match->type)
            ->where('id', '!=', $match->id)
            ->orderBy('match_date', 'desc')
            ->take(4)
            ->get();

        return view('website.matches.show', compact('match', 'relatedMatches'));
    }
}
