<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates a comprehensive superadmin account with full system access.
     */
    public function run(): void
    {
        $this->command->info('Creating Super Admin account...');

        // Get the super-admin role
        $superAdminRole = Role::where('slug', 'super-admin')->first();

        if (!$superAdminRole) {
            $this->command->error('Super Admin role not found! Please run RoleSeeder first.');
            return;
        }

        // Create or update the superadmin user
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@viperzacademy.com'],
            [
                'name' => 'Mumias Vipers Academy Super Admin',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@viperzacademy.com',
                'password' => bcrypt('SuperAdmin@2024!'), // Secure password
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'approved_at' => now(),
                'organization_id' => null, // Platform admin - no organization
                'phone' => '+254700000000',
            ]
        );

        // Assign super-admin role
        $superAdmin->roles()->sync([$superAdminRole->id]);

        $this->command->info("Super Admin created successfully!");
        $this->command->info("Email: superadmin@viperzacademy.com");
        $this->command->info("Password: SuperAdmin@2024!");

        // Also create a backup superadmin account
        $backupAdmin = User::updateOrCreate(
            ['email' => 'admin@viperzacademy.com'],
            [
                'name' => 'Mumias Vipers Academy Administrator',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@viperzacademy.com',
                'password' => bcrypt('Admin@2024!'), // Secure password
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'approved_at' => now(),
                'organization_id' => null, // Platform admin - no organization
                'phone' => '+254700000001',
            ]
        );

        // Assign super-admin role to backup
        $backupAdmin->roles()->sync([$superAdminRole->id]);

        $this->command->info("Backup Admin created successfully!");
        $this->command->info("Email: admin@viperzacademy.com");
        $this->command->info("Password: Admin@2024!");

        // Verify super admin was created correctly
        $this->command->info('');
        $this->command->info('=== Super Admin Verification ===');

        $verifyAdmin = User::where('email', 'superadmin@viperzacademy.com')->first();

        if ($verifyAdmin) {
            $this->command->info("User ID: {$verifyAdmin->id}");
            $this->command->info("Name: {$verifyAdmin->name}");
            $this->command->info("User Type: {$verifyAdmin->user_type}");
            $this->command->info("Approval Status: {$verifyAdmin->approval_status}");
            $this->command->info("Status: {$verifyAdmin->status}");
            $this->command->info("Organization ID: " . ($verifyAdmin->organization_id ?? 'null (Platform Admin)'));
            $this->command->info("Roles: " . $verifyAdmin->roles->pluck('name')->implode(', '));
            $this->command->info("Has Super Admin Role: " . ($verifyAdmin->hasRole('super-admin') ? 'Yes' : 'No'));
        }

        $this->command->info('');
        $this->command->info('Super Admin account creation completed!');
    }
}
