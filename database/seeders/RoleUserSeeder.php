<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create all required roles if they don't exist
        $roles = [
            'super-admin' => ['name' => 'Super Admin', 'description' => 'Highest level administrator with full system access'],
            'marketing-admin' => ['name' => 'Marketing Admin', 'description' => 'Manages marketing and promotional activities'],
            'scouting-admin' => ['name' => 'Scouting Admin', 'description' => 'Oversees player scouting and recruitment'],
            'coaching-admin' => ['name' => 'Coaching Admin', 'description' => 'Manages coaching staff and training programs'],
            'finance-admin' => ['name' => 'Finance Admin', 'description' => 'Handles financial operations and reporting'],
            'operations-admin' => ['name' => 'Operations Admin', 'description' => 'Manages day-to-day operations'],
            'admin-operations' => ['name' => 'Admin Operations', 'description' => 'Administrative operations management'],
            'head-coach' => ['name' => 'Head Coach', 'description' => 'Lead coach responsible for all teams'],
            'assistant-coach' => ['name' => 'Assistant Coach', 'description' => 'Assistant to the head coach'],
            'coach' => ['name' => 'Coach', 'description' => 'Team coach'],
            'team-manager' => ['name' => 'Team Manager', 'description' => 'Manages team logistics and operations'],
            'media-officer' => ['name' => 'Media Officer', 'description' => 'Handles media and public relations'],
            'safeguarding-officer' => ['name' => 'Safeguarding Officer', 'description' => 'Ensures player welfare and safeguarding'],
            'finance-officer' => ['name' => 'Finance Officer', 'description' => 'Handles financial transactions and reporting'],
            'partner-marketing' => ['name' => 'Partner Marketing', 'description' => 'Partner organization marketing representative'],
            'partner-scouting' => ['name' => 'Partner Scouting', 'description' => 'Partner organization scouting representative'],
            'partner-operations' => ['name' => 'Partner Operations', 'description' => 'Partner organization operations representative'],
            'player' => ['name' => 'Player', 'description' => 'Academy player'],
            'parent' => ['name' => 'Parent', 'description' => 'Player parent/guardian'],
            'staff-base' => ['name' => 'Staff Base', 'description' => 'Base role for all staff members'],
            'visitor' => ['name' => 'Visitor', 'description' => 'Website visitor'],
        ];

        foreach ($roles as $slug => $data) {
            Role::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'type' => $this->getRoleType($slug),
                    'is_default' => $slug === 'staff-base',
                ]
            );
        }

        // Create users for each role
        $this->createUsersForRoles();
    }

    /**
     * Get role type based on slug
     */
    private function getRoleType($slug): string
    {
        if (str_contains($slug, 'admin')) {
            return 'admin';
        } elseif (str_contains($slug, 'partner') || str_contains($slug, 'coach') || str_contains($slug, 'manager') ||
                  str_contains($slug, 'media') || str_contains($slug, 'finance') || str_contains($slug, 'safeguarding') ||
                  $slug === 'staff-base') {
            return 'partner_staff';
        } elseif ($slug === 'player' || $slug === 'parent') {
            return 'player';
        } elseif ($slug === 'visitor') {
            // Since 'visitor' isn't in the enum, we'll default to 'player' for consistency
            return 'player';
        }

        return 'partner_staff';
    }

    /**
     * Create users for each role
     */
    private function createUsersForRoles(): void
    {
        $userData = [
            [
                'email' => 'superadmin@webviper.com',
                'name' => 'Super Admin',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['super-admin'],
            ],
            [
                'email' => 'admin@mumiasvipers.com',
                'name' => 'Mumias Vipers Admin',
                'first_name' => 'Mumias',
                'last_name' => 'Vipers Admin',
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['super-admin'],
            ],
            [
                'email' => 'marketingadmin@webviper.com',
                'name' => 'Marketing Admin',
                'first_name' => 'Marketing',
                'last_name' => 'Admin',
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['marketing-admin'],
            ],
            [
                'email' => 'scoutingadmin@webviper.com',
                'name' => 'Scouting Admin',
                'first_name' => 'Scouting',
                'last_name' => 'Admin',
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['scouting-admin'],
            ],
            [
                'email' => 'coachingadmin@webviper.com',
                'name' => 'Coaching Admin',
                'first_name' => 'Coaching',
                'last_name' => 'Admin',
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['coaching-admin'],
            ],
            [
                'email' => 'financeadmin@webviper.com',
                'name' => 'Finance Admin',
                'first_name' => 'Finance',
                'last_name' => 'Admin',
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['finance-admin'],
            ],
            [
                'email' => 'operationsadmin@webviper.com',
                'name' => 'Operations Admin',
                'first_name' => 'Operations',
                'last_name' => 'Admin',
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['operations-admin'],
            ],
            [
                'email' => 'adminoperations@webviper.com',
                'name' => 'Admin Operations',
                'first_name' => 'Admin',
                'last_name' => 'Operations',
                'user_type' => 'admin',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['admin-operations'],
            ],
            [
                'email' => 'headcoach@webviper.com',
                'name' => 'Head Coach',
                'first_name' => 'Head',
                'last_name' => 'Coach',
                'user_type' => 'staff',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['head-coach', 'staff-base'],
            ],
            [
                'email' => 'assistantcoach@webviper.com',
                'name' => 'Assistant Coach',
                'first_name' => 'Assistant',
                'last_name' => 'Coach',
                'user_type' => 'staff',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['assistant-coach', 'staff-base'],
            ],
            [
                'email' => 'coach@webviper.com',
                'name' => 'Coach',
                'first_name' => 'Team',
                'last_name' => 'Coach',
                'user_type' => 'staff',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['coach', 'staff-base'],
            ],
            [
                'email' => 'coach@mumiasvipers.com',
                'name' => 'Mumias Vipers Coach',
                'first_name' => 'Mumias',
                'last_name' => 'Vipers Coach',
                'user_type' => 'staff',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['coach', 'staff-base'],
            ],
            [
                'email' => 'teammanager@webviper.com',
                'name' => 'Team Manager',
                'first_name' => 'Team',
                'last_name' => 'Manager',
                'user_type' => 'staff',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['team-manager', 'staff-base'],
            ],
            [
                'email' => 'mediaofficer@webviper.com',
                'name' => 'Media Officer',
                'first_name' => 'Media',
                'last_name' => 'Officer',
                'user_type' => 'staff',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['media-officer', 'staff-base'],
            ],
            [
                'email' => 'safeguardingofficer@webviper.com',
                'name' => 'Safeguarding Officer',
                'first_name' => 'Safeguarding',
                'last_name' => 'Officer',
                'user_type' => 'staff',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['safeguarding-officer', 'staff-base'],
            ],
            [
                'email' => 'financeofficer@webviper.com',
                'name' => 'Finance Officer',
                'first_name' => 'Finance',
                'last_name' => 'Officer',
                'user_type' => 'staff',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['finance-officer', 'staff-base'],
            ],
            [
                'email' => 'partnermarketing@webviper.com',
                'name' => 'Partner Marketing',
                'first_name' => 'Partner',
                'last_name' => 'Marketing',
                'user_type' => 'partner',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['partner-marketing'],
            ],
            [
                'email' => 'partnerscouting@webviper.com',
                'name' => 'Partner Scouting',
                'first_name' => 'Partner',
                'last_name' => 'Scouting',
                'user_type' => 'partner',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['partner-scouting'],
            ],
            [
                'email' => 'partneroperations@webviper.com',
                'name' => 'Partner Operations',
                'first_name' => 'Partner',
                'last_name' => 'Operations',
                'user_type' => 'partner',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['partner-operations'],
            ],
            [
                'email' => 'player@webviper.com',
                'name' => 'Test Player',
                'first_name' => 'Test',
                'last_name' => 'Player',
                'user_type' => 'player',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['player'],
            ],
            [
                'email' => 'parent@webviper.com',
                'name' => 'Test Parent',
                'first_name' => 'Test',
                'last_name' => 'Parent',
                'user_type' => 'player',
                'approval_status' => 'approved',
                'status' => 'active',
                'password' => 'password',
                'roles' => ['parent'],
            ],

        ];

        foreach ($userData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'user_type' => $data['user_type'],
                    'approval_status' => $data['approval_status'],
                    'status' => $data['status'],
                    'password' => bcrypt($data['password']),
                ]
            );

            // Assign roles
            $user->roles()->sync(Role::whereIn('slug', $data['roles'])->pluck('id'));

            echo "Created/updated user: {$data['email']} with roles: " . implode(', ', $data['roles']) . "\n";
        }
    }
}
