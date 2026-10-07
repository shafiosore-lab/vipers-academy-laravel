<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = App\Models\User::where('user_type', 'player')->with('roles')->get();

foreach ($users as $user) {
    echo "User: {$user->name} (ID: {$user->id})" . PHP_EOL;
    echo "Roles: " . $user->roles->pluck('name')->join(', ') . PHP_EOL;
    echo "All Permissions: " . $user->getAllPermissions()->pluck('slug')->join(', ') . PHP_EOL;
    echo "---" . PHP_EOL;
}
