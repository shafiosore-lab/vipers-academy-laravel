<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\User;

echo "ROLES AND THEIR SLUGS:\n";
echo "========================\n";
$roles = Role::all();
foreach ($roles as $role) {
    echo "ID: {$role->id} | Name: {$role->name} | Slug: " . ($role->slug ?? 'NULL') . "\n";
}

echo "\n\nUSER ROLES:\n";
echo "========================\n";
$users = User::with('roles')->get();
foreach ($users as $user) {
    echo "{$user->email}:\n";
    foreach ($user->roles as $role) {
        echo "  - {$role->name} (slug: " . ($role->slug ?? 'NULL') . ")\n";
    }
}
