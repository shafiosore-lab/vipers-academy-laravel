<?php

namespace App\Http\Controllers\Website;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends \App\Http\Controllers\Controller
{
    /**
     * Display all blog posts.
     */
    public function index(Request $request)
    {
        try {
            $blogs = Blog::published()->latest()->get();

            // Calculate statistics
            $totalArticles = $blogs->count();
            $monthlyArticles = Blog::where('created_at', '>=', now()->startOfMonth())->count();
            $mostReadCategory = $blogs->groupBy('category')->max('count');
            $totalCategories = Blog::distinct('category')->count('category');

            // Get categories
            $categories = Blog::select('category')
                ->distinct()
                ->pluck('category')
                ->map(function ($cat) {
                    return [
                        'name' => $cat,
                        'count' => Blog::where('category', $cat)->count(),
                        'icon' => $this->getCategoryIcon($cat),
                    ];
                })
                ->sortByDesc('count')
                ->values();

            // Featured blogs (latest 3)
            $featuredBlogs = $blogs->take(3);

            // Main article (latest)
            $mainArticle = $blogs->first();

            // Trending blogs (next 5 after main)
            $trendingBlogs = $blogs->skip(1)->take(5);

            // Articles for grid (remaining)
            $articles = $blogs->skip(6);

            $hasMoreArticles = false; // For pagination if needed

            return view('website.blog.index', compact(
                'blogs',
                'totalArticles',
                'monthlyArticles',
                'mostReadCategory',
                'totalCategories',
                'categories',
                'featuredBlogs',
                'mainArticle',
                'trendingBlogs',
                'articles',
                'hasMoreArticles'
            ));
        } catch (\Exception $e) {
            // If table doesn't exist, return empty view
            return view('website.blog.index', [
                'blogs' => collect(),
                'totalArticles' => 0,
                'monthlyArticles' => 0,
                'mostReadCategory' => 0,
                'totalCategories' => 0,
                'categories' => collect(),
                'featuredBlogs' => collect(),
                'mainArticle' => null,
                'trendingBlogs' => collect(),
                'articles' => collect(),
                'hasMoreArticles' => false,
            ]);
        }
    }

    /**
     * Display a single blog post.
     */
    public function show($slug)
    {
        try {
            $blogItem = Blog::where('slug', $slug)->first();

            // If blog post not found, return custom error view
            if (!$blogItem) {
                return view('website.blog.show', [
                    'blogItem' => null,
                    'relatedBlogs' => collect(),
                    'latestBlogs' => collect(),
                    'categories' => collect(),
                    'errorMessage' => 'The article "' . htmlspecialchars($slug) . '" was not found.',
                    'requestedSlug' => $slug,
                ]);
            }

            // Get related blogs (same category, excluding current)
            $relatedBlogs = Blog::where('category', $blogItem->category)
                ->where('id', '!=', $blogItem->id)
                ->published()
                ->latest()
                ->take(3)
                ->get();

            // Get latest blogs (excluding current)
            $latestBlogs = Blog::where('id', '!=', $blogItem->id)
                ->published()
                ->latest()
                ->take(5)
                ->get();

            // Get categories with counts
            $categories = Blog::select('category')
                ->selectRaw('COUNT(*) as count')
                ->distinct()
                ->pluck('category')
                ->map(function ($cat) {
                    return [
                        'name' => $cat,
                        'count' => Blog::where('category', $cat)->count(),
                        'icon' => $this->getCategoryIcon($cat),
                    ];
                })
                ->sortByDesc('count')
                ->values();

            // Increment views
            $blogItem->increment('views');

            return view('website.blog.show', compact('blogItem', 'relatedBlogs', 'latestBlogs', 'categories'));
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Blog show error: ' . $e->getMessage());

            return view('website.blog.show', [
                'blogItem' => null,
                'relatedBlogs' => collect(),
                'latestBlogs' => collect(),
                'categories' => collect(),
                'errorMessage' => 'An error occurred while loading this article.',
                'requestedSlug' => $slug,
            ]);
        }
    }

    /**
     * Search blog posts.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return redirect()->route('blog');
        }

        try {
            $blogs = Blog::where('title', 'LIKE', "%{$query}%")
                ->orWhere('content', 'LIKE', "%{$query}%")
                ->orWhere('excerpt', 'LIKE', "%{$query}%")
                ->published()
                ->latest()
                ->get();

            // Calculate statistics for search results
            $totalArticles = $blogs->count();
            $monthlyArticles = 0; // Not applicable for search
            $mostReadCategory = 'Search Results';
            $totalCategories = $blogs->pluck('category')->unique()->count();

            // Get categories from search results
            $categories = $blogs->pluck('category')
                ->unique()
                ->map(function ($cat) use ($blogs) {
                    return [
                        'name' => $cat,
                        'count' => $blogs->where('category', $cat)->count(),
                        'icon' => $this->getCategoryIcon($cat),
                    ];
                })
                ->sortByDesc('count')
                ->values();

            // Featured blogs (latest 3)
            $featuredBlogs = $blogs->take(3);

            // Main article (latest)
            $mainArticle = $blogs->first();

            // Trending blogs (next 5 after main)
            $trendingBlogs = $blogs->skip(1)->take(5);

            // Articles for grid (remaining)
            $articles = $blogs->skip(1);
            $hasMoreArticles = false; // No pagination for search

            return view('website.blog.index', compact(
                'blogs',
                'totalArticles',
                'monthlyArticles',
                'mostReadCategory',
                'totalCategories',
                'categories',
                'featuredBlogs',
                'mainArticle',
                'trendingBlogs',
                'articles',
                'hasMoreArticles'
            ))->with('pageTitle', 'Search Results for "' . $query . '"');
        } catch (\Exception $e) {
            return redirect()->route('blog');
        }
    }

    /**
     * Display transfer news.
     */
    public function transfers(Request $request)
    {
        try {
            $blogs = Blog::where('category', 'transfers')->published()->latest()->get();

            // Calculate statistics
            $totalArticles = $blogs->count();
            $monthlyArticles = Blog::where('category', 'transfers')
                ->where('created_at', '>=', now()->startOfMonth())
                ->count();
            $mostReadCategory = 'Transfer News';
            $totalCategories = 1; // Only transfers

            // Get categories
            $categories = collect([
                [
                    'name' => 'Transfer News',
                    'count' => $totalArticles,
                    'icon' => '🔄',
                ],
            ]);

            // Featured blogs (latest 3)
            $featuredBlogs = $blogs->take(3);

            // Main article (latest)
            $mainArticle = $blogs->first();

            // Trending blogs (next 5 after main)
            $trendingBlogs = $blogs->skip(1)->take(5);

            // Articles for grid (remaining)
            $articles = $blogs->skip(6);
            $hasMoreArticles = false;

            return view('website.blog.index', compact(
                'blogs',
                'totalArticles',
                'monthlyArticles',
                'mostReadCategory',
                'totalCategories',
                'categories',
                'featuredBlogs',
                'mainArticle',
                'trendingBlogs',
                'articles',
                'hasMoreArticles'
            ))->with('pageTitle', 'Transfer News');
        } catch (\Exception $e) {
            return redirect()->route('blog');
        }
    }

    /**
     * Subscribe to newsletter.
     */
    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Here you would typically save the email to a newsletter subscribers table
        // For now, we'll just return a success message

        return back()->with('success', 'Thank you for subscribing to our newsletter!');
    }

    /**
     * API endpoint for AJAX requests.
     */
    public function apiIndex(Request $request)
    {
        try {
            $category = $request->get('category', 'all');
            $page = $request->get('page', 1);
            $perPage = 6;

            $query = Blog::published();

            if ($category && $category !== 'all') {
                $query->where('category', $category);
            }

            $articles = $query->latest()
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get()
                ->map(function ($article) {
                    return [
                        'id' => $article->id,
                        'title' => $article->title,
                        'slug' => $article->slug,
                        'excerpt' => Str::limit(strip_tags($article->content), 150),
                        'image' => $article->image,
                        'category' => $article->category,
                        'category_icon' => $this->getCategoryIcon($article->category),
                        'created_at' => $article->created_at->toISOString(),
                        'formatted_date' => $article->created_at->format('M d, Y'),
                    ];
                });

            return response()->json([
                'articles' => $articles,
                'has_more' => $articles->count() === $perPage,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'articles' => [],
                'has_more' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get category icon.
     */
    private function getCategoryIcon(string $category): string
    {
        $icons = [
            'Academy News' => '🏆',
            'Match Reports' => '⚽',
            'Player Updates' => '👤',
            'Training Updates' => '🏃',
            'Events' => '📅',
            'Announcements' => '📢',
            'Transfer News' => '🔄',
            'General' => '📰',
        ];

        return $icons[$category] ?? '📰';
    }
}
