<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\WebsitePlayer;
use App\Models\Program;
use App\Models\GameStatistic;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PlayerPortalController extends Controller
{
    /**
     * Dashboard - Overview page
     */
    public function dashboard()
    {
        try {
            $user = auth()->user();
            $player = $user->player()->with(['gameStatistics', 'program'])->first();
            $websitePlayer = WebsitePlayer::first();

            $quickStats = $this->getQuickStats($player, $websitePlayer);
            $recentActivity = $player ? $player->gameStatistics()->latest()->take(5)->get() : collect();
            $recentOrders = Order::where('user_id', $user->id)->latest()->take(3)->get();

            // Placeholder data for future features
            $upcomingSessions = [];
            $announcements = [];

            return view('player.portal.dashboard', compact(
                'player',
                'websitePlayer',
                'quickStats',
                'recentActivity',
                'recentOrders',
                'upcomingSessions',
                'announcements'
            ));
        } catch (\Exception $e) {
            Log::error('Player Portal Dashboard Error: ' . $e->getMessage());
            return redirect('/')->with('error', 'An error occurred while loading your dashboard.');
        }
    }

    /**
     * My Programs - View enrolled and available programs
     */
    public function programs()
    {
        $accessCheck = $this->checkPlayerAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $player = $accessCheck['player'];

        $activePrograms = $player->program ? collect([$player->program]) : collect();
        $availablePrograms = Program::where('status', 'active')->get();
        $completedPrograms = collect();

        return view('player.portal.programs', compact(
            'activePrograms',
            'availablePrograms',
            'completedPrograms'
        ));
    }

    /**
     * Training & Progress - View performance data
     */
    public function training()
    {
        $accessCheck = $this->checkPlayerAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $player = $accessCheck['player'];

        $performanceData = $this->getPerformanceData($player);
        $developmentGoals = [];
        $achievements = [];

        return view('player.portal.training', compact(
            'performanceData',
            'developmentGoals',
            'achievements'
        ));
    }

    /**
     * Schedule & Attendance - View training schedule
     */
    public function schedule()
    {
        $accessCheck = $this->checkPlayerAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $calendarEvents = [];
        $attendanceData = [];

        return view('player.portal.schedule', compact(
            'calendarEvents',
            'attendanceData'
        ));
    }

    /**
     * Resources & Learning - Training materials
     */
    public function resources()
    {
        $accessCheck = $this->checkPlayerAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $trainingMaterials = [];
        $libraryItems = [];
        $tutorials = [];

        return view('player.portal.resources', compact(
            'trainingMaterials',
            'libraryItems',
            'tutorials'
        ));
    }

    /**
     * Communication - Messages and notifications
     */
    public function communication()
    {
        $accessCheck = $this->checkPlayerAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $messages = [];
        $notifications = [];
        $announcements = [];

        return view('player.portal.communication', compact(
            'messages',
            'notifications',
            'announcements'
        ));
    }

    /**
     * Orders & Purchase History
     */
    public function orders()
    {
        $accessCheck = $this->checkPlayerAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $user = $accessCheck['user'];

        $orders = Order::where('user_id', $user->id)
            ->with(['user'])
            ->latest()
            ->paginate(10);

        $orderStats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'total_spent' => Order::where('user_id', $user->id)
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
            'pending_orders' => Order::where('user_id', $user->id)
                ->where('order_status', '!=', 'delivered')
                ->count(),
            'delivered_orders' => Order::where('user_id', $user->id)
                ->where('order_status', 'delivered')
                ->count(),
        ];

        return view('player.portal.orders', compact('orders', 'orderStats'));
    }

    /**
     * Profile & Settings
     */
    public function profile()
    {
        $accessCheck = $this->checkPlayerAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $user = $accessCheck['user'];
        $player = $accessCheck['player'];

        $documents = [];
        $settings = [];

        return view('player.portal.profile', compact('user', 'player', 'documents', 'settings'));
    }

    /**
     * Support & Help
     */
    public function support()
    {
        $accessCheck = $this->checkPlayerAccess();
        if ($accessCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $accessCheck;
        }

        $faqs = [];
        $tickets = [];
        $resources = [];

        return view('player.portal.support', compact('faqs', 'tickets', 'resources'));
    }

    /**
     * Check if user has player access
     *
     * @return array|\Illuminate\Http\RedirectResponse
     */
    private function checkPlayerAccess()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login')->with('error', 'Please log in to access this page.');
        }

        if (!$user->isPlayer()) {
            return redirect('/')->with('error', 'Access denied. Player account required.');
        }

        $player = $user->player;

        if (!$player) {
            return redirect('/')->with('error', 'Player profile not found. Please contact administration.');
        }

        return ['user' => $user, 'player' => $player];
    }

    /**
     * Get quick statistics for dashboard
     *
     * @param Player|null $player
     * @param WebsitePlayer|null $websitePlayer
     * @return array
     */
    private function getQuickStats(?Player $player, ?WebsitePlayer $websitePlayer = null)
    {
        if (!$player) {
            return [
                'training_sessions' => 0,
                'goals_scored' => 0,
                'assists' => 0,
                'minutes_played' => 0,
                'appearances' => 0,
                'programs_enrolled' => 0,
            ];
        }

        $gameStatsGoals = $player->gameStatistics->sum('goals_scored') ?? 0;
        $gameStatsAssists = $player->gameStatistics->sum('assists') ?? 0;
        $gameStatsMinutes = $player->gameStatistics->sum('minutes_played') ?? 0;
        $gameStatsCount = $player->gameStatistics->count() ?? 0;

        return [
            'training_sessions' => $gameStatsCount,
            'goals_scored' => $websitePlayer ? $websitePlayer->goals : $gameStatsGoals,
            'assists' => $websitePlayer ? $websitePlayer->assists : $gameStatsAssists,
            'minutes_played' => $gameStatsMinutes,
            'appearances' => $websitePlayer ? $websitePlayer->appearances : $gameStatsCount,
            'programs_enrolled' => $player->program ? 1 : 0,
        ];
    }

    /**
     * Get performance data for training page
     *
     * @param Player $player
     * @return array
     */
    private function getPerformanceData(Player $player)
    {
        return [
            'skills_assessment' => [],
            'progress_graphs' => [],
            'benchmark_comparisons' => [],
            'predictive_insights' => [],
        ];
    }
}
