<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Program;
use App\Models\GameStatistic;
use App\Models\Order;

class PlayerPortalController extends Controller
{

    // ========================================
    // 1. DASHBOARD (Home/Overview)
    // ========================================
    public function dashboard()
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return redirect('/login');
            }

            $player = Player::with(['gameStatistics', 'program'])->where('email', $user->email)->first();

            // Get recent achievements, stats, etc. (to be enhanced in Phase 2)
            $recentActivity = $player ? ($player->gameStatistics()->latest()->take(5)->get() ?? collect()) : collect();
            $quickStats = $this->getQuickStats($player ?? null);
            $recentOrders = Order::where('user_id', $user->id)->latest()->take(3)->get();
            $upcomingSessions = []; // To be implemented in Phase 2
            $announcements = []; // To be implemented in Phase 2

            return view('player.portal.dashboard', compact(
                'player',
                'quickStats',
                'recentActivity',
                'recentOrders',
                'upcomingSessions',
                'announcements'
            ));
        } catch (\Exception $e) {
            \Log::error('Player Portal Dashboard Error: ' . $e->getMessage());
            return view('player.portal.dashboard', [
                'player' => null,
                'quickStats' => ['training_sessions' => 0, 'goals_scored' => 0, 'minutes_played' => 0, 'programs_enrolled' => 0],
                'recentActivity' => collect(),
                'upcomingSessions' => [],
                'announcements' => []
            ]);
        }
    }

    // ========================================
    // 2. MY PROGRAMS
    // ========================================
    public function programs()
    {
        $user = auth()->user();
        $player = Player::with('program')->where('email', $user->email)->first();

        $activePrograms = $player->program ? collect([$player->program]) : collect(); // Current program
        $availablePrograms = Program::where('status', 'active')->get(); // All available programs
        $completedPrograms = collect(); // To be implemented with enrollment history

        return view('player.portal.programs', compact(
            'activePrograms',
            'availablePrograms',
            'completedPrograms'
        ));
    }

    // ========================================
    // 3. TRAINING & PROGRESS
    // ========================================
    public function training()
    {
        $user = auth()->user();
        $player = Player::with('gameStatistics')->where('email', $user->email)->first();

        $performanceData = $this->getPerformanceData($player);
        $developmentGoals = []; // To be implemented in Phase 3
        $achievements = []; // To be implemented in Phase 3

        return view('player.portal.training', compact(
            'performanceData',
            'developmentGoals',
            'achievements'
        ));
    }

    // ========================================
    // 4. SCHEDULE & ATTENDANCE
    // ========================================
    public function schedule()
    {
        $user = auth()->user();
        $player = Player::where('email', $user->email)->first();

        $calendarEvents = []; // To be implemented with calendar integration
        $attendanceData = []; // To be implemented with session tracking

        return view('player.portal.schedule', compact(
            'calendarEvents',
            'attendanceData'
        ));
    }

    // ========================================
    // 5. RESOURCES & LEARNING
    // ========================================
    public function resources()
    {
        $user = auth()->user();
        $player = Player::with('program')->where('email', $user->email)->first();

        $trainingMaterials = []; // To be implemented with content management
        $libraryItems = []; // To be implemented with resource library
        $tutorials = []; // To be implemented with video content

        return view('player.portal.resources', compact(
            'trainingMaterials',
            'libraryItems',
            'tutorials'
        ));
    }

    // ========================================
    // 6. COMMUNICATION
    // ========================================
    public function communication()
    {
        $user = auth()->user();
        $player = Player::where('email', $user->email)->first();

        $messages = []; // To be implemented with messaging system
        $notifications = []; // To be implemented with notification center
        $announcements = []; // To be implemented with announcement system

        return view('player.portal.communication', compact(
            'messages',
            'notifications',
            'announcements'
        ));
    }

    // ========================================
    // 6.5. ORDERS & PURCHASE HISTORY
    // ========================================
    public function orders()
    {
        $user = auth()->user();

        $orders = Order::where('user_id', $user->id)
            ->with(['user'])
            ->latest()
            ->paginate(10);

        $orderStats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'total_spent' => Order::where('user_id', $user->id)->where('payment_status', 'paid')->sum('total_amount'),
            'pending_orders' => Order::where('user_id', $user->id)->where('order_status', '!=', 'delivered')->count(),
            'delivered_orders' => Order::where('user_id', $user->id)->where('order_status', 'delivered')->count(),
        ];

        return view('player.portal.orders', compact(
            'orders',
            'orderStats'
        ));
    }

    // ========================================
    // 7. PROFILE & SETTINGS
    // ========================================
    public function profile()
    {
        $user = auth()->user();
        $player = Player::where('email', $user->email)->first();

        $documents = []; // To be implemented with document management
        $settings = []; // To be implemented with settings management

        return view('player.portal.profile', compact(
            'user',
            'player',
            'documents',
            'settings'
        ));
    }

    // ========================================
    // 8. SUPPORT & HELP
    // ========================================
    public function support()
    {
        $faqs = []; // To be implemented with FAQ system
        $tickets = []; // To be implemented with support ticketing
        $resources = []; // To be implemented with help resources

        return view('player.portal.support', compact(
            'faqs',
            'tickets',
            'resources'
        ));
    }

    // ========================================
    // HELPER METHODS
    // ========================================
    private function getQuickStats(?Player $player)
    {
        if (!$player) {
            return [
                'training_sessions' => 0,
                'goals_scored' => 0,
                'minutes_played' => 0,
                'programs_enrolled' => 0,
            ];
        }

        return [
            'training_sessions' => $player->gameStatistics ? $player->gameStatistics->count() : 0,
            'goals_scored' => $player->gameStatistics ? $player->gameStatistics->sum('goals_scored') : 0,
            'minutes_played' => $player->gameStatistics ? $player->gameStatistics->sum('minutes_played') : 0,
            'programs_enrolled' => $player->program ? 1 : 0,
        ];
    }

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
