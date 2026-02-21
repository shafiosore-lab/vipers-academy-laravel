<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Player;

$user = User::where('email', 'player@mumiasvipers.com')->first();

if ($user) {
    echo "=== USER ===\n";
    echo "Email: " . $user->email . "\n";
    echo "User Type: " . $user->user_type . "\n";
    echo "Approval Status: " . $user->approval_status . "\n";
    echo "Status: " . $user->status . "\n";
    echo "Roles: " . $user->roles->pluck('slug')->implode(', ') . "\n";

    $player = $user->player;
    echo "\n=== PLAYER RECORD ===\n";
    if ($player) {
        echo "Player ID: " . $player->id . "\n";
        echo "Player Status: " . $player->status . "\n";
        echo "Player Registration Status: " . $player->registration_status . "\n";
    } else {
        echo "NO PLAYER RECORD FOUND!\n";
    }

    echo "\n=== HAS ROLE: player ===\n";
    echo "hasRole('player'): " . ($user->hasRole('player') ? 'true' : 'false') . "\n";
    echo "isPlayer(): " . ($user->isPlayer() ? 'true' : 'false') . "\n";

    // Test the role
    $roles = $user->roles->pluck('slug')->toArray();
    echo "User roles: " . implode(', ', $roles) . "\n";
} else {
    echo "User not found!\n";
}
