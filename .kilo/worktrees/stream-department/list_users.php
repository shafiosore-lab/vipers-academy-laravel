<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "All users in system:\n";
echo "====================\n";

$users = User::all();
if ($users->count() > 0) {
    foreach ($users as $u) {
        echo "ID: " . $u->id . "\n";
        echo "Email: " . $u->email . "\n";
        echo "Name: " . $u->name . "\n";
        echo "User Type: " . ($u->user_type ?? 'null') . "\n";
        echo "Approval Status: " . ($u->approval_status ?? 'null') . "\n";
        echo "---\n";
    }
} else {
    echo "No users found!\n";
}
