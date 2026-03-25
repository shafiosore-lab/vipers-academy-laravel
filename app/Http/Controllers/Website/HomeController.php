<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Player;
use App\Models\News;
use App\Models\User;
use App\Models\PageContent;

class HomeController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        // If there's an error in session, don't redirect to avoid loops
        if (session('error')) {
            return view('website.home.index');
        }

        // Check if user is authenticated and redirect to appropriate dashboard
        // Use centralized RoleHierarchyService for deterministic dashboard routing
        if (auth()->check()) {
            try {
                $user = auth()->user();
                $hierarchyService = new \App\Services\RoleHierarchyService();
                $dashboardRoute = $hierarchyService->getDashboardRouteForUser($user);
                return redirect()->route($dashboardRoute);
            } catch (\Exception $e) {
                // Continue to home page if hierarchy service fails
            }
        }

        // Fetch active partners for the home page
        try {
            $partners = User::where('user_type', 'partner')
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            $partners = collect([]);
        }

        // Fetch dynamic page content
        $pageContent = [];
        try {
            $sections = ['hero', 'what_we_do', 'features', 'stories', 'programs'];
            foreach ($sections as $section) {
                $pageContent[$section] = PageContent::getSection('home', $section);
            }
        } catch (\Exception $e) {
            $pageContent = [];
        }

        // For guests or unrecognized user types, show the home page
        return view('website.home.index', compact('partners', 'pageContent'));
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

        return view('website.search.index', compact('query', 'programs', 'players', 'news'));
    }
}
