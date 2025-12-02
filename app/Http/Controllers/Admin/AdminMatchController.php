<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminMatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matches = FootballMatch::orderBy('match_date', 'desc')->paginate(15);

        return view('admin.matches.index', compact('matches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.matches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:friendly,tournament',
            'opponent' => 'required|string|max:255',
            'match_date' => 'required|date',
            'venue' => 'required|string|max:255',
            'status' => 'required|in:planned,upcoming,completed',
            'vipers_score' => 'nullable|integer|min:0',
            'opponent_score' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'tournament_name' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'live_link' => 'nullable|url',
            'highlights_link' => 'nullable|url',
            'match_summary' => 'nullable|string',
            'registration_open' => 'boolean',
            'registration_deadline' => 'nullable|date|after:match_date',
        ]);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('matches', 'public');
                $imagePaths[] = $path;
            }
        }

        $validated['images'] = $imagePaths;

        FootballMatch::create($validated);

        return redirect()->route('admin.matches.index')
            ->with('success', 'Match created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FootballMatch $match)
    {
        return view('admin.matches.show', compact('match'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FootballMatch $match)
    {
        return view('admin.matches.edit', compact('match'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FootballMatch $match)
    {
        $validated = $request->validate([
            'type' => 'required|in:friendly,tournament',
            'opponent' => 'required|string|max:255',
            'match_date' => 'required|date',
            'venue' => 'required|string|max:255',
            'status' => 'required|in:planned,upcoming,completed',
            'vipers_score' => 'nullable|integer|min:0',
            'opponent_score' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'tournament_name' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'live_link' => 'nullable|url',
            'highlights_link' => 'nullable|url',
            'match_summary' => 'nullable|string',
            'registration_open' => 'boolean',
            'registration_deadline' => 'nullable|date|after:match_date',
        ]);

        // Handle image uploads
        $imagePaths = $match->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('matches', 'public');
                $imagePaths[] = $path;
            }
        }

        // Handle image deletions
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imagePath) {
                if (($key = array_search($imagePath, $imagePaths)) !== false) {
                    unset($imagePaths[$key]);
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }

        $validated['images'] = array_values($imagePaths);

        $match->update($validated);

        return redirect()->route('admin.matches.index')
            ->with('success', 'Match updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FootballMatch $match)
    {
        // Delete associated images
        if ($match->images) {
            foreach ($match->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $match->delete();

        return redirect()->route('admin.matches.index')
            ->with('success', 'Match deleted successfully.');
    }
}
