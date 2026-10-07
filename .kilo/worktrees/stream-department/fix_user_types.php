<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "Fixing user types based on roles...\n";
echo "=====================================\n";

// Map emails to user types
$userTypeMap = [
    'coach@mumiasvipers.com' => 'staff',
    'headcoach@mumiasvipers.com' => 'staff',
    'assistantcoach@mumiasvipers.com' => 'staff',
    'manager@mumiasvipers.com' => 'staff',
    'finance@mumiasvipers.com' => 'staff',
    'media@mumiasvipers.com' => 'staff',
    'welfare@mumiasvipers.com' => 'staff',
    'admin@mumiasvipers.com' => 'admin',
    'operations@mumiasvipers.com' => 'admin',
    'player@mumiasvipers.com' => 'player',
    'partner@mumiasvipers.com' => 'partner',
];

// Also update approval_status to approved
foreach ($userTypeMap as $email => $userType) {
    $user = User::where('email', $email)->first();

    if ($user) {
        $user->update([
            'user_type' => $userType,
            'approval_status' => 'approved',
            'status' => 'active',
        ]);
        echo "Updated: {$email} -> user_type: {$userType}, approval_status: approved\n";
    } else {
        echo "User not found: {$email}\n";
    }
}

echo "\nDone!\n";
