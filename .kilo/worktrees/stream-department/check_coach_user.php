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

// Get the user
$user = User::where('email', 'coach@mumiasvipers.com')->first();

if (!$user) {
    echo "User not found!\n";
    exit(1);
}

echo "User: {$user->email}\n";
echo "Name: {$user->name}\n";
echo "User Type: {$user->user_type}\n";
echo "Approval Status: {$user->approval_status}\n";
echo "Status: {$user->status}\n";
echo "-------------------\n";

// Check roles
echo "Roles:\n";
foreach ($user->roles as $role) {
    echo "- {$role->name} ({$role->slug})\n";
}

echo "-------------------\n";

// Check if user has any coach-related roles
$hasCoachRole = $user->hasRole('coach');
$hasHeadCoachRole = $user->hasRole('head-coach');
$hasAssistantCoachRole = $user->hasRole('assistant-coach');
$hasAnyCoachRole = $user->hasAnyRole(['coach', 'head-coach', 'assistant-coach']);

echo "Has coach role: " . ($hasCoachRole ? 'Yes' : 'No') . "\n";
echo "Has head coach role: " . ($hasHeadCoachRole ? 'Yes' : 'No') . "\n";
echo "Has assistant coach role: " . ($hasAssistantCoachRole ? 'Yes' : 'No') . "\n";
echo "Has any coach role: " . ($hasAnyCoachRole ? 'Yes' : 'No') . "\n";
