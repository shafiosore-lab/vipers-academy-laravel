<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'coach@mumiasvipers.com')->first();

if ($user) {
    // Set the password to 'password'
    $user->password = Hash::make('password');
    $user->save();

    echo "Password updated successfully!\n";
    echo "Password hash: " . substr($user->password, 0, 30) . "...\n";
    echo "Verification: " . (Hash::check('password', $user->password) ? 'PASS' : 'FAIL') . "\n";
} else {
    echo "User not found!\n";
}
