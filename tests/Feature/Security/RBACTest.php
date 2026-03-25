<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\Player;
use App\Services\PermissionService;

class RBACTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test organizations
        $this->organization1 = Organization::factory()->create();
        $this->organization2 = Organization::factory()->create();

        // Create users for each role
        $this->superAdmin = User::factory()->create([
            'user_type' => 'super-admin',
            'approval_status' => 'approved',
        ]);
        $this->superAdmin->assignRole('super-admin');

        $this->admin1 = User::factory()->create([
            'organization_id' => $this->organization1->id,
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ]);
        $this->admin1->assignRole('admin');

        $this->admin2 = User::factory()->create([
            'organization_id' => $this->organization2->id,
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ]);
        $this->admin2->assignRole('admin');

        $this->tournamentDirector = User::factory()->create([
            'organization_id' => $this->organization1->id,
            'user_type' => 'tournament-director',
            'approval_status' => 'approved',
        ]);
        $this->tournamentDirector->assignRole('tournament-director');

        $this->teamManager = User::factory()->create([
            'organization_id' => $this->organization1->id,
            'user_type' => 'team-manager',
            'approval_status' => 'approved',
        ]);
        $this->teamManager->assignRole('team-manager');

        $this->coach = User::factory()->create([
            'organization_id' => $this->organization1->id,
            'user_type' => 'coach',
            'approval_status' => 'approved',
        ]);
        $this->coach->assignRole('coach');

        $this->player = User::factory()->create([
            'organization_id' => $this->organization1->id,
            'user_type' => 'player',
            'approval_status' => 'approved',
        ]);
        $this->player->assignRole('player');

        // Create test data
        $this->tournament1 = Tournament::factory()->create([
            'organization_id' => $this->organization1->id,
        ]);

        $this->tournament2 = Tournament::factory()->create([
            'organization_id' => $this->organization2->id,
        ]);

        $this->team1 = Team::factory()->create([
            'organization_id' => $this->organization1->id,
        ]);

        $this->team2 = Team::factory()->create([
            'organization_id' => $this->organization2->id,
        ]);

        $this->playerModel = Player::factory()->create([
            'organization_id' => $this->organization1->id,
            'user_id' => $this->player->id,
        ]);
    }

    /**
     * Test SuperAdmin has full system access
     */
    public function test_super_admin_full_access()
    {
        $this->actingAs($this->superAdmin);

        // SuperAdmin should access all organizations
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);

        // SuperAdmin should access all tournaments
        $response = $this->get("/admin/tournaments/{$this->tournament1->id}");
        $response->assertStatus(200);

        $response = $this->get("/admin/tournaments/{$this->tournament2->id}");
        $response->assertStatus(200);

        // SuperAdmin should access all teams
        $response = $this->get("/admin/teams/{$this->team1->id}");
        $response->assertStatus(200);

        $response = $this->get("/admin/teams/{$this->team2->id}");
        $response->assertStatus(200);

        // SuperAdmin should access all players
        $response = $this->get("/admin/players/{$this->playerModel->id}");
        $response->assertStatus(200);
    }

    /**
     * Test Admin role organization isolation
     */
    public function test_admin_organization_isolation()
    {
        $this->actingAs($this->admin1);

        // Admin1 should access their own organization
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);

        // Admin1 should access their own tournaments
        $response = $this->get("/admin/tournaments/{$this->tournament1->id}");
        $response->assertStatus(200);

        // Admin1 should NOT access other organization's tournaments
        $response = $this->get("/admin/tournaments/{$this->tournament2->id}");
        $response->assertStatus(403); // Forbidden

        // Admin1 should access their own teams
        $response = $this->get("/admin/teams/{$this->team1->id}");
        $response->assertStatus(200);

        // Admin1 should NOT access other organization's teams
        $response = $this->get("/admin/teams/{$this->team2->id}");
        $response->assertStatus(403); // Forbidden

        // Admin1 should access their own players
        $response = $this->get("/admin/players/{$this->playerModel->id}");
        $response->assertStatus(200);
    }

    /**
     * Test cross-organization data access prevention
     */
    public function test_cross_organization_data_isolation()
    {
        $this->actingAs($this->admin1);

        // Admin1 should only see their organization's tournaments
        $response = $this->get('/admin/tournaments');
        $response->assertStatus(200);
        $response->assertSee($this->tournament1->name);
        $response->assertDontSee($this->tournament2->name);

        // Admin1 should only see their organization's teams
        $response = $this->get('/admin/teams');
        $response->assertStatus(200);
        $response->assertSee($this->team1->name);
        $response->assertDontSee($this->team2->name);

        // Admin1 should only see their organization's players
        $response = $this->get('/admin/players');
        $response->assertStatus(200);
        $response->assertSee($this->playerModel->name);
    }

    /**
     * Test role hierarchy enforcement
     */
    public function test_role_hierarchy_enforcement()
    {
        $permissionService = new PermissionService();

        // SuperAdmin should have all permissions
        $this->assertTrue($permissionService->hasRoleOrHigher($this->superAdmin, 'admin'));
        $this->assertTrue($permissionService->hasRoleOrHigher($this->superAdmin, 'tournament-director'));
        $this->assertTrue($permissionService->hasRoleOrHigher($this->superAdmin, 'team-manager'));

        // Admin should have lower role permissions
        $this->assertTrue($permissionService->hasRoleOrHigher($this->admin1, 'tournament-director'));
        $this->assertTrue($permissionService->hasRoleOrHigher($this->admin1, 'team-manager'));
        $this->assertTrue($permissionService->hasRoleOrHigher($this->admin1, 'coach'));

        // Tournament Director should have specific permissions
        $this->assertTrue($permissionService->hasRoleOrHigher($this->tournamentDirector, 'team-manager'));
        $this->assertTrue($permissionService->hasRoleOrHigher($this->tournamentDirector, 'coach'));
        $this->assertFalse($permissionService->hasRoleOrHigher($this->tournamentDirector, 'admin'));

        // Team Manager should have limited permissions
        $this->assertTrue($permissionService->hasRoleOrHigher($this->teamManager, 'coach'));
        $this->assertFalse($permissionService->hasRoleOrHigher($this->teamManager, 'admin'));
        $this->assertFalse($permissionService->hasRoleOrHigher($this->teamManager, 'tournament-director'));
    }

    /**
     * Test CRUD permission enforcement
     */
    public function test_crud_permission_enforcement()
    {
        $permissionService = new PermissionService();

        // SuperAdmin can perform all CRUD operations
        $this->assertTrue($permissionService->canCRUD($this->superAdmin, 'tournament', 'create'));
        $this->assertTrue($permissionService->canCRUD($this->superAdmin, 'tournament', 'update'));
        $this->assertTrue($permissionService->canCRUD($this->superAdmin, 'tournament', 'delete'));

        // Admin can manage tournaments in their organization
        $this->assertTrue($permissionService->canCRUD($this->admin1, 'tournament', 'create'));
        $this->assertTrue($permissionService->canCRUD($this->admin1, 'tournament', 'update'));
        $this->assertTrue($permissionService->canCRUD($this->admin1, 'tournament', 'delete'));

        // Tournament Director can manage tournaments
        $this->assertTrue($permissionService->canCRUD($this->tournamentDirector, 'tournament', 'create'));
        $this->assertTrue($permissionService->canCRUD($this->tournamentDirector, 'tournament', 'update'));
        $this->assertTrue($permissionService->canCRUD($this->tournamentDirector, 'tournament', 'delete'));

        // Team Manager cannot manage tournaments
        $this->assertFalse($permissionService->canCRUD($this->teamManager, 'tournament', 'create'));
        $this->assertFalse($permissionService->canCRUD($this->teamManager, 'tournament', 'update'));
        $this->assertFalse($permissionService->canCRUD($this->teamManager, 'tournament', 'delete'));

        // Coach cannot manage tournaments
        $this->assertFalse($permissionService->canCRUD($this->coach, 'tournament', 'create'));
        $this->assertFalse($permissionService->canCRUD($this->coach, 'tournament', 'update'));
        $this->assertFalse($permissionService->canCRUD($this->coach, 'tournament', 'delete'));
    }

    /**
     * Test API endpoint security
     */
    public function test_api_endpoint_security()
    {
        // Test that API endpoints respect organization scoping
        $this->actingAs($this->admin1);

        // Admin1 should access their own organization's API data
        $response = $this->getJson("/api/tournaments/{$this->tournament1->id}");
        $response->assertStatus(200);

        // Admin1 should NOT access other organization's API data
        $response = $this->getJson("/api/tournaments/{$this->tournament2->id}");
        $response->assertStatus(403);

        // Test SuperAdmin API access
        $this->actingAs($this->superAdmin);
        $response = $this->getJson("/api/tournaments/{$this->tournament2->id}");
        $response->assertStatus(200);
    }

    /**
     * Test direct URL manipulation prevention
     */
    public function test_direct_url_manipulation_prevention()
    {
        $this->actingAs($this->admin1);

        // Admin1 should not be able to access other organization's data via URL manipulation
        $response = $this->get("/admin/tournaments/{$this->tournament2->id}/edit");
        $response->assertStatus(403);

        $response = $this->get("/admin/teams/{$this->team2->id}/edit");
        $response->assertStatus(403);

        $response = $this->get("/admin/players/{$this->playerModel->id}/edit");
        $response->assertStatus(200); // Should work for their own organization
    }

    /**
     * Test permission escalation prevention
     */
    public function test_permission_escalation_prevention()
    {
        // Test that lower roles cannot escalate to higher permissions
        $permissionService = new PermissionService();

        // Player should not have admin permissions
        $this->assertFalse($permissionService->hasRoleOrHigher($this->player, 'admin'));
        $this->assertFalse($permissionService->canCRUD($this->player, 'tournament', 'create'));
        $this->assertFalse($permissionService->canCRUD($this->player, 'team', 'create'));

        // Coach should not have admin permissions
        $this->assertFalse($permissionService->hasRoleOrHigher($this->coach, 'admin'));
        $this->assertFalse($permissionService->canCRUD($this->coach, 'tournament', 'create'));
        $this->assertFalse($permissionService->canCRUD($this->coach, 'team', 'create'));
    }

    /**
     * Test organization switching prevention
     */
    public function test_organization_switching_prevention()
    {
        $this->actingAs($this->admin1);

        // Admin1 should not be able to switch to other organizations
        $response = $this->post('/admin/organizations/switch', [
            'organization_id' => $this->organization2->id
        ]);
        $response->assertStatus(403);

        // Test that organization_id parameter manipulation is prevented
        $response = $this->get('/admin/tournaments?organization_id=' . $this->organization2->id);
        $response->assertStatus(200);

        // Should only show tournaments from admin1's organization
        $response->assertDontSee($this->tournament2->name);
    }

    /**
     * Test SuperAdmin bypass of all restrictions
     */
    public function test_super_admin_bypass_all_restrictions()
    {
        $this->actingAs($this->superAdmin);

        // SuperAdmin should access all organizations
        $response = $this->get('/admin/organizations');
        $response->assertStatus(200);
        $response->assertSee($this->organization1->name);
        $response->assertSee($this->organization2->name);

        // SuperAdmin should access all tournaments
        $response = $this->get('/admin/tournaments');
        $response->assertStatus(200);
        $response->assertSee($this->tournament1->name);
        $response->assertSee($this->tournament2->name);

        // SuperAdmin should access all teams
        $response = $this->get('/admin/teams');
        $response->assertStatus(200);
        $response->assertSee($this->team1->name);
        $response->assertSee($this->team2->name);

        // SuperAdmin should access all players
        $response = $this->get('/admin/players');
        $response->assertStatus(200);
        $response->assertSee($this->playerModel->name);
    }
}
