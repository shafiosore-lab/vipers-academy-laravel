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
        return view('website.home.index');
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
                          ->paginate(20);

        // Search in players
        $players = Player::where('name', 'LIKE', "%{$query}%")
                        ->orWhere('position', 'LIKE', "%{$query}%")
                        ->orWhere('nationality', 'LIKE', "%{$query}%")
                        ->paginate(20);

        // Search in news
        $news = News::where('title', 'LIKE', "%{$query}%")
                   ->orWhere('content', 'LIKE', "%{$query}%")
                   ->orWhere('category', 'LIKE', "%{$query}%")
                   ->paginate(20);

        // Search in gallery
        $gallery = Gallery::where('title', 'LIKE', "%{$query}%")
                         ->paginate(20);

        return view('website.search.index', compact('query', 'programs', 'players', 'news', 'gallery'));
    }
}
