<?php

require __DIR__.'/vendor/autoload.php';

// Create Laravel application
$app = require_once __DIR__.'/bootstrap/app.php';

// Handle incoming request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Check if user exists
$user = App\Models\User::where('email', 'coach@mumiasvipers.com')->first();

if (!$user) {
    echo "User not found\n";
    exit(1);
}

echo "User Found:\n";
echo "ID: {$user->id}\n";
echo "Email: {$user->email}\n";
echo "Name: {$user->name}\n";
echo "User Type: {$user->user_type}\n";
echo "Approval Status: {$user->approval_status}\n";

// Verify password
$password = 'password';
$isValid = Hash::check($password, $user->password);
echo "Password valid: " . ($isValid ? "Yes" : "No") . "\n";

// Check roles
echo "Roles:\n";
foreach ($user->roles as $role) {
    echo "  - {$role->name} ({$role->slug})\n";
}
