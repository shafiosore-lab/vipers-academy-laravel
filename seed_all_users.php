<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

// Define users to create with correct role SLUGS (not names)
$users = [
    [
        'name' => 'Coach',
        'email' => 'coach@mumiasvipers.com',
        'role_slug' => 'coach',
    ],
    [
        'name' => 'Head Coach',
        'email' => 'headcoach@mumiasvipers.com',
        'role_slug' => 'head-coach',
    ],
    [
        'name' => 'Assistant Coach',
        'email' => 'assistantcoach@mumiasvipers.com',
        'role_slug' => 'assistant-coach',
    ],
    [
        'name' => 'Team Manager',
        'email' => 'manager@mumiasvipers.com',
        'role_slug' => 'team-manager',
    ],
    [
        'name' => 'Finance Officer',
        'email' => 'finance@mumiasvipers.com',
        'role_slug' => 'finance-officer',
    ],
    [
        'name' => 'Media Officer',
        'email' => 'media@mumiasvipers.com',
        'role_slug' => 'media-officer',
    ],
    [
        'name' => 'Welfare Officer',
        'email' => 'welfare@mumiasvipers.com',
        'role_slug' => 'safeguarding-officer',
    ],
    [
        'name' => 'Admin',
        'email' => 'admin@mumiasvipers.com',
        'role_slug' => 'super-admin',
    ],
    [
        'name' => 'Operations Admin',
        'email' => 'operations@mumiasvipers.com',
        'role_slug' => 'operations-admin',
    ],
    [
        'name' => 'Player',
        'email' => 'player@mumiasvipers.com',
        'role_slug' => 'player',
    ],
    [
        'name' => 'Partner',
        'email' => 'partner@mumiasvipers.com',
        'role_slug' => 'parent', // Using Parent as partner role
    ],
];

$password = 'password';

echo "Creating/updating users and assigning roles...\n";
echo "===========================================\n";

foreach ($users as $userData) {
    // Check if user exists
    $user = User::where('email', $userData['email'])->first();

    if (!$user) {
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($password),
            'status' => 'active',
            'approval_status' => 'approved', // Ensure users are approved by default
        ]);
        echo "Created user: " . $userData['email'] . "\n";
    } else {
        // Update existing user to ensure they're approved
        if ($user->approval_status !== 'approved') {
            $user->update(['approval_status' => 'approved']);
            echo "Updated user approval: " . $userData['email'] . "\n";
        } else {
            echo "User already exists: " . $userData['email'] . "\n";
        }
    }

    // Get role by SLUG (not name)
    $role = Role::where('slug', $userData['role_slug'])->first();

    if ($role) {
        // Check if user has this role - detach all first, then attach new
        $user->roles()->detach();
        $user->roles()->attach($role->id);
        echo "  Assigned role: " . $role->name . " (slug: " . $role->slug . ")\n";
    } else {
        echo "  Role not found: " . $userData['role_slug'] . "\n";
    }
}

echo "\n===================================\n";
echo "All users created/updated successfully!\n";
echo "===================================\n";
echo "Password for all users: $password\n";
echo "\nLogin URLs:\n";
echo "- Coach Dashboard: http://127.0.0.1:8000/coach/dashboard\n";
echo "- Manager Dashboard: http://127.0.0.1:8000/manager/dashboard\n";
echo "- Finance Dashboard: http://127.0.0.1:8000/finance/dashboard\n";
echo "- Media Dashboard: http://127.0.0.1:8000/media/dashboard\n";
echo "- Welfare Dashboard: http://127.0.0.1:8000/welfare/dashboard\n";
echo "- Admin Dashboard: http://127.0.0.1:8000/admin/dashboard\n";
echo "- Player Portal: http://127.0.0.1:8000/player-portal/dashboard\n";
echo "- Partner Portal: http://127.0.0.1:8000/partner/dashboard\n";
echo "\n";
echo "IMPORTANT: Run 'php audit_roles.php' first to ensure all roles exist with proper slugs!\n";
