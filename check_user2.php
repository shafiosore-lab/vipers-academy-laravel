<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'coach@mumiasvipers.com')->first();

if ($user) {
    echo "Found user: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Status: " . $user->status . "\n";
    echo "Approval: " . $user->approval_status . "\n";
    echo "Roles: " . $user->roles->pluck('slug')->implode(', ') . "\n";
} else {
    echo "User NOT FOUND\n";
}

echo "\nAll users:\n";
$users = App\Models\User::all();
foreach ($users as $u) {
    echo "- " . $u->email . " (" . $u->name . ")\n";
}
