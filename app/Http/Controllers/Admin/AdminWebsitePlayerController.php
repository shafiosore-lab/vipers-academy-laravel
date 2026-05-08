<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsitePlayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AdminWebsitePlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $players = WebsitePlayer::orderBy('category')->orderBy('last_name')->paginate(12);
        return view('admin.website-players.index', compact('players'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.website-players.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'category' => 'required|string',
            'gender' => 'required|in:M,F',
            'position' => 'required|string',
            'age' => 'required|integer|min:1',
            'jersey_number' => 'nullable|integer',
            'bio' => 'nullable|string',
            'youtube_url' => 'nullable|url',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Increased to 5MB
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $filename = strtolower($request->first_name . '-' . $request->last_name . '-' . $request->position . '.jpg');
            $imagePath = public_path('assets/img/players/' . $filename);

            // Process and compress the image
            $this->processAndSaveImage($request->file('image'), $imagePath);

            $data['image_path'] = $filename;
        }

        $websitePlayer = WebsitePlayer::create($data);

        return redirect()->route('admin.website-players.index')->with('success', 'Player added to website successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(WebsitePlayer $websitePlayer)
    {
        return view('admin.website-players.show', compact('websitePlayer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WebsitePlayer $websitePlayer)
    {
        return view('admin.website-players.edit', compact('websitePlayer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WebsitePlayer $websitePlayer)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'category' => 'required|string',
            'gender' => 'required|in:M,F',
            'position' => 'required|string',
            'age' => 'required|integer|min:1',
            'jersey_number' => 'nullable|integer',
            'bio' => 'nullable|string',
            'yellow_cards' => 'nullable|integer|min:0',
            'red_cards' => 'nullable|integer|min:0',
            'youtube_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Increased to 5MB
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($websitePlayer->image_path && file_exists(public_path('assets/img/players/' . $websitePlayer->image_path))) {
                unlink(public_path('assets/img/players/' . $websitePlayer->image_path));
            }

            $filename = strtolower($request->first_name . '-' . $request->last_name . '-' . $request->position . '.jpg');
            $imagePath = public_path('assets/img/players/' . $filename);

            // Process and compress the image
            $this->processAndSaveImage($request->file('image'), $imagePath);

            $data['image_path'] = $filename;
        }

        $websitePlayer->update($data);

        return redirect()->route('admin.website-players.index')->with('success', 'Player updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebsitePlayer $websitePlayer)
    {
        // Delete image file
        if ($websitePlayer->image_path && file_exists(public_path('assets/img/players/' . $websitePlayer->image_path))) {
            unlink(public_path('assets/img/players/' . $websitePlayer->image_path));
        }

        $websitePlayer->delete();

        return redirect()->route('admin.website-players.index')->with('success', 'Player removed from website successfully.');
    }

    /**
     * Sync website players with main player database
     */
    public function sync()
    {
        // Get players from the main Player table that don't have corresponding website player records
        $mainPlayers = \App\Models\Player::whereNull('website_player_id')
            ->where('registration_status', 'Approved')
            ->get();

        $syncedCount = 0;

        foreach ($mainPlayers as $player) {
            // Create website player from main player
            $websitePlayer = WebsitePlayer::create([
                'first_name' => $player->first_name,
                'last_name' => $player->last_name,
                'category' => $player->age_group ?? 'Youth',
                'position' => $player->position ?? 'Player',
                'age' => $player->age ?? 18,
                'jersey_number' => $player->jersey_number,
                'bio' => $player->bio ?? '',
                'player_id' => $player->id,
            ]);

            // Update the main player with the website player reference
            $player->update(['website_player_id' => $websitePlayer->id]);

            $syncedCount++;
        }

        if ($syncedCount > 0) {
            return redirect()->route('admin.website-players.index')
                ->with('success', "Successfully synced {$syncedCount} player(s) to website.");
        } else {
            return redirect()->route('admin.website-players.index')
                ->with('info', 'No new players to sync. All approved players are already on the website.');
        }
    }

    /**
     * Process and save image (simplified version without GD)
     */
    private function processAndSaveImage($file, $destinationPath)
    {
        // Ensure the directory exists
        $directory = dirname($destinationPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Simply move the uploaded file to the destination
        // Note: Image compression and resizing would require GD extension
        // For now, we save the original file with the correct naming
        $file->move($directory, basename($destinationPath));
    }

}
