<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Program;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ManagerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:team-manager']);
    }

    public function index()
    {
        $user = auth()->user();

        // Get player statistics
        $players = Player::count();
        $activePlayers = Player::where('registration_status', 'Active')->count();
        $pendingRegistrations = Player::where('registration_status', 'Pending')->count();

        // Get program statistics
        $programs = Program::count();
        $activePrograms = Program::where('status', 'active')->count();

        // Get order statistics
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $recentOrders = Order::latest()->take(5)->get();

        // Get payment overview
        $pendingPayments = Payment::pending()->sum('amount');
        $completedPayments = Payment::completed()->sum('amount');

        // Get players by program (only if program_id column exists)
        $playersByProgram = collect();
        if (Schema::hasColumn('players', 'program_id')) {
            $playersByProgram = Player::with('program')
                ->select('program_id')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('program_id')
                ->get();
        }

        return view('staff.manager.dashboard', compact(
            'user',
            'players',
            'activePlayers',
            'pendingRegistrations',
            'programs',
            'activePrograms',
            'pendingOrders',
            'recentOrders',
            'pendingPayments',
            'completedPayments',
            'playersByProgram'
        ));
    }

    public function registrations()
    {
        $registrations = Player::orderBy('created_at', 'desc')->paginate(15);

        return view('staff.manager.registrations', compact('registrations'));
    }

    public function logistics()
    {
        $upcomingPrograms = Program::where('start_date', '>', now())
            ->orderBy('start_date')
            ->get();

        return view('staff.manager.logistics', compact('upcomingPrograms'));
    }
}
