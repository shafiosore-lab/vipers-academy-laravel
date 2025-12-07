<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()->get();

        // Calculate statistics
        $totalArticles = $news->count();
        $monthlyArticles = News::where('created_at', '>=', now()->startOfMonth())->count();
        $mostReadCategoryResult = News::select('category')
            ->groupBy('category')
            ->orderByRaw('COUNT(*) DESC')
            ->first();

        $mostReadCategory = $mostReadCategoryResult ? $mostReadCategoryResult->category : 'General';

        $totalCategories = News::distinct('category')->count('category');

        // Get categories
        $categories = News::select('category')
            ->distinct()
            ->get()
            ->map(function($item) {
                return (object)['category' => $item->category];
            });

        // Featured news (latest 3)
        $featuredNews = $news->take(3);

        // Main article (latest)
        $mainArticle = $news->first();

        // Trending news (next 5 after main)
        $trendingNews = $news->skip(1)->take(5);

        // Articles for grid (remaining)
        $articles = $news->skip(6);

        // Check if there are more articles
        $hasMoreArticles = $articles->count() > 12; // Assuming pagination

        return view('website.news.index', compact(
            'news',
            'totalArticles',
            'monthlyArticles',
            'mostReadCategory',
            'totalCategories',
            'categories',
            'featuredNews',
            'mainArticle',
            'trendingNews',
            'articles',
            'hasMoreArticles'
        ));
    }

    public function show($id)
    {
        $newsItem = News::findOrFail($id);

        // Get related news (same category, excluding current)
        $relatedNews = News::where('category', $newsItem->category)
            ->where('id', '!=', $id)
            ->latest()
            ->take(3)
            ->get();

        // Get latest news (excluding current)
        $latestNews = News::where('id', '!=', $id)
            ->latest()
            ->take(5)
            ->get();

        // Get categories with counts
        $categories = News::select('category')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        return view('website.news.show', compact('newsItem', 'relatedNews', 'latestNews', 'categories'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return redirect()->route('news');
        }

        $news = News::where('title', 'LIKE', "%{$query}%")
            ->orWhere('content', 'LIKE', "%{$query}%")
            ->orWhere('category', 'LIKE', "%{$query}%")
            ->latest()
            ->get();

        // Calculate statistics for search results
        $totalArticles = $news->count();
        $monthlyArticles = 0; // Not applicable for search
        $mostReadCategory = 'Search Results';
        $totalCategories = $news->pluck('category')->unique()->count();

        // Get categories from search results
        $categories = $news->pluck('category')
            ->unique()
            ->map(function($category) {
                return (object)['category' => $category];
            });

        // Use search results for display
        $featuredNews = collect(); // Empty for search
        $mainArticle = $news->first();
        $trendingNews = collect(); // Empty for search
        $articles = $news->skip(1);
        $hasMoreArticles = false; // No pagination for search

        return view('website.news.index', compact(
            'news',
            'totalArticles',
            'monthlyArticles',
            'mostReadCategory',
            'totalCategories',
            'categories',
            'featuredNews',
            'mainArticle',
            'trendingNews',
            'articles',
            'hasMoreArticles'
        ))->with('searchQuery', $query);
    }

    public function transfers()
    {
        $news = News::where('category', 'transfers')->latest()->get();

        // Calculate statistics
        $totalArticles = $news->count();
        $monthlyArticles = News::where('category', 'transfers')
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();
        $mostReadCategory = 'Transfer News';
        $totalCategories = 1; // Only transfers

        // Get categories (only transfers)
        $categories = collect([(object)['category' => 'transfers']]);

        // Featured news (latest 3)
        $featuredNews = $news->take(3);

        // Main article (latest)
        $mainArticle = $news->first();

        // Trending news (next 5 after main)
        $trendingNews = $news->skip(1)->take(5);

        // Articles for grid (remaining)
        $articles = $news->skip(6);

        // Check if there are more articles
        $hasMoreArticles = $articles->count() > 12;

        return view('website.news.index', compact(
            'news',
            'totalArticles',
            'monthlyArticles',
            'mostReadCategory',
            'totalCategories',
            'categories',
            'featuredNews',
            'mainArticle',
            'trendingNews',
            'articles',
            'hasMoreArticles'
        ))->with('pageTitle', 'Transfer News');
    }

    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Here you would typically save the email to a newsletter subscribers table
        // For now, we'll just return a success message

        return back()->with('success', 'Thank you for subscribing to our newsletter!');
    }

    /**
     * Get category icon for display
     */
    private function getCategoryIcon($category)
    {
        $icons = [
            'Achievements' => '🏆',
            'Events' => '🎓',
            'Training Updates' => '🏃',
            'Announcements' => '📢',
            'Match Reports' => '⚽',
            'Player Updates' => '👤',
            'Transfer News' => '🔄',
            'General' => '📰',
            'Other' => '📄'
        ];

        return $icons[$category] ?? '📰';
    }
}
