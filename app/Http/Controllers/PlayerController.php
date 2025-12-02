<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $query = Player::query();

        // Filter by position
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        // Filter by group/age
        if ($request->filled('group')) {
            switch ($request->group) {
                case 'u10':
                    $query->where('age', '<=', 10);
                    break;
                case 'u13':
                    $query->whereBetween('age', [11, 13]);
                    break;
                case 'u15':
                    $query->whereBetween('age', [14, 15]);
                    break;
                case 'u17':
                    $query->whereBetween('age', [16, 17]);
                    break;
                case 'senior':
                    $query->where('age', '>=', 18);
                    break;
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'featured':
                    $query->where('performance_rating', '>=', 9.0);
                    break;
                case 'new':
                    $query->where('registration_status', 'Active');
                    break;
                case 'academy':
                    $query->where('current_level', 'like', '%academy%');
                    break;
                case 'injured':
                    // For now, just show all - injury status not implemented
                    break;
                case 'transfer':
                    $query->where('transfer_status', 'Available');
                    break;
            }
        }

        $players = $query->paginate(24); // 24 players per page

        // Get random players for mega menu decorations
        $randomPlayers = Player::inRandomOrder()->take(4)->get();
        $decorativePlayers = [
            'positions' => [
                'above' => Player::where('position', 'forward')->inRandomOrder()->first() ?? Player::inRandomOrder()->first(),
                'below' => Player::where('position', 'midfielder')->inRandomOrder()->first() ?? Player::inRandomOrder()->first(),
            ],
            'teams' => [
                'above' => Player::whereBetween('age', [16, 17])->inRandomOrder()->first() ?? Player::inRandomOrder()->first(),
                'below' => Player::whereBetween('age', [14, 15])->inRandomOrder()->first() ?? Player::inRandomOrder()->first(),
            ],
            'highlights' => [
                'above' => Player::where('performance_rating', '>=', 9.0)->inRandomOrder()->first() ?? Player::inRandomOrder()->first(),
                'below' => Player::where('registration_status', 'Active')->inRandomOrder()->first() ?? Player::inRandomOrder()->first(),
            ]
        ];

        return view('website.players', compact('players', 'randomPlayers', 'decorativePlayers'));
    }

    public function elite(Request $request)
    {
        // Elite players: high performance rating, senior team, or featured status
        $query = Player::query();

        // Elite criteria: high performance rating (>= 8.5) OR senior team OR featured
        $query->where(function ($q) {
            $q->where('performance_rating', '>=', 8.5)
              ->orWhere('age', '>=', 18)
              ->orWhere('current_level', 'like', '%senior%')
              ->orWhere('current_level', 'like', '%professional%');
        });

        // Position filter
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        // Category filter
        if ($request->filled('category')) {
            switch ($request->category) {
                case 'rising':
                    $query->where('registration_status', 'Active')
                          ->where('age', '<=', 21);
                    break;
                case 'legends':
                    $query->where('age', '>=', 25)
                          ->where('performance_rating', '>=', 9.0);
                    break;
                case 'youth':
                    $query->whereBetween('age', [16, 20]);
                    break;
                case 'free':
                    $query->where('transfer_status', 'Available');
                    break;
            }
        }

        // Sort options
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'rating':
                    $query->orderBy('performance_rating', 'desc');
                    break;
                case 'goals':
                    $query->orderBy('goals', 'desc');
                    break;
                case 'assists':
                    $query->orderBy('assists', 'desc');
                    break;
                default:
                    $query->orderBy('performance_rating', 'desc');
            }
        } else {
            $query->orderBy('performance_rating', 'desc');
        }

        $players = $query->paginate(24); // 24 players per page

        // Get random elite players for decorations
        $randomPlayers = Player::where('performance_rating', '>=', 8.0)
            ->inRandomOrder()
            ->take(4)
            ->get();

        $decorativePlayers = [
            'positions' => [
                'above' => Player::where('performance_rating', '>=', 8.5)
                    ->where('position', 'forward')
                    ->inRandomOrder()
                    ->first() ?? Player::where('performance_rating', '>=', 8.0)->inRandomOrder()->first(),
                'below' => Player::where('performance_rating', '>=', 8.5)
                    ->where('position', 'midfielder')
                    ->inRandomOrder()
                    ->first() ?? Player::where('performance_rating', '>=', 8.0)->inRandomOrder()->first(),
            ],
            'teams' => [
                'above' => Player::where('performance_rating', '>=', 8.5)
                    ->where('age', '>=', 18)
                    ->inRandomOrder()
                    ->first() ?? Player::where('performance_rating', '>=', 8.0)->inRandomOrder()->first(),
                'below' => Player::where('performance_rating', '>=', 8.5)
                    ->whereBetween('age', [16, 17])
                    ->inRandomOrder()
                    ->first() ?? Player::where('performance_rating', '>=', 8.0)->inRandomOrder()->first(),
            ],
            'highlights' => [
                'above' => Player::where('performance_rating', '>=', 9.0)
                    ->inRandomOrder()
                    ->first() ?? Player::where('performance_rating', '>=', 8.5)->inRandomOrder()->first(),
                'below' => Player::where('performance_rating', '>=', 8.5)
                    ->where('registration_status', 'Active')
                    ->inRandomOrder()
                    ->first() ?? Player::where('performance_rating', '>=', 8.0)->inRandomOrder()->first(),
            ]
        ];

        return view('website.players', compact('players', 'randomPlayers', 'decorativePlayers'));
    }

    public function show($id)
    {
        $player = Player::findOrFail($id);

        // Get all players for dropdown navigation
        $players = Player::orderBy('name')->get();

        // Get related players by position
        $relatedPlayers = Player::where('id', '!=', $player->id)
            ->where('position', $player->position)
            ->take(4)
            ->get();

        // Get previous and next players for navigation
        $previousPlayer = Player::where('id', '<', $player->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextPlayer = Player::where('id', '>', $player->id)
            ->orderBy('id', 'asc')
            ->first();

        return view('website.player_detail', compact('player', 'players', 'relatedPlayers', 'previousPlayer', 'nextPlayer'));
    }

    public function rankings(Request $request)
    {
        $query = Player::query();

        // Filter by position if specified
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        // Order by performance rating descending
        $players = $query->orderBy('performance_rating', 'desc')
                         ->orderBy('goals', 'desc')
                         ->orderBy('assists', 'desc')
                         ->get();

        // Get top performers for highlights
        $topPerformers = Player::orderBy('performance_rating', 'desc')
                              ->take(5)
                              ->get();

        // Get statistics
        $totalPlayers = Player::count();
        $avgRating = Player::avg('performance_rating');
        $topRating = Player::max('performance_rating');

        // Position distribution
        $positionStats = Player::select('position')
                              ->selectRaw('COUNT(*) as count, AVG(performance_rating) as avg_rating')
                              ->groupBy('position')
                              ->get();

        return view('website.players', compact(
            'players',
            'topPerformers',
            'totalPlayers',
            'avgRating',
            'topRating',
            'positionStats'
        ))->with('pageTitle', 'Player Rankings');
    }
}
