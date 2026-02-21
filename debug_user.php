<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'coach@mumiasvipers.com')->first();

if ($user) {
    echo "Found User: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Status: " . $user->status . "\n";
    echo "Approval Status: " . $user->approval_status . "\n";
    echo "User Type: " . $user->user_type . "\n";

    echo "\nRoles:\n";
    foreach ($user->roles as $role) {
        echo "- " . $role->name . " (" . $role->slug . ")\n";
    }

    echo "\nHas coach role: " . ($user->hasRole('coach') ? 'Yes' : 'No') . "\n";
    echo "Has head-coach role: " . ($user->hasRole('head-coach') ? 'Yes' : 'No') . "\n";
    echo "Has assistant-coach role: " . ($user->hasRole('assistant-coach') ? 'Yes' : 'No') . "\n";

    echo "\nisApproved(): " . ($user->isApproved() ? 'Yes' : 'No') . "\n";
} else {
    echo "User NOT FOUND in database!\n";

    // Check what users exist
    echo "\nAll users in database:\n";
    $users = App\Models\User::all();
    foreach ($users as $u) {
        echo "- " . $u->email . " (" . $u->name . ")\n";
    }
}
