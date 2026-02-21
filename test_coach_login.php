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
use Illuminate\Support\Facades\Hash;

echo "Verifying coach@mumiasvipers.com user:\n";
echo "==================================\n";

$user = User::where('email', 'coach@mumiasvipers.com')->first();

if (!$user) {
    echo "User not found\n";
} else {
    echo "User found:\n";
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "User type: " . $user->user_type . "\n";
    echo "Approval status: " . $user->approval_status . "\n";
    echo "Status: " . $user->status . "\n";

    echo "\nRoles:\n";
    foreach ($user->roles as $role) {
        echo "- " . $role->name . "\n";
    }

    echo "\nPassword check (should be true): " . (Hash::check('password', $user->password) ? '✅' : '❌') . "\n";
}
