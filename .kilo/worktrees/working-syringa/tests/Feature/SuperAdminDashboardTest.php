<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Tournament;
use App\Models\Role;

class SuperAdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create multiple test organizations
        $this->organization1 = Organization::factory()->create();
        $this->organization2 = Organization::factory()->create();

        // Create super admin user
        $this->superAdminUser = User::factory()->create([
            'user_type' => 'super-admin',
            'approval_status' => 'approved',
        ]);

        // Assign super admin role
        $this->superAdminUser->assignRole('super-admin');
    }

    /**
     * Test super admin dashboard loads successfully
     */
    public function test_super_admin_dashboard_loads()
    {
        $response = $this->actingAs($this->superAdminUser)
            ->get(route('super-admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('super-admin.dashboard');
    }

    /**
     * Test all super admin dashboard quick action buttons are accessible
     */
    public function test_super_admin_dashboard_quick_actions_accessible()
    {
        $this->actingAs($this->superAdminUser);

        // Test Tournament Overview button
        $response = $this->get(route('super-admin.tournaments.overview'));
        $response->assertStatus(200);
        $response->assertViewIs('super-admin.tournaments.overview');

        // Test Website Content button
        $response = $this->get(route('super-admin.page-content.index'));
        $response->assertStatus(200);
        $response->assertViewIs('super-admin.page-content.index');

        // Test Tournaments button
        $response = $this->get(route('super-admin.tournaments.index'));
        $response->assertStatus(200);
        $response->assertViewIs('super-admin.tournaments.index');

        // Test Manage Organizations button
        $response = $this->get(route('super-admin.organizations.index'));
        $response->assertStatus(200);
        $response->assertViewIs('super-admin.organizations.index');

        // Test Role Management button
        $response = $this->get(route('super-admin.roles.index'));
        $response->assertStatus(200);
        $response->assertViewIs('super-admin.roles.index');

        // Test Subscription Plans button
        $response = $this->get(route('super-admin.plans.index'));
        $response->assertStatus(200);
        $response->assertViewIs('super-admin.plans.index');

        // Test Analytics button
        $response = $this->get(route('super-admin.analytics'));
        $response->assertStatus(200);
        $response->assertViewIs('super-admin.analytics');
    }

    /**
     * Test super admin dashboard displays multi-organization statistics
     */
    public function test_super_admin_dashboard_displays_multi_org_statistics()
    {
        // Create test tournaments across multiple organizations
        Tournament::factory()->count(3)->create([
            'organization_id' => $this->organization1->id,
            'status' => 'open'
        ]);

        Tournament::factory()->count(2)->create([
            'organization_id' => $this->organization2->id,
            'status' => 'ongoing'
        ]);

        $response = $this->actingAs($this->superAdminUser)
            ->get(route('super-admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('stats');

        $stats = $response->viewData('stats');
        $this->assertEquals(5, $stats['total_tournaments']);
        $this->assertEquals(3, $stats['open_tournaments']);
        $this->assertEquals(2, $stats['ongoing_tournaments']);
    }

    /**
     * Test super admin dashboard displays recent organizations
     */
    public function test_super_admin_dashboard_displays_recent_organizations()
    {
        $response = $this->actingAs($this->superAdminUser)
            ->get(route('super-admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('recentOrganizations');

        $recentOrganizations = $response->viewData('recentOrganizations');
        $this->assertCount(2, $recentOrganizations);
    }

    /**
     * Test super admin dashboard requires authentication
     */
    public function test_super_admin_dashboard_requires_authentication()
    {
        $response = $this->get(route('super-admin.dashboard'));
        $response->assertRedirect('/login');
    }

    /**
     * Test super admin dashboard requires super admin role
     */
    public function test_super_admin_dashboard_requires_super_admin_role()
    {
        // Create admin user without super admin role
        $adminUser = User::factory()->create([
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ]);
        $adminUser->assignRole('admin');

        $response = $this->actingAs($adminUser)
            ->get(route('super-admin.dashboard'));

        $response->assertStatus(403);
    }

    /**
     * Test super admin dashboard governance quick actions
     */
    public function test_super_admin_dashboard_governance_quick_actions()
    {
        $this->actingAs($this->superAdminUser);

        // Test Age Verification button
        $response = $this->get(route('super-admin.governance.age-verification.index'));
        $response->assertStatus(200);

        // Test Disciplinary Cases button
        $response = $this->get(route('super-admin.governance.disciplinary.index'));
        $response->assertStatus(200);

        // Test Appeals button
        $response = $this->get(route('super-admin.governance.appeals.index'));
        $response->assertStatus(200);

        // Test Protests button
        $response = $this->get(route('super-admin.governance.protests.index'));
        $response->assertStatus(200);
    }

    /**
     * Test super admin dashboard multi-organization access
     */
    public function test_super_admin_dashboard_multi_organization_access()
    {
        // Create tournaments in different organizations
        $org1Tournament = Tournament::factory()->create([
            'organization_id' => $this->organization1->id,
            'name' => 'Organization 1 Tournament'
        ]);

        $org2Tournament = Tournament::factory()->create([
            'organization_id' => $this->organization2->id,
            'name' => 'Organization 2 Tournament'
        ]);

        $response = $this->actingAs($this->superAdminUser)
            ->get(route('super-admin.dashboard'));

        $response->assertStatus(200);

        // Super admin should see tournaments from all organizations
        $response->assertSee('Organization 1 Tournament');
        $response->assertSee('Organization 2 Tournament');
    }

    /**
     * Test super admin dashboard quick actions have correct route parameters
     */
    public function test_super_admin_dashboard_quick_actions_have_correct_parameters()
    {
        $response = $this->actingAs($this->superAdminUser)
            ->get(route('super-admin.dashboard'));

        $response->assertStatus(200);

        // Check that the view contains the correct route names
        $response->assertSee('route("super-admin.tournaments.overview")');
        $response->assertSee('route("super-admin.page-content.index")');
        $response->assertSee('route("super-admin.tournaments.index")');
        $response->assertSee('route("super-admin.organizations.index")');
        $response->assertSee('route("super-admin.roles.index")');
        $response->assertSee('route("super-admin.plans.index")');
        $response->assertSee('route("super-admin.analytics")');
    }

    /**
     * Test super admin dashboard navigation links work correctly
     */
    public function test_super_admin_dashboard_navigation_links()
    {
        $response = $this->actingAs($this->superAdminUser)
            ->get(route('super-admin.dashboard'));

        // Test that navigation links are present
        $response->assertSee('Tournament Overview');
        $response->assertSee('Website Content');
        $response->assertSee('Tournaments');
        $response->assertSee('Manage Organizations');
        $response->assertSee('Role Management');
        $response->assertSee('Subscription Plans');
        $response->assertSee('Analytics');
    }
}
