<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;

echo "Fixing partner user...\n\n";

// Find the partner user
$user = User::where('email', 'partner@mumiasvipers.com')->first();

if (!$user) {
    echo "Partner user not found!\n";
    exit(1);
}

echo "Current user details:\n";
echo "  Email: " . $user->email . "\n";
echo "  Name: " . $user->name . "\n";
echo "  User Type: " . $user->user_type . "\n";
echo "  Approval Status: " . $user->approval_status . "\n";

// Get partner role
$partnerRole = Role::where('slug', 'partner')->first();

if (!$partnerRole) {
    echo "Partner role not found! Creating it...\n";
    $partnerRole = Role::create([
        'name' => 'Partner',
        'slug' => 'partner',
        'type' => 'partner_staff',
        'description' => 'Partner organization'
    ]);
    echo "Created partner role.\n";
}

// Update user_type to partner
$user->user_type = 'partner';
$user->approval_status = 'approved';
$user->save();

// Assign partner role
$user->roles()->sync([$partnerRole->id]);

echo "\nUpdated user details:\n";
echo "  Email: " . $user->email . "\n";
echo "  Name: " . $user->name . "\n";
echo "  User Type: " . $user->user_type . "\n";
echo "  Approval Status: " . $user->approval_status . "\n";
echo "  Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";

echo "\n✅ Partner user fixed successfully!\n";
