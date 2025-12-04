<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class PlayerController extends Controller
{
    /**
     * Display a listing of all players organized by category
     * Automatically syncs before displaying
     */
    public function index()
    {
        // Auto-sync players from gallery on every page load
        $this->autoSyncPlayersFromGallery();

        // Get all players organized by category
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
     * Auto-sync before showing stats
     */
    public function stats($id)
    {
        // Sync to ensure we have latest data
        $this->autoSyncPlayersFromGallery();

        $player = Player::findOrFail($id);
        return view('players.stats', compact('player'));
    }

    /**
     * Manual sync endpoint (optional - for admin use)
     */
    public function syncPlayersFromGallery()
    {
        $result = $this->autoSyncPlayersFromGallery();

        return redirect()->route('players.index')->with('success',
            "Synced {$result['synced']} players. Added: {$result['added']}, Updated: {$result['updated']}, Removed: {$result['removed']}"
        );
    }

    /**
     * Auto sync players from gallery folder
     * This runs automatically whenever the player pages are accessed
     *
     * @return array Statistics about the sync operation
     */
    private function autoSyncPlayersFromGallery()
    {
        $playersPath = public_path('assets/img/players');
        $stats = [
            'synced' => 0,
            'added' => 0,
            'updated' => 0,
            'removed' => 0,
        ];

        // Check if directory exists
        if (!File::isDirectory($playersPath)) {
            Log::warning("Players directory not found: {$playersPath}");
            return $stats;
        }

        // Get all image files from directory
        $files = File::files($playersPath);
        $validPlayerIds = [];

        foreach ($files as $file) {
            $filename = $file->getFilename();

            // Only process image files
            $extension = strtolower($file->getExtension());
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                continue;
            }

            // Parse filename: firstname-lastname-category-position-age.jpg
            $filenameParts = pathinfo($filename, PATHINFO_FILENAME);
            $filenameParts = preg_replace('/\.jpg.*$/i', '', $filenameParts);

            $parts = explode('-', $filenameParts);

            // Validate format
            if (count($parts) < 5) {
                Log::warning("Invalid player filename format: {$filename}");
                continue;
            }

            // Extract player data
            $firstName = ucfirst(trim($parts[0]));
            $lastName = ucfirst(trim($parts[1]));
            $categoryRaw = strtolower(trim($parts[2]));
            $positionRaw = strtolower(trim($parts[3]));
            $age = (int) trim($parts[4]);

            // Normalize category
            $category = $this->normalizeCategory($categoryRaw);

            // Normalize position
            $position = $this->normalizePosition($positionRaw);

            // Validate data
            if (!$category || !$position || $age <= 0) {
                Log::warning("Invalid player data in filename: {$filename}");
                continue;
            }

            // Check if player exists
            $player = Player::where('first_name', $firstName)
                ->where('last_name', $lastName)
                ->where('category', $category)
                ->first();

            $playerData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'name' => "{$firstName} {$lastName}",
                'category' => $category,
                'position' => $position,
                'age' => $age,
                'image_path' => $filename,
                'program_id' => 1,
                'registration_status' => 'Active',
                'approval_type' => 'full',
                'documents_completed' => true,
            ];

            if ($player) {
                // Update existing player
                $player->update($playerData);
                $stats['updated']++;
                $validPlayerIds[] = $player->id;
            } else {
                // Create new player
                $newPlayer = Player::create($playerData);
                $stats['added']++;
                $validPlayerIds[] = $newPlayer->id;
            }

            $stats['synced']++;
        }

        // Remove players whose images no longer exist
        if (!empty($validPlayerIds)) {
            $deletedCount = Player::whereNotIn('id', $validPlayerIds)->delete();
            $stats['removed'] = $deletedCount;
        } else {
            // If no valid players found, optionally delete all
            // Uncomment if you want to remove all players when folder is empty
            // $stats['removed'] = Player::count();
            // Player::truncate();
        }

        return $stats;
    }

    /**
     * Normalize category name
     *
     * @param string $categoryRaw
     * @return string|null
     */
    private function normalizeCategory($categoryRaw)
    {
        $categoryMap = [
            'under13' => 'under-13',
            'under-13' => 'under-13',
            'u13' => 'under-13',
            'under15' => 'under-15',
            'under-15' => 'under-15',
            'u15' => 'under-15',
            'under17' => 'under-17',
            'under-17' => 'under-17',
            'u17' => 'under-17',
            'seniour' => 'senior',
            'senior' => 'senior',
            'sen' => 'senior',
        ];

        $normalized = $categoryMap[$categoryRaw] ?? null;

        // Validate against allowed categories
        if (!in_array($normalized, ['under-13', 'under-15', 'under-17', 'senior'])) {
            return null;
        }

        return $normalized;
    }

    /**
     * Normalize position name
     *
     * @param string $positionRaw
     * @return string|null
     */
    private function normalizePosition($positionRaw)
    {
        $positionMap = [
            'goalkeeper' => 'goalkeeper',
            'gk' => 'goalkeeper',
            'keeper' => 'goalkeeper',
            'defender' => 'defender',
            'def' => 'defender',
            'defence' => 'defender',
            'midfielder' => 'midfielder',
            'mid' => 'midfielder',
            'midfield' => 'midfielder',
            'striker' => 'striker',
            'str' => 'striker',
            'forward' => 'striker',
            'attacker' => 'striker',
        ];

        $normalized = $positionMap[$positionRaw] ?? null;

        // Validate against allowed positions
        if (!in_array($normalized, ['goalkeeper', 'defender', 'midfielder', 'striker'])) {
            return null;
        }

        return $normalized;
    }

    /**
     * Force sync endpoint - clears all players and re-syncs from gallery
     */
    public function forceSync()
    {
        // Clear all existing players first
        $clearedCount = Player::count();
        Player::truncate();

        // Re-sync from gallery
        $result = $this->autoSyncPlayersFromGallery();

        return redirect()->route('players.index')->with('success',
            "Force sync completed! Cleared {$clearedCount} existing players, then synced {$result['synced']} players from gallery. Added: {$result['added']}, Updated: {$result['updated']}, Removed: {$result['removed']}"
        );
    }
}
