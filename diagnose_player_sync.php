<?php
/**
 * Diagnostic script to check player data sync issues
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Player;
use App\Models\WebsitePlayer;

echo "=== PLAYER SYNC DIAGNOSTIC ===\n\n";

// Check players table
echo "PLAYERS TABLE:\n";
echo "  Total players: " . Player::count() . "\n";
echo "  Approved (full): " . Player::where('approval_type', 'full')->count() . "\n";
echo "  Approved (temporary): " . Player::where('approval_type', 'temporary')->count() . "\n";
echo "  Approved (any): " . Player::whereIn('approval_type', ['full', 'temporary'])->count() . "\n";
echo "  Pending (none): " . Player::where('approval_type', 'none')->count() . "\n\n";

echo "WEBSITE PLAYERS TABLE:\n";
echo "  Total website players: " . WebsitePlayer::count() . "\n\n";

echo "DETAILED PLAYER ANALYSIS:\n";
echo str_repeat("-", 80) . "\n";

$players = Player::all();
foreach ($players as $player) {
    $hasWebsitePlayer = $player->websitePlayer ? 'YES' : 'NO';
    echo "Player ID {$player->id}: {$player->full_name}\n";
    echo "  Position: {$player->position}, Age: {$player->age}\n";
    echo "  Approval Type: {$player->approval_type}\n";
    echo "  Has Website Record: {$hasWebsitePlayer}\n";

    if ($player->websitePlayer) {
        echo "  Website Player ID: {$player->websitePlayer->id}\n";
    }
    echo "\n";
}

echo str_repeat("-", 80) . "\n";
echo "\nPOTENTIAL ISSUES:\n";

// Check if there are approved players without website records
$approvedWithoutWebsite = Player::whereIn('approval_type', ['full', 'temporary'])
    ->whereDoesntHave('websitePlayer')
    ->count();

if ($approvedWithoutWebsite > 0) {
    echo "  WARNING: {$approvedWithoutWebsite} approved players are NOT synced to website!\n";
    echo "  Players affected:\n";
    $affected = Player::whereIn('approval_type', ['full', 'temporary'])
        ->whereDoesntHave('websitePlayer')
        ->get();
    foreach ($affected as $p) {
        echo "    - ID {$p->id}: {$p->full_name} ({$p->approval_type} approval)\n";
    }
} else {
    echo "  All approved players are synced to website ✓\n";
}

// Check for website players without matching player record
$orphanedWebsitePlayers = WebsitePlayer::whereNull('player_id')->count();
if ($orphanedWebsitePlayers > 0) {
    echo "  WARNING: {$orphanedWebsitePlayers} website players have no matching player record!\n";
}

echo "\n=== END DIAGNOSTIC ===\n";
