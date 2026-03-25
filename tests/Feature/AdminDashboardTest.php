<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Tournament;
use App\Models\Role;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test organization
        $this->organization = Organization::factory()->create();

        // Create admin user
        $this->adminUser = User::factory()->create([
            'organization_id' => $this->organization->id,
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ]);

        // Assign admin role
        $this->adminUser->assignRole('admin');
    }

    /**
     * Test admin dashboard loads successfully
     */
    public function test_admin_dashboard_loads()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard.index');
    }

    /**
     * Test all dashboard quick action buttons are accessible
     */
    public function test_dashboard_quick_actions_accessible()
    {
        $this->actingAs($this->adminUser);

        // Test New Tournament button
        $response = $this->get(route('admin.tournaments.create'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.tournaments.create');

        // Test All Tournaments button
        $response = $this->get(route('admin.tournaments.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.tournaments.index');

        // Test Tournament Overview button
        $response = $this->get(route('admin.tournaments.overview'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.tournaments.overview');

        // Test Matches button
        $response = $this->get(route('admin.tournaments.matches'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.tournaments.matches');

        // Test Venues button
        $response = $this->get(route('admin.tournaments.venues'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.tournaments.venues');

        // Test Teams button
        $response = $this->get(route('admin.tournaments.teams'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.tournaments.teams');

        // Test Smart Tournament Engine button
        $response = $this->get(route('admin.tournaments.engine'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.tournaments.engine');

        // Test Match Scheduler button
        $response = $this->get(route('admin.tournaments.scheduler'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.tournaments.scheduler');
    }

    /**
     * Test dashboard displays correct tournament statistics
     */
    public function test_dashboard_displays_tournament_statistics()
    {
        // Create test tournaments
        Tournament::factory()->count(5)->create([
            'organization_id' => $this->organization->id,
            'status' => 'open'
        ]);

        Tournament::factory()->count(3)->create([
            'organization_id' => $this->organization->id,
            'status' => 'ongoing'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('stats');

        $stats = $response->viewData('stats');
        $this->assertEquals(8, $stats['total_tournaments']);
        $this->assertEquals(5, $stats['open_tournaments']);
        $this->assertEquals(3, $stats['ongoing_tournaments']);
    }

    /**
     * Test dashboard displays recent tournaments
     */
    public function test_dashboard_displays_recent_tournaments()
    {
        // Create test tournaments
        $tournaments = Tournament::factory()->count(3)->create([
            'organization_id' => $this->organization->id,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('recentTournaments');

        $recentTournaments = $response->viewData('recentTournaments');
        $this->assertCount(3, $recentTournaments);
    }

    /**
     * Test dashboard requires authentication
     */
    public function test_dashboard_requires_authentication()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect('/login');
    }

    /**
     * Test dashboard requires admin role
     */
    public function test_dashboard_requires_admin_role()
    {
        // Create regular user without admin role
        $regularUser = User::factory()->create([
            'organization_id' => $this->organization->id,
            'user_type' => 'player',
        ]);

        $response = $this->actingAs($regularUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    /**
     * Test dashboard quick actions have correct route parameters
     */
    public function test_dashboard_quick_actions_have_correct_parameters()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Check that the view contains the correct route names
        $response->assertSee('route("admin.tournaments.create")');
        $response->assertSee('route("admin.tournaments.index")');
        $response->assertSee('route("admin.tournaments.overview")');
    }

    /**
     * Test dashboard navigation links work correctly
     */
    public function test_dashboard_navigation_links()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        // Test that navigation links are present
        $response->assertSee('New Tournament');
        $response->assertSee('All Tournaments');
        $response->assertSee('Tournament Overview');
        $response->assertSee('Matches');
        $response->assertSee('Venues');
        $response->assertSee('Teams');
        $response->assertSee('Smart Tournament Engine');
        $response->assertSee('Match Scheduler');
    }
}
