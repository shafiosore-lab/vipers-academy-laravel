<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:media-officer']);
    }

    public function index()
    {
        $user = auth()->user();

        // Get content statistics - using Blog model for blogs
        $totalBlogs = Blog::count();
        $publishedBlogs = Blog::whereNotNull('published_at')->where('published_at', '<=', now())->count();
        $draftBlogs = Blog::whereNull('published_at')->count();

        $totalGallery = Gallery::count();
        $recentGallery = Gallery::latest()->take(6)->get();

        // Announcements - use Blog with 'Announcements' category
        $totalAnnouncements = Blog::where('category', 'Announcements')->count();
        $recentAnnouncements = Blog::where('category', 'Announcements')->latest()->take(5)->get();

        // Get blogs by category
        $blogsByCategory = Blog::select('category')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('category')
            ->get();

        // Get recent blogs for the dashboard
        $recentBlogs = Blog::latest()->take(5)->get();

        return view('staff.media.dashboard', compact(
            'user',
            'totalBlogs',
            'publishedBlogs',
            'draftBlogs',
            'totalGallery',
            'recentGallery',
            'totalAnnouncements',
            'recentAnnouncements',
            'blogsByCategory',
            'recentBlogs'
        ));
    }

    /**
     * Display a listing of blogs.
     */
    public function blogs()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->paginate(15);
        return view('staff.media.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new blog.
     */
    public function createBlog()
    {
        return view('staff.media.blogs.create');
    }

    /**
     * Store a newly created blog in storage.
     */
    public function storeBlog(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['_token']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/blog', 'public');
        }

        // Set published_at if requested
        if ($request->has('publish') && $request->publish) {
            $data['published_at'] = now();
        }

        Blog::create($data);

        return redirect()->route('media.blogs')->with('success', 'Blog post created successfully.');
    }

    /**
     * Show the form for editing the specified blog.
     */
    public function editBlog(Blog $blog)
    {
        return view('staff.media.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified blog in storage.
     */
    public function updateBlog(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['_token', '_method']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $data['image'] = $request->file('image')->store('uploads/blog', 'public');
        }

        // Handle publish status
        if ($request->has('publish') && $request->publish) {
            if (!$blog->published_at) {
                $data['published_at'] = now();
            }
        } else {
            $data['published_at'] = null;
        }

        $blog->update($data);

        return redirect()->route('media.blogs')->with('success', 'Blog post updated successfully.');
    }

    public function gallery()
    {
        $gallery = Gallery::orderBy('created_at', 'desc')->paginate(15);
        return view('staff.media.gallery', compact('gallery'));
    }
}
