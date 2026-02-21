<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Player;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting comprehensive test user creation...');

        // ==================== ADMIN USERS ====================

        // 1. Super Admin - Full system control
        $superAdmin = $this->createUser([
            'name' => 'Super Admin',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@mumiasvipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ], ['super-admin']);
        $this->command->info("Created: Super Admin (super-admin)");

        // 2. Marketing Admin
        $marketingAdmin = $this->createUser([
            'name' => 'Marketing Admin',
            'first_name' => 'Marketing',
            'last_name' => 'Admin',
            'email' => 'marketing@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ], ['marketing-admin']);
        $this->command->info("Created: Marketing Admin (marketing-admin)");

        // 3. Scouting Admin
        $scoutingAdmin = $this->createUser([
            'name' => 'Scouting Admin',
            'first_name' => 'Scouting',
            'last_name' => 'Admin',
            'email' => 'scouting@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ], ['scouting-admin']);
        $this->command->info("Created: Scouting Admin (scouting-admin)");

        // 4. Operations Admin
        $operationsAdmin = $this->createUser([
            'name' => 'Operations Admin',
            'first_name' => 'Operations',
            'last_name' => 'Admin',
            'email' => 'operations@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ], ['operations-admin']);
        $this->command->info("Created: Operations Admin (operations-admin)");

        // 5. Coaching Admin
        $coachingAdmin = $this->createUser([
            'name' => 'Coaching Admin',
            'first_name' => 'Coaching',
            'last_name' => 'Admin',
            'email' => 'coaching@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ], ['coaching-admin']);
        $this->command->info("Created: Coaching Admin (coaching-admin)");

        // 6. Finance Admin
        $financeAdmin = $this->createUser([
            'name' => 'Finance Admin',
            'first_name' => 'Finance',
            'last_name' => 'Admin',
            'email' => 'finance@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ], ['finance-admin']);
        $this->command->info("Created: Finance Admin (finance-admin)");

        // 7. Admin/Operations Manager
        $adminOpsManager = $this->createUser([
            'name' => 'Admin Operations Manager',
            'first_name' => 'AdminOps',
            'last_name' => 'Manager',
            'email' => 'adminops@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ], ['admin-operations']);
        $this->command->info("Created: Admin Operations Manager (admin-operations)");

        // 8. Head Coach
        $headCoach = $this->createUser([
            'name' => 'Head Coach',
            'first_name' => 'Head',
            'last_name' => 'Coach',
            'email' => 'headcoach@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ], ['head-coach']);
        $this->command->info("Created: Head Coach (head-coach)");

        // 9. Safeguarding/Welfare Officer
        $safeguardingOfficer = $this->createUser([
            'name' => 'Safeguarding Officer',
            'first_name' => 'Safeguarding',
            'last_name' => 'Officer',
            'email' => 'safeguarding@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ], ['safeguarding-officer']);
        $this->command->info("Created: Safeguarding Officer (safeguarding-officer)");

        // 10. Finance Officer
        $financeOfficer = $this->createUser([
            'name' => 'Finance Officer',
            'first_name' => 'Finance',
            'last_name' => 'Officer',
            'email' => 'financeofficer@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ], ['finance-officer']);
        $this->command->info("Created: Finance Officer (finance-officer)");

        // 11. Media & Communications Officer
        $mediaOfficer = $this->createUser([
            'name' => 'Media Officer',
            'first_name' => 'Media',
            'last_name' => 'Officer',
            'email' => 'media@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ], ['media-officer']);
        $this->command->info("Created: Media Officer (media-officer)");

        // 12. Greenboys Academy Org Admin
        $greenboysOrg = $this->createUser([
            'name' => 'Greenboys Admin',
            'first_name' => 'Greenboys',
            'last_name' => 'Admin',
            'email' => 'greenboys@admin.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ], ['org-admin']);
        $this->command->info("Created: Greenboys Admin (org-admin)");

        // ==================== PARTNER STAFF USERS ====================

        // 12. Coach
        $coach = $this->createUser([
            'name' => 'Coach',
            'first_name' => 'Team',
            'last_name' => 'Coach',
            'email' => 'coach@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'staff',
            'approval_status' => 'approved',
        ], ['coach']);
        $this->command->info("Created: Coach (coach)");

        // 13. Partner Marketing
        $partnerMarketing = $this->createUser([
            'name' => 'Partner Marketing',
            'first_name' => 'Partner',
            'last_name' => 'Marketing',
            'email' => 'partnermarketing@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'staff',
            'approval_status' => 'approved',
        ], ['partner-marketing']);
        $this->command->info("Created: Partner Marketing (partner-marketing)");

        // 14. Partner Scouting
        $partnerScouting = $this->createUser([
            'name' => 'Partner Scouting',
            'first_name' => 'Partner',
            'last_name' => 'Scout',
            'email' => 'partnerscouting@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'staff',
            'approval_status' => 'approved',
        ], ['partner-scouting']);
        $this->command->info("Created: Partner Scouting (partner-scouting)");

        // 15. Partner Operations
        $partnerOperations = $this->createUser([
            'name' => 'Partner Operations',
            'first_name' => 'Partner',
            'last_name' => 'Operations',
            'email' => 'partneroperations@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'staff',
            'approval_status' => 'approved',
        ], ['partner-operations']);
        $this->command->info("Created: Partner Operations (partner-operations)");

        // 16. Assistant Coach
        $assistantCoach = $this->createUser([
            'name' => 'Assistant Coach',
            'first_name' => 'Assistant',
            'last_name' => 'Coach',
            'email' => 'assistantcoach@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'staff',
            'approval_status' => 'approved',
        ], ['assistant-coach']);
        $this->command->info("Created: Assistant Coach (assistant-coach)");

        // 17. Team Manager
        $teamManager = $this->createUser([
            'name' => 'Team Manager',
            'first_name' => 'Team',
            'last_name' => 'Manager',
            'email' => 'teammanager@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'staff',
            'approval_status' => 'approved',
        ], ['team-manager']);
        $this->command->info("Created: Team Manager (team-manager)");

        // ==================== PLAYER/PARENT USERS ====================

        // 18. Player
        $player = $this->createUser([
            'name' => 'Player User',
            'first_name' => 'Test',
            'last_name' => 'Player',
            'email' => 'player@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'player',
            'approval_status' => 'approved',
        ], ['player']);
        $this->command->info("Created: Player User (player)");

        // 19. Parent
        $parent = $this->createUser([
            'name' => 'Parent User',
            'first_name' => 'Test',
            'last_name' => 'Parent',
            'email' => 'parent@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'player',
            'approval_status' => 'approved',
        ], ['parent']);
        $this->command->info("Created: Parent User (parent)");

        // ==================== PARTNER USER ====================

        // 20. Partner
        $partner = $this->createUser([
            'name' => 'Partner User',
            'first_name' => 'Partner',
            'last_name' => 'Organization',
            'email' => 'partner@vipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'partner',
            'approval_status' => 'approved',
        ], []);
        $this->command->info("Created: Partner User (no role - partner type)");

        // ==================== SUMMARY ====================
        $totalUsers = User::count();
        $this->command->info("========================================");
        $this->command->info("Total test users created: {$totalUsers}");
        $this->command->info("Admin users: " . User::where('user_type', 'admin')->count());
        $this->command->info("Staff users: " . User::where('user_type', 'staff')->count());
        $this->command->info("Player users: " . User::where('user_type', 'player')->count());
        $this->command->info("Partner users: " . User::where('user_type', 'partner')->count());
        $this->command->info("========================================");
    }

    /**
     * Helper method to create a user and assign roles.
     */
    private function createUser(array $userData, array $roleSlugs): User
    {
        $user = User::create($userData);

        foreach ($roleSlugs as $slug) {
            $role = Role::where('slug', $slug)->first();
            if ($role) {
                $user->assignRole($role);
            }
        }

        return $user;
    }
}
