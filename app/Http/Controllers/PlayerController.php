<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Display a listing of all players organized by category
     */
    public function index()
    {
        // Automatically sync players from gallery before displaying
        $this->autoSyncPlayersFromGallery();

        // Get all players and organize by category
        $categories = [
            'under-13' => Player::where('category', 'under-13')->orderedByPositionAndAge()->get(),
            'under-15' => Player::where('category', 'under-15')->orderedByPositionAndAge()->get(),
            'under-17' => Player::where('category', 'under-17')->orderedByPositionAndAge()->get(),
            'senior' => Player::where('category', 'senior')->orderedByPositionAndAge()->get(),
        ];

        // Remove empty categories
        $categories = array_filter($categories, function($players) {
            return $players->isNotEmpty();
        });

        return view('players.index', compact('categories'));
    }

    /**
     * Display player statistics
     */
    public function stats($id)
    {
        $player = Player::findOrFail($id);
        return view('players.stats', compact('player'));
    }

    /**
     * Sync players from the players folder
     * This reads all images from public/assets/img/players and creates/updates player records
     */
    public function syncPlayersFromGallery()
    {
        $syncedCount = $this->autoSyncPlayersFromGallery();
        return redirect()->route('players.index')->with('success', "Synced {$syncedCount} players from gallery");
    }

    /**
     * Auto sync players from gallery (called internally)
     * Returns the number of players synced
     */
    private function autoSyncPlayersFromGallery()
    {
        $playersPath = public_path('assets/img/players');

        if (!is_dir($playersPath)) {
            return 0;
        }

        $files = scandir($playersPath);
        $syncedCount = 0;
        $existingPlayers = [];

        foreach ($files as $file) {
            // Skip directories and hidden files
            if ($file === '.' || $file === '..' || is_dir($playersPath . '/' . $file)) {
                continue;
            }

            // Check if it's an image file
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                continue;
            }

            // Parse filename: firstname-lastname-category-position-age.jpg
            $filename = pathinfo($file, PATHINFO_FILENAME);

            // Remove .jpg if present in filename (for double extensions)
            $filename = str_replace('.jpg', '', $filename);

            $parts = explode('-', $filename);

            if (count($parts) < 5) {
                continue; // Skip files that don't match the format
            }

            $firstName = ucfirst($parts[0]);
            $lastName = ucfirst($parts[1]);
            $categoryRaw = strtolower($parts[2]);
            $position = strtolower($parts[3]);
            $age = (int) $parts[4];

            // Normalize category
            $categoryMap = [
                'under13' => 'under-13',
                'under15' => 'under-15',
                'under17' => 'under-17',
                'seniour' => 'senior',
                'senior' => 'senior'
            ];

            $category = $categoryMap[$categoryRaw] ?? $categoryRaw;

            // Validate category and position
            if (!in_array($category, ['under-13', 'under-15', 'under-17', 'senior'])) {
                continue;
            }

            if (!in_array($position, ['goalkeeper', 'defender', 'midfielder', 'striker'])) {
                continue;
            }

            // Check if player exists
            $existingPlayer = Player::where('first_name', $firstName)
                ->where('last_name', $lastName)
                ->where('category', $category)
                ->first();

            if ($existingPlayer) {
                // Update existing
                $existingPlayer->update([
                    'name' => "$firstName $lastName",
                    'position' => $position,
                    'age' => $age,
                    'image_path' => $file,
                    'program_id' => 1,
                    'registration_status' => 'Active',
                    'approval_type' => 'full',
                    'documents_completed' => true,
                ]);
                $player = $existingPlayer;
            } else {
                // Create new
                $player = Player::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'category' => $category,
                    'name' => "$firstName $lastName",
                    'position' => $position,
                    'age' => $age,
                    'image_path' => $file,
                    'program_id' => 1,
                    'registration_status' => 'Active',
                    'approval_type' => 'full',
                    'documents_completed' => true,
                ]);
            }

            $existingPlayers[] = $player->id;
            $syncedCount++;
        }

        // Remove players from database whose images no longer exist
        if (!empty($existingPlayers)) {
            Player::whereNotIn('id', $existingPlayers)->delete();
        }

        return $syncedCount;
    }
}
