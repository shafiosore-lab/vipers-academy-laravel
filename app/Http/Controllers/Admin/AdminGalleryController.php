<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminGalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::all();
        return view('admin.gallery.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'media_type' => 'required|in:image,video',
            'media_url' => 'required|url',
        ]);

        Gallery::create([
            'title' => $request->title,
            'media_type' => $request->media_type,
            'media_url' => $request->media_url,
        ]);

        // Clear relevant caches for immediate content updates
        Cache::tags(['gallery'])->flush();
        Cache::forget('gallery_items');

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item created successfully.');
    }

    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('admin.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'media_type' => 'required|in:image,video',
            'media_url' => 'required|url',
        ]);

        $gallery->update([
            'title' => $request->title,
            'media_type' => $request->media_type,
            'media_url' => $request->media_url,
        ]);

        // Clear relevant caches for immediate content updates
        Cache::tags(['gallery'])->flush();
        Cache::forget('gallery_items');

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item updated successfully.');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->delete();

        // Clear relevant caches for immediate content updates
        Cache::tags(['gallery'])->flush();
        Cache::forget('gallery_items');

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item deleted successfully.');
    }

    public function show($id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('admin.gallery.show', compact('gallery'));
    }
}
