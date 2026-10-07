<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Laravel Application Initialized ===\n";

// Test 1: Check if User model is available
try {
    $userClass = '\App\Models\User';
    $roleClass = '\App\Models\Role';
    $roleServiceClass = '\App\Services\RoleHierarchyService';

    echo "\n=== Testing Classes ===\n";
    echo "User class exists: " . (class_exists($userClass) ? "✅" : "❌") . "\n";
    echo "Role class exists: " . (class_exists($roleClass) ? "✅" : "❌") . "\n";
    echo "RoleHierarchyService class exists: " . (class_exists($roleServiceClass) ? "✅" : "❌") . "\n";
} catch (\Exception $e) {
    echo "\n❌ Error initializing classes: " . $e->getMessage() . "\n";
}

// Test 2: Check if we can create and login a test user
try {
    echo "\n=== Creating Test User ===\n";

    // Create a test user
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'test@mumiasvipers.com'],
        [
            'name' => 'Test Coach',
            'password' => bcrypt('password'),
            'user_type' => 'staff',
            'approval_status' => 'approved',
            'status' => 'active',
        ]
    );

    // Assign coach role
    $coachRole = \App\Models\Role::firstOrCreate(
        ['slug' => 'coach'],
        [
            'name' => 'Coach',
            'description' => 'Test Coach Role',
            'type' => 'partner_staff',
            'is_default' => false,
            'level' => 4,
        ]
    );

    $user->roles()->sync([$coachRole->id]);

    echo "✅ Test user created: " . $user->email . "\n";
    echo "✅ User roles: " . implode(', ', $user->roles->pluck('slug')->toArray()) . "\n";
} catch (\Exception $e) {
    echo "\n❌ Error creating test user: " . $e->getMessage() . "\n";
    if ($e->getPrevious()) {
        echo "Previous: " . $e->getPrevious()->getMessage() . "\n";
    }
}

// Test 3: Verify login functionality works
try {
    echo "\n=== Testing Login Functionality ===\n";

    // Check if user exists
    $user = \App\Models\User::where('email', 'coach@mumiasvipers.com')->first();
    if (!$user) {
        echo "❌ User not found: coach@mumiasvipers.com\n";
    } else {
        echo "✅ User found: " . $user->email . "\n";
        echo "   - Name: " . $user->name . "\n";
        echo "   - User Type: " . $user->user_type . "\n";
        echo "   - Approval Status: " . $user->approval_status . "\n";
        echo "   - Status: " . $user->status . "\n";

        // Check roles
        if ($user->roles->count() > 0) {
            echo "   - Roles: " . implode(', ', $user->roles->pluck('slug')->toArray()) . "\n";
        } else {
            echo "   - ❌ No roles assigned\n";
        }

        // Check if password is valid
        $validPassword = \Illuminate\Support\Facades\Hash::check('password', $user->password);
        echo "   - Password valid: " . ($validPassword ? "✅" : "❌") . "\n";

        // Test login with custom logic
        if ($validPassword) {
            echo "✅ Login attempt would succeed\n";

            // Check dashboard route
            $roleService = new \App\Services\RoleHierarchyService();
            $dashboardRoute = $roleService->getDashboardRouteForUser($user);
            echo "✅ Dashboard route: " . $dashboardRoute . "\n";

            // Verify route exists
            if (\Illuminate\Support\Facades\Route::has($dashboardRoute)) {
                echo "✅ Route exists\n";
            } else {
                echo "❌ Route does not exist\n";
            }
        }
    }
} catch (\Exception $e) {
    echo "\n❌ Error testing login functionality: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

echo "\n=== All Tests Completed ===\n";
