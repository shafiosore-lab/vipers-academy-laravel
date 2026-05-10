<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

// Simulate signup for a player
$request = new \Illuminate\Http\Request();
$request->merge([
    'first_name' => 'New',
    'last_name' => 'Player',
    'email' => 'newplayer'.uniqid().'@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'account_type' => 'player'
]);

// Validate
$request->validate([
    'first_name' => ['required', 'string', 'max:255'],
    'last_name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'confirmed'],
    'account_type' => ['nullable', 'in:player,organization,coach,team_manager,partner,general'],
]);

$accountType = $request->input('account_type', 'general');
$userTypeMapping = [
    'player' => 'player',
    'coach' => 'staff',
    'team_manager' => 'staff',
    'organization' => 'staff',
    'partner' => 'partner',
    'general' => 'general',
];

$userData = [
    'name' => $request->first_name . ' ' . $request->last_name,
    'first_name' => $request->first_name,
    'last_name' => $request->last_name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'user_type' => $userTypeMapping[$accountType] ?? 'general',
    'status' => 'active',
    'approval_status' => 'approved',
];

$user = User::create($userData);

echo "Created user ID: $user->id, email: {$user->email}, user_type: {$user->user_type}" . PHP_EOL;

// Assign default role (same code as controller)
$roleSlug = match($accountType) {
    'player' => 'player',
    'coach' => 'coach',
    'team_manager' => 'team-manager',
    'organization' => null,
    'partner' => null,
    'general' => null,
};

if ($roleSlug) {
    $role = Role::where('slug', $roleSlug)->first();
    if ($role) {
        $user->assignRole($role);
        echo "Assigned role: {$role->name} (slug: {$roleSlug})" . PHP_EOL;
    } else {
        echo "Role not found for slug: $roleSlug" . PHP_EOL;
    }
} else {
    echo "No role assigned for account type: $accountType" . PHP_EOL;
}

echo "User roles: " . $user->roles->pluck('name')->join(', ') . PHP_EOL;
echo "User permissions: " . $user->getAllPermissions()->pluck('slug')->join(', ') . PHP_EOL;

echo "hasPermission('players.portal.view'): " . ($user->hasPermission('players.portal.view') ? 'YES' : 'NO') . PHP_EOL;
echo "hasPermission('players.programs.view'): " . ($user->hasPermission('players.programs.view') ? 'YES' : 'NO') . PHP_EOL;
