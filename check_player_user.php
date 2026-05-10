<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::whereHas('roles', function($q) {
    $q->where('slug', 'player');
})->first();

if ($user) {
    echo "User: {$user->name} (ID: {$user->id})" . PHP_EOL;
    echo "Roles: " . $user->roles->pluck('name')->join(', ') . PHP_EOL;
    echo "Direct Permissions: " . $user->permissions->pluck('slug')->join(', ') . PHP_EOL;
    echo "All Permissions via allRoles(): " . $user->allPermissions()->pluck('slug')->join(', ') . PHP_EOL;
    
    // Check specific abilities
    echo "Can players.programs.view? " . ($user->can('players.programs.view') ? 'YES' : 'NO') . PHP_EOL;
    echo "Can players.portal.view? " . ($user->can('players.portal.view') ? 'YES' : 'NO') . PHP_EOL;
} else {
    echo "No user with player role found." . PHP_EOL;
}
