<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Player;
use App\Models\News;
use App\Models\Gallery;

class HomeController extends Controller
{
    public function index()
    {
        \Log::info('Player count: ' . \App\Models\Player::count());
        return view('website.home');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return redirect()->back()->with('error', 'Please enter a search term.');
        }

        // Search in programs
        $programs = Program::where('title', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%")
                          ->orWhere('age_group', 'LIKE', "%{$query}%")
                          ->get();

        // Search in players
        $players = Player::where('name', 'LIKE', "%{$query}%")
                        ->orWhere('position', 'LIKE', "%{$query}%")
                        ->orWhere('nationality', 'LIKE', "%{$query}%")
                        ->get();

        // Search in news
        $news = News::where('title', 'LIKE', "%{$query}%")
                   ->orWhere('content', 'LIKE', "%{$query}%")
                   ->orWhere('category', 'LIKE', "%{$query}%")
                   ->get();

        // Search in gallery
        $gallery = Gallery::where('title', 'LIKE', "%{$query}%")
                         ->get();

        return view('website.search', compact('query', 'programs', 'players', 'news', 'gallery'));
    }
}
