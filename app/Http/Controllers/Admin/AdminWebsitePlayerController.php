<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsitePlayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminWebsitePlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $players = WebsitePlayer::orderBy('category')->orderBy('last_name')->get();
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
            'position' => 'required|string',
            'age' => 'required|integer|min:6|max:25',
            'jersey_number' => 'nullable|integer',
            'bio' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Increased to 5MB
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $filename = strtolower($request->first_name . '-' . $request->last_name . '-' . $request->category . '-' . $request->position . '-' . $request->age . '.jpg');
            $imagePath = public_path('assets/img/players/' . $filename);

            // Process and compress the image
            $this->processAndSaveImage($request->file('image'), $imagePath);

            $data['image_path'] = $filename;
        }

        WebsitePlayer::create($data);

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
            'position' => 'required|string',
            'age' => 'required|integer|min:6|max:25',
            'jersey_number' => 'nullable|integer',
            'bio' => 'nullable|string',
            'goals' => 'nullable|integer|min:0',
            'assists' => 'nullable|integer|min:0',
            'appearances' => 'nullable|integer|min:0',
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

            $filename = strtolower($request->first_name . '-' . $request->last_name . '-' . $request->category . '-' . $request->position . '-' . $request->age . '.jpg');
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

    /**
     * Sync players from gallery images
     */
    public function syncFromGallery()
    {
        $result = $this->performSync();

        return redirect()->route('admin.website-players.index')->with('success', $result['message']);
    }

    /**
     * Perform the actual sync operation
     */
    protected function performSync($silent = false)
    {
        $playersPath = public_path('assets/img/players');

        // Check if directory exists
        if (!File::exists($playersPath)) {
            File::makeDirectory($playersPath, 0755, true);
            return [
                'added' => 0,
                'updated' => 0,
                'removed' => 0,
                'message' => 'Players directory created. Add player images to sync.'
            ];
        }

        $imageFiles = File::files($playersPath);
        $syncedPlayerIds = [];
        $addedCount = 0;
        $updatedCount = 0;

        foreach ($imageFiles as $file) {
            // Only process image files
            if (!in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                continue;
            }

            $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $playerData = $this->parseFilename($filename);

            if (!$playerData) {
                continue; // Skip invalid filenames
            }

            // Check if player exists
            $player = Player::where('first_name', $playerData['first_name'])
                ->where('last_name', $playerData['last_name'])
                ->where('category', $playerData['category'])
                ->first();

            $imageUrl = asset('assets/img/players/' . $file->getFilename());

            if ($player) {
                // Update existing player
                $player->update([
                    'position' => $playerData['position'],
                    'age' => $playerData['age'],
                    'image_url' => $imageUrl,
                ]);
                $updatedCount++;
            } else {
                // Create new player
                $player = Player::create([
                    'first_name' => $playerData['first_name'],
                    'last_name' => $playerData['last_name'],
                    'category' => $playerData['category'],
                    'position' => $playerData['position'],
                    'age' => $playerData['age'],
                    'image_url' => $imageUrl,
                    'jersey_number' => null, // Can be set manually later
                ]);
                $addedCount++;
            }

            $syncedPlayerIds[] = $player->id;
        }

        // Remove players whose images no longer exist
        $removedCount = Player::whereNotIn('id', $syncedPlayerIds)->delete();

        $message = $this->buildSyncMessage($addedCount, $updatedCount, $removedCount);

        return [
            'added' => $addedCount,
            'updated' => $updatedCount,
            'removed' => $removedCount,
            'message' => $message
        ];
    }

    /**
     * Parse filename to extract player data
     */
    protected function parseFilename($filename)
    {
        // Split by hyphen
        $parts = explode('-', strtolower($filename));

        // Must have at least 5 parts: firstname, surname, category, position, age
        if (count($parts) < 5) {
            return null;
        }

        // Extract parts
        $age = array_pop($parts); // Last part is age
        $position = array_pop($parts); // Second to last is position
        $category = array_pop($parts); // Third to last is category

        // Remaining parts are the name (could be multiple parts)
        // Last part before category is surname, rest is first name
        $surname = array_pop($parts);
        $firstname = implode(' ', $parts);

        // Validate age is numeric
        if (!is_numeric($age)) {
            return null;
        }

        // Validate category
        $validCategories = ['u9', 'u11', 'u13', 'u15', 'u17', 'u19', 'senior', 'academy'];
        if (!in_array($category, $validCategories)) {
            return null;
        }

        // Validate position
        $validPositions = [
            'goalkeeper', 'gk',
            'defender', 'def', 'lb', 'rb', 'cb',
            'midfielder', 'mid', 'cdm', 'cam', 'cm', 'lm', 'rm',
            'forward', 'fwd', 'striker', 'st', 'lw', 'rw', 'cf'
        ];
        if (!in_array($position, $validPositions)) {
            return null;
        }

        return [
            'first_name' => ucwords($firstname),
            'last_name' => ucwords($surname),
            'category' => $category,
            'position' => $position,
            'age' => (int) $age,
        ];
    }

    /**
     * Build sync message based on counts
     */
    protected function buildSyncMessage($added, $updated, $removed)
    {
        $messages = [];

        if ($added > 0) {
            $messages[] = "{$added} player(s) added";
        }
        if ($updated > 0) {
            $messages[] = "{$updated} player(s) updated";
        }
        if ($removed > 0) {
            $messages[] = "{$removed} player(s) removed";
        }

        if (empty($messages)) {
            return "No changes detected. All players are up to date.";
        }

        return "Sync completed: " . implode(', ', $messages) . ".";
    }
}
