<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\WebsitePlayer;
use App\Models\Program;
use App\Models\Blog;
use App\Models\User;
use App\Models\Payment;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\TournamentStanding;
use App\Models\Event;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class GamesuiteController extends Controller
{
    /**
     * Display the GameSuite landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch key metrics from the database to display on GameSuite page
        // These represent the actual data from the academy管理系统

        // Player counts
        $mainPlayersCount = Player::count();
        $websitePlayersCount = WebsitePlayer::count();
        $totalPlayers = $mainPlayersCount + $websitePlayersCount;

        // Approved players
        $approvedPlayers = Player::where('registration_status', 'Approved')->count();

        // New players this month
        $newPlayersThisMonth = Player::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();

        // Programs
        $totalPrograms = Program::count();
        $activePrograms = Program::where('status', 'active')->count();

        // News/Blogs
        $totalNews = Blog::count();
        $publishedNews = Blog::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();

        // Partners
        $totalPartners = User::where('user_type', 'partner')->count();
        $activePartners = User::where('user_type', 'partner')->where('approval_status', 'approved')->count();

        // Financial - total revenue from completed payments
        $totalRevenue = Payment::completed()->sum('amount');

        // Performance metrics
        $totalGoals = Player::sum('goals_scored') ?: 0;
        $totalAssists = Player::sum('assists') ?: 0;
        $totalMatches = Player::sum('matches_played') ?: 0;
        $highPerformers = Player::where('performance_rating', '>=', 8.0)->count();

        // Years since founding (2017)
        $yearsActive = now()->year - 2017;

        // Calculate growth metrics
        $playersLastMonth = Player::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $playerGrowth = $playersLastMonth > 0 ? round((($newPlayersThisMonth - $playersLastMonth) / $playersLastMonth) * 100, 1) : 0;

        // ========== ENHANCED DASHBOARD DATA ==========
        // Wrap in try-catch to prevent page crashes when tables are empty

        try {
            // Recent Matches - Use scheduled_date instead of match_date
            $recentMatches = TournamentMatch::with(['homeTeam', 'awayTeam', 'tournament'])
                ->whereNotNull('scheduled_date')
                ->orderBy('scheduled_date', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $recentMatches = collect();
        }

        try {
            // Active Tournaments
            $activeTournaments = Tournament::where('status', 'active')
                ->orWhere('status', 'ongoing')
                ->orderBy('start_date', 'desc')
                ->limit(3)
                ->get();
        } catch (\Exception $e) {
            $activeTournaments = collect();
        }

        try {
            // Tournament Standings (top 5)
            $standings = TournamentStanding::with('team')
                ->orderBy('points', 'desc')
                ->orderBy('goal_difference', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $standings = collect();
        }

        try {
            // Top Scorers
            $topScorers = Player::where('goals_scored', '>', 0)
                ->orderBy('goals_scored', 'desc')
                ->limit(5)
                ->get(['id', 'first_name', 'last_name', 'goals_scored', 'position', 'profile_image']);
        } catch (\Exception $e) {
            $topScorers = collect();
        }

        try {
            // Top Assists
            $topAssists = Player::where('assists', '>', 0)
                ->orderBy('assists', 'desc')
                ->limit(5)
                ->get(['id', 'first_name', 'last_name', 'assists', 'position', 'profile_image']);
        } catch (\Exception $e) {
            $topAssists = collect();
        }

        try {
            // Recent Registrations
            $recentRegistrations = Player::orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id', 'first_name', 'last_name', 'created_at', 'registration_status', 'profile_image']);
        } catch (\Exception $e) {
            $recentRegistrations = collect();
        }

        try {
            // Recent Payments
            $recentPayments = Payment::with('player')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $recentPayments = collect();
        }

        try {
            // Upcoming Events
            $upcomingEvents = Event::where('event_date', '>', now())
                ->orderBy('event_date', 'asc')
                ->limit(3)
                ->get();
        } catch (\Exception $e) {
            $upcomingEvents = collect();
        }

        // Calculate completion percentages for progress bars
        $playerCompletion = $totalPlayers > 0 ? round(($approvedPlayers / $totalPlayers) * 100) : 0;
        $programCompletion = $totalPrograms > 0 ? round(($activePrograms / $totalPrograms) * 100) : 0;
        $partnerCompletion = $totalPartners > 0 ? round(($activePartners / $totalPartners) * 100) : 0;

        return view('website.gamesuite.index', compact(
            // Original data
            'totalPlayers',
            'approvedPlayers',
            'newPlayersThisMonth',
            'playerGrowth',
            'totalPrograms',
            'activePrograms',
            'totalNews',
            'publishedNews',
            'totalPartners',
            'activePartners',
            'totalRevenue',
            'totalGoals',
            'totalAssists',
            'totalMatches',
            'highPerformers',
            'yearsActive',
            // Enhanced dashboard data
            'recentMatches',
            'activeTournaments',
            'standings',
            'topScorers',
            'topAssists',
            'recentRegistrations',
            'recentPayments',
            'upcomingEvents',
            'playerCompletion',
            'programCompletion',
            'partnerCompletion'
        ));
    }
}
