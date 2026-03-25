<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Partner;
use App\Models\Guardian;
use Illuminate\Support\Facades\Hash;

class TestUserAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating test user accounts with @mumiasvipers.com domain...');

        // 1. Super Admin Account
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@mumiasvipers.com'],
            [
                'name' => 'Super Admin',
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'phone' => '+254700000001',
                'password' => Hash::make('password123'),
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'approved_at' => now(),
            ]
        );
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        if ($superAdminRole && !$superAdmin->hasRole('super-admin')) {
            $superAdmin->roles()->attach($superAdminRole->id);
        }
        $this->command->info('✓ Created: admin@mumiasvipers.com (Super Admin)');

        // 2. Academy Manager Account (using org-admin role)
        $academyManager = User::updateOrCreate(
            ['email' => 'manager@mumiasvipers.com'],
            [
                'name' => 'Academy Manager',
                'first_name' => 'John',
                'last_name' => 'Otieno',
                'phone' => '+254700000002',
                'password' => Hash::make('password123'),
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'approved_at' => now(),
            ]
        );
        $orgAdminRole = Role::where('slug', 'org-admin')->first();
        if ($orgAdminRole && !$academyManager->hasRole('org-admin')) {
            $academyManager->roles()->attach($orgAdminRole->id);
        }

        // Create staff record for academy manager
        Staff::updateOrCreate(
            ['user_id' => $academyManager->id],
            [
                'position' => 'Academy Manager',
                'department' => 'Management',
                'employee_id' => 'STAFF-001',
                'hire_date' => now(),
                'is_active' => true,
            ]
        );
        $this->command->info('✓ Created: manager@mumiasvipers.com (Academy Manager)');

        // 3. Coach Account
        $coach = User::updateOrCreate(
            ['email' => 'coach@mumiasvipers.com'],
            [
                'name' => 'Head Coach',
                'first_name' => 'David',
                'last_name' => 'Mwangi',
                'phone' => '+254700000003',
                'password' => Hash::make('password123'),
                'user_type' => 'staff',
                'approval_status' => 'approved',
                'status' => 'active',
                'approved_at' => now(),
            ]
        );
        $coachRole = Role::where('slug', 'coach')->first();
        if ($coachRole && !$coach->hasRole('coach')) {
            $coach->roles()->attach($coachRole->id);
        }

        // Create staff record for coach
        Staff::updateOrCreate(
            ['user_id' => $coach->id],
            [
                'position' => 'Head Coach',
                'department' => 'Football',
                'employee_id' => 'COACH-001',
                'hire_date' => now()->subMonths(6),
                'is_active' => true,
                'specialization' => 'Youth Development',
            ]
        );
        $this->command->info('✓ Created: coach@mumiasvipers.com (Coach)');

        // 4. Partner Account
        $partnerUser = User::updateOrCreate(
            ['email' => 'partner@mumiasvipers.com'],
            [
                'name' => 'Partner Organization',
                'first_name' => 'Peter',
                'last_name' => 'Wekesa',
                'phone' => '+254700000004',
                'password' => Hash::make('password123'),
                'user_type' => 'partner',
                'approval_status' => 'approved',
                'status' => 'active',
                'approved_at' => now(),
            ]
        );

        // Create partner record
        $partner = Partner::updateOrCreate(
            ['user_id' => $partnerUser->id],
            [
                'name' => 'Mumias Vipers Partner',
                'company_name' => 'Mumias Vipers Sports Academy',
                'industry' => 'Sports',
                'company_website' => 'https://mumiasvipers.com',
                'company_description' => 'Official partner of Mumias Vipers Academy',
                'contact_person_name' => 'Peter Wekesa',
                'contact_person_phone' => '+254700000004',
                'status' => 'active',
            ]
        );
        $this->command->info('✓ Created: partner@mumiasvipers.com (Partner)');

        // 5. Player Guardian/Parent Account
        $guardianUser = User::updateOrCreate(
            ['email' => 'guardian@mumiasvipers.com'],
            [
                'name' => 'Parent Guardian',
                'first_name' => 'Mary',
                'last_name' => 'Atieno',
                'phone' => '+254700000005',
                'password' => Hash::make('password123'),
                'user_type' => 'player',
                'approval_status' => 'approved',
                'status' => 'active',
                'approved_at' => now(),
            ]
        );
        $parentRole = Role::where('slug', 'parent')->first();
        if ($parentRole && !$guardianUser->hasRole('parent')) {
            $guardianUser->roles()->attach($parentRole->id);
        }

        // Create guardian record
        Guardian::updateOrCreate(
            ['user_id' => $guardianUser->id],
            [
                'first_name' => 'Mary',
                'last_name' => 'Atieno',
                'phone' => '+254700000005',
                'email' => 'guardian@mumiasvipers.com',
                'relationship' => 'Mother',
                'address' => 'Mumias, Kakamega County',
                'is_primary' => true,
            ]
        );
        $this->command->info('✓ Created: guardian@mumiasvipers.com (Parent/Guardian)');

        // 6. Additional Assistant Coach Account
        $assistantCoach = User::updateOrCreate(
            ['email' => 'assistantcoach@mumiasvipers.com'],
            [
                'name' => 'Assistant Coach',
                'first_name' => 'James',
                'last_name' => 'Kiprotich',
                'phone' => '+254700000006',
                'password' => Hash::make('password123'),
                'user_type' => 'staff',
                'approval_status' => 'approved',
                'status' => 'active',
                'approved_at' => now(),
            ]
        );
        $assistantCoachRole = Role::where('slug', 'assistant-coach')->first();
        if ($assistantCoachRole && !$assistantCoach->hasRole('assistant-coach')) {
            $assistantCoach->roles()->attach($assistantCoachRole->id);
        }

        Staff::updateOrCreate(
            ['user_id' => $assistantCoach->id],
            [
                'position' => 'Assistant Coach',
                'department' => 'Football',
                'employee_id' => 'COACH-002',
                'hire_date' => now()->subMonths(3),
                'is_active' => true,
            ]
        );
        $this->command->info('✓ Created: assistantcoach@mumiasvipers.com (Assistant Coach)');

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Test Accounts Created Successfully!');
        $this->command->info('========================================');
        $this->command->info('All accounts use password: password123');
        $this->command->info('');
        $this->command->info('Account Summary:');
        $this->command->info('  1. admin@mumiasvipers.com - Super Admin (full system access)');
        $this->command->info('  2. manager@mumiasvipers.com - Academy Manager (org-admin)');
        $this->command->info('  3. coach@mumiasvipers.com - Head Coach');
        $this->command->info('  4. assistantcoach@mumiasvipers.com - Assistant Coach');
        $this->command->info('  5. partner@mumiasvipers.com - Partner Organization');
        $this->command->info('  6. guardian@mumiasvipers.com - Parent/Guardian');
        $this->command->info('');
    }
}
