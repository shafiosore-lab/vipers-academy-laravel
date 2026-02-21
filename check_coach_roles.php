<?php

require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';

// Boot the application
$app->boot();

// Check the coach user
$coach = App\Models\User::where('email', 'coach@mumiasvipers.com')->first();

if (!$coach) {
    echo "Coach user not found.\n";
    exit(1);
}

echo "Coach user found:\n";
echo "Email: {$coach->email}\n";
echo "User Type: {$coach->user_type}\n";
echo "Approval Status: {$coach->approval_status}\n";

echo "\nRoles assigned:\n";
foreach ($coach->roles as $role) {
    echo "- {$role->slug} ({$role->name})\n";
}

echo "\nDashboard route:\n";
$hierarchyService = new \App\Services\RoleHierarchyService();
echo $hierarchyService->getDashboardRouteForUser($coach);
