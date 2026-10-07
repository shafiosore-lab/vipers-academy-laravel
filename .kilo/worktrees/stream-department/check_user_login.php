<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('email', 'parent@mumiasvipers.com')->first();

if ($user) {
    echo "User Found!\n";
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "User Type: " . $user->user_type . "\n";
    echo "Approval Status: " . $user->approval_status . "\n";
    echo "Password Hash: " . substr($user->password, 0, 60) . "...\n";

    // Check if password is correct
    $password = 'password'; // Try default password
    if (Hash::check($password, $user->password)) {
        echo "Password 'password' is CORRECT\n";
    } else {
        echo "Password 'password' is INCORRECT\n";
    }

    // Check roles
    $roles = $user->roles()->get();
    echo "Roles: ";
    foreach ($roles as $role) {
        echo $role->name . " (" . $role->slug . ") ";
    }
    echo "\n";
} else {
    echo "User NOT FOUND!\n";
}
