<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Now we can use Laravel's authentication system
use Illuminate\Support\Facades\Auth;
use App\Models\User;

$email = 'coach@mumiasvipers.com';
$password = 'password';

// Test 1: Check if user exists
echo "Checking if user exists...\n";
$user = User::where('email', $email)->first();
if (!$user) {
    echo "ERROR: User not found\n";
    exit(1);
}

echo "User found: {$user->name} ({$user->email})\n";
echo "User type: {$user->user_type}\n";
echo "Approval status: {$user->approval_status}\n";
echo "Status: {$user->status}\n";

// Test 2: Try to log in
echo "\nAttempting login...\n";
if (Auth::attempt(['email' => $email, 'password' => $password])) {
    echo "SUCCESS: User logged in\n";

    // Check current authenticated user
    $authenticatedUser = Auth::user();
    echo "Current authenticated user: {$authenticatedUser->name}\n";

    // Check roles
    echo "Roles: ";
    foreach ($authenticatedUser->roles as $role) {
        echo "{$role->slug} ";
    }
    echo "\n";

    // Test dashboard route
    echo "\nChecking dashboard route...\n";
    try {
        $hierarchyService = new \App\Services\RoleHierarchyService();
        $dashboardRoute = $hierarchyService->getDashboardRouteForUser($authenticatedUser);
        echo "Dashboard route: {$dashboardRoute}\n";
    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        echo $e->getTraceAsString();
    }

} else {
    echo "ERROR: Invalid credentials\n";

    // Check if password is valid
    if (!password_verify($password, $user->password)) {
        echo "Password is incorrect. Stored hash: {$user->password}\n";
        echo "Trying to hash 'password': " . password_hash('password', PASSWORD_DEFAULT) . "\n";
    }
}
