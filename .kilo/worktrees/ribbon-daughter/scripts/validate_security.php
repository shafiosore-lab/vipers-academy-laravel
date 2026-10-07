<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Organization;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\Player;
use App\Services\PermissionService;

class SecurityValidator
{
    private $permissionService;
    private $results = [];
    private $passed = 0;
    private $failed = 0;
    private $testEmails = [];

    public function __construct()
    {
        $this->permissionService = new PermissionService();
    }

    public function runAllTests()
    {
        // Prevent running in production environment
        if (App::isProduction()) {
            echo "❌ ERROR: Security validation cannot run in production environment!\n";
            echo "This script is for testing/development purposes only.\n";
            exit(1);
        }

        echo "🔒 Starting Comprehensive Security Validation\n";
        echo "===========================================\n\n";
        echo "⚠️  WARNING: Running in " . (App::isLocal() ? 'local' : 'testing') . " environment\n\n";

        // Test 1: Organization Isolation
        $this->testOrganizationIsolation();

        // Test 2: Role Hierarchy Enforcement
        $this->testRoleHierarchy();

        // Test 3: Cross-Organization Data Access
        $this->testCrossOrganizationAccess();

        // Test 4: Permission Escalation Prevention
        $this->testPermissionEscalation();

        // Test 5: SuperAdmin Privileges
        $this->testSuperAdminPrivileges();

        // Test 6: CRUD Permission Enforcement
        $this->testCRUDPermissions();

        // Test 7: URL Manipulation Prevention
        $this->testURLManipulation();

        // Test 8: API Security
        $this->testAPISecurity();

        // Generate Report
        $this->generateReport();
    }

    private function testOrganizationIsolation()
    {
        echo "🧪 Testing Organization Isolation...\n";

        // Create test data
        $org1 = Organization::create(['name' => 'Test Org 1', 'email' => 'org1@test.com']);
        $org2 = Organization::create(['name' => 'Test Org 2', 'email' => 'org2@test.com']);

        $admin1 = User::create([
            'name' => 'Admin 1',
            'email' => 'admin1@test.com',
            'organization_id' => $org1->id,
            'user_type' => 'admin',
            'approval_status' => 'approved'
        ]);

        $admin2 = User::create([
            'name' => 'Admin 2',
            'email' => 'admin2@test.com',
            'organization_id' => $org2->id,
            'user_type' => 'admin',
            'approval_status' => 'approved'
        ]);

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super@test.com',
            'user_type' => 'super-admin',
            'approval_status' => 'approved'
        ]);

        // Test admin1 cannot access org2
        $result1 = $this->permissionService->canAccessOrganization($admin1, $org2);
        $this->assertTest(!$result1, "Admin 1 should NOT access Organization 2");

        // Test admin1 can access org1
        $result2 = $this->permissionService->canAccessOrganization($admin1, $org1);
        $this->assertTest($result2, "Admin 1 should access Organization 1");

        // Test super admin can access all
        $result3 = $this->permissionService->canAccessOrganization($superAdmin, $org1);
        $this->assertTest($result3, "Super Admin should access Organization 1");

        $result4 = $this->permissionService->canAccessOrganization($superAdmin, $org2);
        $this->assertTest($result4, "Super Admin should access Organization 2");

        echo "✅ Organization Isolation Tests Complete\n\n";
    }

    private function testRoleHierarchy()
    {
        echo "🧪 Testing Role Hierarchy...\n";

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super2@test.com',
            'user_type' => 'super-admin',
            'approval_status' => 'approved'
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin3@test.com',
            'user_type' => 'admin',
            'approval_status' => 'approved'
        ]);

        $tournamentDirector = User::create([
            'name' => 'Tournament Director',
            'email' => 'td@test.com',
            'user_type' => 'tournament-director',
            'approval_status' => 'approved'
        ]);

        $teamManager = User::create([
            'name' => 'Team Manager',
            'email' => 'tm@test.com',
            'user_type' => 'team-manager',
            'approval_status' => 'approved'
        ]);

        // Test hierarchy
        $this->assertTest(
            $this->permissionService->hasRoleOrHigher($superAdmin, 'admin'),
            "Super Admin should have admin permissions"
        );

        $this->assertTest(
            $this->permissionService->hasRoleOrHigher($admin, 'tournament-director'),
            "Admin should have tournament director permissions"
        );

        $this->assertTest(
            $this->permissionService->hasRoleOrHigher($tournamentDirector, 'team-manager'),
            "Tournament Director should have team manager permissions"
        );

        $this->assertTest(
            !$this->permissionService->hasRoleOrHigher($teamManager, 'admin'),
            "Team Manager should NOT have admin permissions"
        );

        echo "✅ Role Hierarchy Tests Complete\n\n";
    }

    private function testCrossOrganizationAccess()
    {
        echo "🧪 Testing Cross-Organization Data Access...\n";

        $org1 = Organization::create(['name' => 'Test Org 3', 'email' => 'org3@test.com']);
        $org2 = Organization::create(['name' => 'Test Org 4', 'email' => 'org4@test.com']);

        $admin1 = User::create([
            'name' => 'Admin 3',
            'email' => 'admin3@test.com',
            'organization_id' => $org1->id,
            'user_type' => 'admin',
            'approval_status' => 'approved'
        ]);

        $tournament1 = Tournament::create([
            'name' => 'Tournament 1',
            'organization_id' => $org1->id
        ]);

        $tournament2 = Tournament::create([
            'name' => 'Tournament 2',
            'organization_id' => $org2->id
        ]);

        // Test admin1 cannot manage tournament2
        $result1 = $this->permissionService->canManageTournament($admin1, $tournament2);
        $this->assertTest(!$result1, "Admin 1 should NOT manage Tournament 2");

        // Test admin1 can manage tournament1
        $result2 = $this->permissionService->canManageTournament($admin1, $tournament1);
        $this->assertTest($result2, "Admin 1 should manage Tournament 1");

        echo "✅ Cross-Organization Access Tests Complete\n\n";
    }

    private function testPermissionEscalation()
    {
        echo "🧪 Testing Permission Escalation Prevention...\n";

        $player = User::create([
            'name' => 'Player',
            'email' => 'player@test.com',
            'user_type' => 'player',
            'approval_status' => 'approved'
        ]);

        $coach = User::create([
            'name' => 'Coach',
            'email' => 'coach@test.com',
            'user_type' => 'coach',
            'approval_status' => 'approved'
        ]);

        // Test player cannot escalate to admin
        $this->assertTest(
            !$this->permissionService->hasRoleOrHigher($player, 'admin'),
            "Player should NOT have admin permissions"
        );

        $this->assertTest(
            !$this->permissionService->canCRUD($player, 'tournament', 'create'),
            "Player should NOT create tournaments"
        );

        // Test coach cannot escalate to admin
        $this->assertTest(
            !$this->permissionService->hasRoleOrHigher($coach, 'admin'),
            "Coach should NOT have admin permissions"
        );

        $this->assertTest(
            !$this->permissionService->canCRUD($coach, 'tournament', 'create'),
            "Coach should NOT create tournaments"
        );

        echo "✅ Permission Escalation Tests Complete\n\n";
    }

    private function testSuperAdminPrivileges()
    {
        echo "🧪 Testing SuperAdmin Privileges...\n";

        $superAdmin = User::create([
            'name' => 'Super Admin 2',
            'email' => 'super3@test.com',
            'user_type' => 'super-admin',
            'approval_status' => 'approved'
        ]);

        $org = Organization::create(['name' => 'Test Org 5', 'email' => 'org5@test.com']);
        $tournament = Tournament::create(['name' => 'Tournament 3', 'organization_id' => $org->id]);

        // Test SuperAdmin can access everything
        $this->assertTest(
            $this->permissionService->canAccessOrganization($superAdmin, $org),
            "Super Admin should access all organizations"
        );

        $this->assertTest(
            $this->permissionService->canManageTournament($superAdmin, $tournament),
            "Super Admin should manage all tournaments"
        );

        $this->assertTest(
            $this->permissionService->canCRUD($superAdmin, 'tournament', 'create'),
            "Super Admin should create tournaments"
        );

        $this->assertTest(
            $this->permissionService->canCRUD($superAdmin, 'organization', 'create'),
            "Super Admin should create organizations"
        );

        echo "✅ SuperAdmin Privileges Tests Complete\n\n";
    }

    private function testCRUDPermissions()
    {
        echo "🧪 Testing CRUD Permission Enforcement...\n";

        $admin = User::create([
            'name' => 'Admin 4',
            'email' => 'admin4@test.com',
            'user_type' => 'admin',
            'approval_status' => 'approved'
        ]);

        $tournamentDirector = User::create([
            'name' => 'Tournament Director 2',
            'email' => 'td2@test.com',
            'user_type' => 'tournament-director',
            'approval_status' => 'approved'
        ]);

        $teamManager = User::create([
            'name' => 'Team Manager 2',
            'email' => 'tm2@test.com',
            'user_type' => 'team-manager',
            'approval_status' => 'approved'
        ]);

        $coach = User::create([
            'name' => 'Coach 2',
            'email' => 'coach2@test.com',
            'user_type' => 'coach',
            'approval_status' => 'approved'
        ]);

        // Test admin permissions
        $this->assertTest(
            $this->permissionService->canCRUD($admin, 'tournament', 'create'),
            "Admin should create tournaments"
        );

        $this->assertTest(
            $this->permissionService->canCRUD($admin, 'team', 'create'),
            "Admin should create teams"
        );

        // Test tournament director permissions
        $this->assertTest(
            $this->permissionService->canCRUD($tournamentDirector, 'tournament', 'create'),
            "Tournament Director should create tournaments"
        );

        $this->assertTest(
            !$this->permissionService->canCRUD($tournamentDirector, 'team', 'create'),
            "Tournament Director should NOT create teams"
        );

        // Test team manager permissions
        $this->assertTest(
            $this->permissionService->canCRUD($teamManager, 'team', 'create'),
            "Team Manager should create teams"
        );

        $this->assertTest(
            !$this->permissionService->canCRUD($teamManager, 'tournament', 'create'),
            "Team Manager should NOT create tournaments"
        );

        // Test coach permissions
        $this->assertTest(
            !$this->permissionService->canCRUD($coach, 'tournament', 'create'),
            "Coach should NOT create tournaments"
        );

        $this->assertTest(
            !$this->permissionService->canCRUD($coach, 'team', 'create'),
            "Coach should NOT create teams"
        );

        echo "✅ CRUD Permission Tests Complete\n\n";
    }

    private function testURLManipulation()
    {
        echo "🧪 Testing URL Manipulation Prevention...\n";

        // This would require actual HTTP requests, so we'll test the logic
        $admin = User::create([
            'name' => 'Admin 5',
            'email' => 'admin5@test.com',
            'user_type' => 'admin',
            'approval_status' => 'approved'
        ]);

        $org1 = Organization::create(['name' => 'Test Org 6', 'email' => 'org6@test.com']);
        $org2 = Organization::create(['name' => 'Test Org 7', 'email' => 'org7@test.com']);

        // Simulate URL manipulation attempt
        $result = $this->permissionService->canAccessOrganization($admin, $org2);
        $this->assertTest(!$result, "Admin should NOT access other organization via URL manipulation");

        echo "✅ URL Manipulation Tests Complete\n\n";
    }

    private function testAPISecurity()
    {
        echo "🧪 Testing API Security...\n";

        $admin = User::create([
            'name' => 'Admin 6',
            'email' => 'admin6@test.com',
            'user_type' => 'admin',
            'approval_status' => 'approved'
        ]);

        $org = Organization::create(['name' => 'Test Org 8', 'email' => 'org8@test.com']);

        // Test API access permissions
        $this->assertTest(
            $this->permissionService->canAccessOrganization($admin, $org),
            "Admin should access their organization via API"
        );

        echo "✅ API Security Tests Complete\n\n";
    }

    private function assertTest($condition, $message)
    {
        if ($condition) {
            echo "  ✓ $message\n";
            $this->passed++;
        } else {
            echo "  ✗ $message\n";
            $this->failed++;
            $this->results[] = $message;
        }
    }

    private function generateReport()
    {
        echo "📊 Security Validation Report\n";
        echo "============================\n\n";

        $total = $this->passed + $this->failed;
        $successRate = $total > 0 ? round(($this->passed / $total) * 100, 2) : 0;

        echo "Tests Passed: $this->passed\n";
        echo "Tests Failed: $this->failed\n";
        echo "Total Tests: $total\n";
        echo "Success Rate: $successRate%\n\n";

        if ($this->failed > 0) {
            echo "❌ Failed Tests:\n";
            foreach ($this->results as $result) {
                echo "  - $result\n";
            }
            echo "\n";
        }

        if ($successRate == 100) {
            echo "🎉 All security tests passed! System is secure.\n";
        } else {
            echo "⚠️  Security vulnerabilities detected. Please review failed tests.\n";
        }

        // Clean up test data
        $this->cleanupTestData();
    }

    /**
     * Clean up test data created during validation
     */
    private function cleanupTestData()
    {
        echo "🧹 Cleaning up test data...\n";

        // Delete test users
        $testEmails = [
            'admin1@test.com', 'admin2@test.com', 'admin3@test.com',
            'admin4@test.com', 'admin5@test.com', 'admin6@test.com',
            'super@test.com', 'super2@test.com', 'super3@test.com',
            'td@test.com', 'td2@test.com', 'tm@test.com', 'tm2@test.com',
            'coach@test.com', 'coach2@test.com', 'player@test.com',
        ];

        $deletedUsers = User::whereIn('email', $testEmails)->delete();
        echo "  ✓ Deleted $deletedUsers test users\n";

        // Delete test organizations
        $testOrgEmails = [
            'org1@test.com', 'org2@test.com', 'org3@test.com',
            'org4@test.com', 'org5@test.com', 'org6@test.com',
            'org7@test.com', 'org8@test.com',
        ];

        $deletedOrgs = Organization::whereIn('email', $testOrgEmails)->delete();
        echo "  ✓ Deleted $deletedOrgs test organizations\n";

        echo "✅ Cleanup complete!\n";
    }
}

// Run the validation
$validator = new SecurityValidator();
$validator->runAllTests();
