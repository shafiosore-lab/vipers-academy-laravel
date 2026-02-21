<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Now we can use Laravel's database functions
use App\Models\User;

$email = 'coach@mumiasvipers.com';

$user = User::where('email', $email)->first();

if ($user) {
    echo "User Status: " . $user->status . PHP_EOL;
    echo "Approval Status: " . $user->approval_status . PHP_EOL;
    echo "Is Active: " . ($user->isActive() ? 'Yes' : 'No') . PHP_EOL;
    echo "Is Approved: " . ($user->isApproved() ? 'Yes' : 'No') . PHP_EOL;
    echo "Is Admin: " . ($user->isAdmin() ? 'Yes' : 'No') . PHP_EOL;
    echo "Is Staff: " . ($user->isStaff() ? 'Yes' : 'No') . PHP_EOL;
} else {
    echo "User not found";
}
