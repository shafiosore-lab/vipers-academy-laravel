<?php

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $_ENV['DB_HOST'],
    'database'  => $_ENV['DB_DATABASE'],
    'username'  => $_ENV['DB_USERNAME'],
    'password'  => $_ENV['DB_PASSWORD'],
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

use App\Models\User;
use App\Models\Role;

echo "Checking user: coach@mumiasvipers.com\n";
echo "==================================\n";

$user = User::where('email', 'coach@mumiasvipers.com')->first();

if (!$user) {
    echo "User not found in database\n";
} else {
    echo "User found:\n";
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Email verified at: " . $user->email_verified_at . "\n";
    echo "Status: " . $user->status . "\n";
    echo "Approval status: " . $user->approval_status . "\n";
    echo "Role: " . $user->role . "\n";
    echo "Password set: " . (empty($user->password) ? 'No' : 'Yes') . "\n";
    echo "Created at: " . $user->created_at . "\n";
    echo "Updated at: " . $user->updated_at . "\n";

    echo "\nRoles:\n";
    $roles = $user->roles;
    if ($roles->isEmpty()) {
        echo "No roles assigned\n";
    } else {
        foreach ($roles as $role) {
            echo "- " . $role->name . " (ID: " . $role->id . ")\n";
        }
    }

    echo "\nPermissions:\n";
    $permissions = $user->permissions;
    if ($permissions->isEmpty()) {
        echo "No permissions assigned\n";
    } else {
        foreach ($permissions as $permission) {
            echo "- " . $permission->name . " (ID: " . $permission->id . ")\n";
        }
    }
}
