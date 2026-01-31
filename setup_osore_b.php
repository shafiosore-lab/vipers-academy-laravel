<?php

/**
 * Setup script for player Osore B and training session
 * This script creates the player, schedules a session, starts it, and admits the player
 * to test the SMS notification functionality.
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Player;
use App\Models\TrainingSession;
use App\Services\SmsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Ensure we have an admin user for logging
$adminUser = \App\Models\User::where('user_type', 'admin')->first();
if (!$adminUser) {
    $adminUser = \App\Models\User::first();
}
if (!$adminUser) {
    echo "Error: No admin user found. Please create an admin user first.\n";
    exit(1);
}

// Login as admin for activity logging
Auth::login($adminUser);

echo "=== Setting up Osore B and training session ===\n\n";

// Step 1: Check if player already exists
$existingPlayer = Player::where('first_name', 'Osore')->where('last_name', 'B')->first();
if ($existingPlayer) {
    echo "Player Osore B already exists (ID: {$existingPlayer->id})\n";
    $player = $existingPlayer;
} else {
    // Step 2: Create player Osore B
    echo "Creating player Osore B...\n";
    $player = Player::create([
        'first_name' => 'Osore',
        'last_name' => 'B',
        'name' => 'Osore B',
        'full_name' => 'Osore B',
        'category' => 'under-15', // U15 team category
        'position' => 'midfielder',
        'age' => 13,
        'parent_phone' => '0711263020',
        'parent_guardian_name' => 'Parent of Osore B',
        'registration_status' => 'Active',
        'approval_type' => 'full',
        'documents_completed' => true,
    ]);
    echo "Player created with ID: {$player->id}\n";
}

// Step 3: Create a training session scheduled for right now
echo "\nCreating training session for U15...\n";
$now = now();
$session = TrainingSession::create([
    'session_type' => 'training',
    'team_category' => 'U15', // Will map to under-15 players
    'scheduled_start_time' => $now,
    'started_by' => $adminUser->id,
    'status' => 'scheduled',
    'players_admitted' => 0,
    'late_arrivals' => 0,
]);
echo "Training session created with ID: {$session->id}\n";
echo "Scheduled start time: {$session->scheduled_start_time->format('Y-m-d H:i:s')}\n";

// Step 4: Start the session
echo "\nStarting the training session...\n";
try {
    $session->start($adminUser->id);
    echo "Session started successfully. Status: {$session->status}\n";
} catch (\Exception $e) {
    echo "Error starting session: {$e->getMessage()}\n";
    exit(1);
}

// Step 5: Admit Osore B to the session (this triggers SMS notification)
echo "\nAdmitting Osore B to the session...\n";
try {
    $attendance = $session->admitPlayer($player, $adminUser->id);
    echo "Player admitted successfully. Attendance ID: {$attendance->id}\n";
} catch (\Exception $e) {
    echo "Error admitting player: {$e->getMessage()}\n";
    exit(1);
}

// Step 6: Send SMS notification to parent
echo "\nSending SMS notification to parent...\n";
$smsService = new SmsService();
$result = $smsService->sendAdmissionNotification($player, $session);
echo "SMS notification result: " . ($result ? 'SENT' : 'FAILED') . "\n";

// Summary
echo "\n=== Setup Complete ===\n";
echo "Player: {$player->full_name} (ID: {$player->id})\n";
echo "Parent Phone: {$player->parent_phone}\n";
echo "Session: {$session->team_category} {$session->session_type} (ID: {$session->id})\n";
echo "Session Status: {$session->status}\n";
echo "SMS Sent: " . ($result ? 'Yes' : 'No') . "\n";
echo "\nCheck Laravel logs for detailed SMS API response.\n";
