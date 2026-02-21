<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

$email = 'parent@mumiasvipers.com';

// Check if user already exists
$existingUser = User::where('email', $email)->first();
if ($existingUser) {
    echo "User already exists!\n";
    exit;
}

// Create the user
$user = User::create([
    'name' => 'Parent User',
    'email' => $email,
    'password' => Hash::make('password'), // Default password - user should change it
    'user_type' => 'player', // Parent is a player type
    'approval_status' => 'approved',
]);

echo "User created with ID: " . $user->id . "\n";

// Get the Parent role
$parentRole = Role::where('slug', 'parent')->first();
if ($parentRole) {
    $user->roles()->attach($parentRole->id);
    echo "Attached Parent role to user\n";
} else {
    echo "Parent role not found!\n";
}

echo "Done! User can now login with:\n";
echo "Email: parent@mumiasvipers.com\n";
echo "Password: password\n";
