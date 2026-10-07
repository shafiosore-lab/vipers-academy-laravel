<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

echo "===========================================\n";
echo "ROLE & PERMISSION AUDIT REPORT\n";
echo "===========================================\n\n";

// 1. List all existing roles
echo "EXISTING ROLES:\n";
echo "-------------------------------------------";
$roles = Role::all();
$roleNameToSlug = [
    'Super Admin' => 'super-admin',
    'Admin/Operations Manager' => 'operations-admin',
    'Coach' => 'coach',
    'Head Coach' => 'head-coach',
    'Assistant Coach' => 'assistant-coach',
    'Team Manager' => 'team-manager',
    'Finance Officer' => 'finance-officer',
    'Media & Communications Officer' => 'media-officer',
    'Safeguarding/Welfare Officer' => 'safeguarding-officer',
    'Player' => 'player',
    'Parent' => 'parent',
    'Partner' => 'partner',
    'Marketing Admin' => 'marketing-admin',
    'Scouting Admin' => 'scouting-admin',
    'Coaching Admin' => 'coaching-admin',
    'Operations Admin' => 'operations-admin',
];

foreach ($roles as $role) {
    echo "\n[{$role->id}] {$role->name} => slug: '{$role->slug}'";

    // If slug is null or empty, set it based on our mapping
    if (empty($role->slug) && isset($roleNameToSlug[$role->name])) {
        $role->slug = $roleNameToSlug[$role->name];
        $role->save();
        echo " [UPDATED]";
    }
}
echo "\n\n";

// 2. Create any missing roles with proper slugs
echo "CREATING MISSING ROLES:\n";
echo "-------------------------------------------";
$requiredRoles = [
    ['name' => 'Super Admin', 'slug' => 'super-admin', 'type' => 'admin', 'description' => 'Full system access'],
    ['name' => 'Admin/Operations Manager', 'slug' => 'operations-admin', 'type' => 'admin', 'description' => 'Operations and admin management'],
    ['name' => 'Coach', 'slug' => 'coach', 'type' => 'partner_staff', 'description' => 'Football coach'],
    ['name' => 'Head Coach', 'slug' => 'head-coach', 'type' => 'partner_staff', 'description' => 'Head coach'],
    ['name' => 'Assistant Coach', 'slug' => 'assistant-coach', 'type' => 'partner_staff', 'description' => 'Assistant coach'],
    ['name' => 'Team Manager', 'slug' => 'team-manager', 'type' => 'partner_staff', 'description' => 'Team manager'],
    ['name' => 'Finance Officer', 'slug' => 'finance-officer', 'type' => 'partner_staff', 'description' => 'Finance management'],
    ['name' => 'Media & Communications Officer', 'slug' => 'media-officer', 'type' => 'partner_staff', 'description' => 'Media and communications'],
    ['name' => 'Safeguarding/Welfare Officer', 'slug' => 'safeguarding-officer', 'type' => 'partner_staff', 'description' => 'Welfare and safeguarding'],
    ['name' => 'Player', 'slug' => 'player', 'type' => 'player', 'description' => 'Player account'],
    ['name' => 'Parent', 'slug' => 'parent', 'type' => 'player', 'description' => 'Parent/Guardian account'],
    ['name' => 'Partner', 'slug' => 'partner', 'type' => 'partner_staff', 'description' => 'Partner organization'],
];

foreach ($requiredRoles as $roleData) {
    $existingRole = Role::where('slug', $roleData['slug'])->first();
    if (!$existingRole) {
        Role::create([
            'name' => $roleData['name'],
            'slug' => $roleData['slug'],
            'type' => $roleData['type'],
            'description' => $roleData['description'] ?? null,
        ]);
        echo "Created role: {$roleData['name']} (slug: {$roleData['slug']})\n";
    } else {
        // Update existing role with proper slug if needed
        if ($existingRole->name !== $roleData['name']) {
            $existingRole->name = $roleData['name'];
        }
        if (empty($existingRole->slug)) {
            $existingRole->slug = $roleData['slug'];
        }
        $existingRole->save();
        echo "Updated role: {$roleData['name']} (slug: {$roleData['slug']})\n";
    }
}
echo "\n";

// 3. List all existing permissions
echo "EXISTING PERMISSIONS (72 Total):\n";
echo "-------------------------------------------";
$permissions = Permission::all();
$permMap = [];
foreach ($permissions as $perm) {
    $permMap[strtolower($perm->name)] = $perm->id;
    echo "\n[{$perm->id}] {$perm->name}";
}
echo "\n\n";

// 4. Map new permission names to existing ones
// This maps our desired permission names to existing permission IDs
$permissionMapping = [
    // Training (existing IDs)
    'view-training' => $permMap['view training data'] ?? null,
    'create-training' => $permMap['view training data'] ?? null,
    'edit-training' => $permMap['view training data'] ?? null,
    'delete-training' => $permMap['view training data'] ?? null,

    // Attendance (existing IDs)
    'view-attendance' => $permMap['view attendance history'] ?? null,
    'record-attendance' => $permMap['mark attendance'] ?? null,
    'approve-attendance' => $permMap['mark attendance'] ?? null,

    // Players (existing IDs)
    'view-players' => $permMap['view players'] ?? null,
    'edit-player-progress' => $permMap['edit players'] ?? null,
    'manage-player-assignments' => $permMap['assign players to team'] ?? null,

    // Registrations
    'view-registrations' => $permMap['approve players'] ?? null,
    'approve-registrations' => $permMap['approve players'] ?? null,
    'manage-registrations' => $permMap['approve players'] ?? null,

    // Finance
    'view-payments' => $permMap['view payments'] ?? null,
    'manage-payments' => $permMap['process payments'] ?? null,
    'view-reports' => $permMap['view financial reports'] ?? null,
    'export-reports' => $permMap['export reports'] ?? null,
    'view-invoices' => $permMap['view orders'] ?? null,

    // Media
    'view-blogs' => $permMap['view blogs'] ?? null,
    'create-blogs' => $permMap['create blogs'] ?? null,
    'edit-blogs' => $permMap['edit blogs'] ?? null,
    'view-gallery' => $permMap['view gallery'] ?? null,
    'upload-gallery' => $permMap['create gallery'] ?? null,
    'manage-media' => $permMap['edit gallery'] ?? null,

    // Welfare
    'view-welfare' => $permMap['view players'] ?? null,
    'manage-welfare' => $permMap['edit players'] ?? null,
    'view-attention-list' => $permMap['view players'] ?? null,
    'view-compliance' => $permMap['approve documents'] ?? null,
    'manage-compliance' => $permMap['approve documents'] ?? null,

    // Reports
    'generate-reports' => $permMap['generate reports'] ?? null,

    // Logistics
    'manage-logistics' => $permMap['edit team'] ?? null,

    // Player/Parent
    'view-own-profile' => $permMap['view player portal'] ?? null,
    'view-training-schedule' => $permMap['view training data'] ?? null,
    'view-child-profile' => $permMap['view players'] ?? null,
];

// 5. Define permissions per role using EXISTING permission IDs AND slugs
$rolePermissionIds = [
    'coach' => [
        $permMap['view training data'] ?? 57,
        $permMap['view players'] ?? 6,
        $permMap['edit players'] ?? 8,
        $permMap['mark attendance'] ?? 60,
        $permMap['view attendance history'] ?? 64,
        $permMap['view financial reports'] ?? 38,
    ],
    'head-coach' => [
        $permMap['view training data'] ?? 57,
        $permMap['start training session'] ?? 58,
        $permMap['end training session'] ?? 59,
        $permMap['mark attendance'] ?? 60,
        $permMap['view attendance history'] ?? 64,
        $permMap['view players'] ?? 6,
        $permMap['edit players'] ?? 8,
        $permMap['assign players to team'] ?? 67,
        $permMap['view financial reports'] ?? 38,
        $permMap['generate reports'] ?? 71,
        $permMap['export reports'] ?? 72,
    ],
    'assistant-coach' => [
        $permMap['view training data'] ?? 57,
        $permMap['start training session'] ?? 58,
        $permMap['mark attendance'] ?? 60,
        $permMap['view attendance history'] ?? 64,
        $permMap['view players'] ?? 6,
        $permMap['edit players'] ?? 8,
    ],
    'team-manager' => [
        $permMap['approve players'] ?? 10,
        $permMap['view players'] ?? 6,
        $permMap['edit team'] ?? 66,
        $permMap['assign players to team'] ?? 67,
        $permMap['send bulk messages'] ?? 68,
        $permMap['send team messages'] ?? 69,
        $permMap['view financial reports'] ?? 38,
    ],
    'finance-officer' => [
        $permMap['view payments'] ?? 36,
        $permMap['process payments'] ?? 37,
        $permMap['view financial reports'] ?? 38,
        $permMap['view orders'] ?? 39,
        $permMap['process orders'] ?? 40,
        $permMap['generate reports'] ?? 71,
        $permMap['export reports'] ?? 72,
    ],
    'media-officer' => [
        $permMap['view news'] ?? 16,
        $permMap['create news'] ?? 17,
        $permMap['edit news'] ?? 18,
        $permMap['delete news'] ?? 19,
        $permMap['view gallery'] ?? 20,
        $permMap['create gallery'] ?? 21,
        $permMap['edit gallery'] ?? 22,
        $permMap['approve announcements'] ?? 70,
    ],
    'safeguarding-officer' => [
        $permMap['view players'] ?? 6,
        $permMap['edit players'] ?? 8,
        $permMap['approve documents'] ?? 44,
        $permMap['view documents'] ?? 42,
        $permMap['upload documents'] ?? 43,
    ],
    'super-admin' => [
        // All permissions (1-72)
    ],
    'operations-admin' => [
        // Most permissions
    ],
    'player' => [
        $permMap['view player portal'] ?? 55,
        $permMap['update player profile'] ?? 56,
        $permMap['view training data'] ?? 57,
    ],
    'parent' => [
        $permMap['view player portal'] ?? 55,
        $permMap['view training data'] ?? 57,
    ],
];

echo "ASSIGNING PERMISSIONS TO ROLES...\n";
echo "-------------------------------------------";

// Get all permission IDs for admin roles
$allPermIds = range(1, 72);

// Assign permissions to roles (using slug now)
foreach ($rolePermissionIds as $roleSlug => $permIds) {
    $role = Role::where('slug', $roleSlug)->first();
    if (!$role) continue;

    if ($roleSlug === 'super-admin' || $roleSlug === 'operations-admin') {
        // Give admin roles all permissions
        $role->permissions()->sync($allPermIds);
        echo "Assigned ALL permissions to: {$role->name} (slug: {$roleSlug})\n";
    } else {
        // Filter out null values and sync
        $validPermIds = array_filter($permIds);
        $role->permissions()->sync($validPermIds);
        echo "Assigned " . count($validPermIds) . " permissions to: {$role->name} (slug: {$roleSlug})\n";
    }
}
echo "\n";

// 6. Verify user to role mapping (using slug)
echo "VERIFYING USER ROLE ASSIGNMENTS...\n";
echo "-------------------------------------------";

$userRoleMap = [
    'coach@mumiasvipers.com' => 'coach',
    'headcoach@mumiasvipers.com' => 'head-coach',
    'assistantcoach@mumiasvipers.com' => 'assistant-coach',
    'manager@mumiasvipers.com' => 'team-manager',
    'finance@mumiasvipers.com' => 'finance-officer',
    'media@mumiasvipers.com' => 'media-officer',
    'welfare@mumiasvipers.com' => 'safeguarding-officer',
    'admin@mumiasvipers.com' => 'super-admin',
    'operations@mumiasvipers.com' => 'operations-admin',
    'player@mumiasvipers.com' => 'player',
    'partner@mumiasvipers.com' => 'partner',
];

foreach ($userRoleMap as $email => $roleSlug) {
    $user = User::where('email', $email)->first();
    $role = Role::where('slug', $roleSlug)->first();

    if ($user && $role) {
        // Use sync to replace roles (avoids duplicate entry errors)
        $user->roles()->sync([$role->id]);
        echo "✓ Assigned {$role->name} (slug: {$roleSlug}) to {$email}\n";
    } else {
        echo "✗ Error: User or Role not found for {$email} (expected role: {$roleSlug})\n";
    }
}

echo "\n===========================================\n";
echo "ROLE-PERMISSION MAPPING COMPLETE\n";
echo "===========================================\n";

// Generate JSON output
echo "\n\n";
echo "===========================================\n";
echo "USER DEFAULT ROLE & PERMISSION MAPPING\n";
echo "===========================================\n\n";

$output = [];

foreach ($userRoleMap as $email => $roleSlug) {
    $user = User::where('email', $email)->first();
    $role = Role::where('slug', $roleSlug)->first();

    if ($user && $role) {
        $perms = $role->permissions->pluck('name')->toArray();

        $entry = [
            'email' => $email,
            'dashboard' => strtolower(str_replace(' ', '-', $role->name)) . '-dashboard',
            'default_role' => $roleSlug,
            'permissions' => $perms,
        ];

        $output[] = $entry;

        echo json_encode($entry, JSON_PRETTY_PRINT) . "\n\n";
    }
}

echo "\n===========================================\n";
echo "AUDIT COMPLETE\n";
echo "===========================================\n";
