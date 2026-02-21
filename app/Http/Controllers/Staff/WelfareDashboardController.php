<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Document;
use App\Models\UserDocument;
use Illuminate\Http\Request;

class WelfareDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:safeguarding-officer']);
    }

    public function index()
    {
        $user = auth()->user();

        // Get welfare statistics
        $totalPlayers = Player::count();
        $playersNeedingAttention = Player::where('needs_attention', true)->count();
        $overdueFollowUps = Player::where('last_follow_up', '<', now()->subDays(30))->count();

        // Get documents requiring attention from UserDocument (tracks user-specific document status)
        $pendingDocuments = UserDocument::where('status', 'pending_review')->count();
        $expiringDocuments = UserDocument::whereNotNull('expires_at')
            ->where('expires_at', '<', now()->addDays(30))
            ->where('expires_at', '>', now())
            ->count();

        // Get players by academic performance
        $playersByAcademic = Player::select('academic_performance')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('academic_performance')
            ->get();

        // Get age group distribution
        $playersByAge = Player::select('age_group')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('age_group')
            ->get();

        return view('staff.welfare.dashboard', compact(
            'user',
            'totalPlayers',
            'playersNeedingAttention',
            'overdueFollowUps',
            'pendingDocuments',
            'expiringDocuments',
            'playersByAcademic',
            'playersByAge'
        ));
    }

    public function attentionList()
    {
        $players = Player::where('needs_attention', true)
            ->orWhere('last_follow_up', '<', now()->subDays(30))
            ->paginate(15);

        return view('staff.welfare.attention-list', compact('players'));
    }

    public function compliance()
    {
        $players = Player::with(['user.userDocuments', 'guardian'])
            ->paginate(15);

        return view('staff.welfare.compliance', compact('players'));
    }
}
