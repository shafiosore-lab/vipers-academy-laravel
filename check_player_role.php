<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$role = App\Models\Role::where('slug', 'player')->first();
echo "Parent Role ID: " . ($role->parent_role_id ?? 'none') . PHP_EOL;
echo "Inherit Permissions: " . ($role->inherit_permissions ? 'true' : 'false') . PHP_EOL;
echo "Direct Permissions: " . $role->permissions->pluck('slug')->join(', ') . PHP_EOL;
echo "All Permissions: " . $role->getAllPermissions()->pluck('slug')->join(', ') . PHP_EOL;
