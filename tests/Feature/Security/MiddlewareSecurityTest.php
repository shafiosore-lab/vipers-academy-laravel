<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class MiddlewareSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test roles and permissions
        $this->createTestRolesAndPermissions();
    }

    protected function createTestRolesAndPermissions()
    {
        // Create roles
        $superAdmin = Role::create(['name' => 'super-admin', 'slug' => 'super-admin']);
        $orgAdmin = Role::create(['name' => 'org-admin', 'slug' => 'org-admin']);
        $coach = Role::create(['name' => 'coach', 'slug' => 'coach']);
        $player = Role::create(['name' => 'player', 'slug' => 'player']);

        // Create permissions
        $createPlayers = Permission::create(['name' => 'Create Players', 'slug' => 'create_players']);
        $viewTournaments = Permission::create(['name' => 'View Tournaments', 'slug' => 'view_tournaments']);
        $manageFinance = Permission::create(['name' => 'Manage Finance', 'slug' => 'manage_finance']);

        // Assign permissions to roles
        $orgAdmin->permissions()->attach([$createPlayers->id, $viewTournaments->id]);
        $coach->permissions()->attach([$viewTournaments->id]);
    }

    public function test_role_middleware_blocks_unauthorized_access()
    {
        // Create a user without any roles
        $user = User::factory()->create();

        // Try to access admin route without proper role
        $response = $this->actingAs($user)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_role_middleware_allows_authorized_access()
    {
        // Create a user with org-admin role
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('slug', 'org-admin')->first()->id);

        // Try to access admin route with proper role
        $response = $this->actingAs($user)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_permission_middleware_blocks_unauthorized_access()
    {
        // Create a user with coach role (has view_tournaments but not create_players)
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('slug', 'coach')->first()->id);

        // Try to access route requiring create_players permission
        $response = $this->actingAs($user)
            ->get(route('admin.players.create'));

        $response->assertStatus(403);
    }

    public function test_permission_middleware_allows_authorized_access()
    {
        // Create a user with org-admin role (has create_players permission)
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('slug', 'org-admin')->first()->id);

        // Try to access route requiring create_players permission
        $response = $this->actingAs($user)
            ->get(route('admin.players.create'));

        $response->assertStatus(200);
    }

    public function test_super_admin_bypasses_all_permissions()
    {
        // Create a super admin user
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('slug', 'super-admin')->first()->id);

        // Super admin should be able to access any route
        $response = $this->actingAs($user)
            ->get(route('admin.players.create'));

        $response->assertStatus(200);
    }

    public function test_trial_user_no_longer_bypasses_security()
    {
        // Create a trial user
        $user = User::factory()->create([
            'is_on_trial' => true,
            'trial_ends_at' => now()->addDays(5)
        ]);

        // Trial user should NOT be able to access admin routes without proper roles
        $response = $this->actingAs($user)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_organization_user_no_longer_bypasses_security()
    {
        // Create a user with organization_id but no proper roles
        $user = User::factory()->create([
            'organization_id' => 1
        ]);

        // Organization user should NOT be able to access admin routes without proper roles
        $response = $this->actingAs($user)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_staff_user_no_longer_bypasses_security()
    {
        // Create a staff user without proper admin roles
        $user = User::factory()->create([
            'user_type' => 'staff'
        ]);

        // Staff user should NOT be able to access admin routes without proper roles
        $response = $this->actingAs($user)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_multiple_roles_work_correctly()
    {
        // Create a user with multiple roles
        $user = User::factory()->create();
        $orgAdminRole = Role::where('slug', 'org-admin')->first();
        $coachRole = Role::where('slug', 'coach')->first();
        $user->roles()->attach([$orgAdminRole->id, $coachRole->id]);

        // Should be able to access routes for either role
        $response = $this->actingAs($user)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_wildcard_permissions_work()
    {
        // Create a user with wildcard permission
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('slug', 'org-admin')->first()->id);

        // Should be able to access routes with wildcard permissions
        $response = $this->actingAs($user)
            ->get(route('admin.players.create'));

        $response->assertStatus(200);
    }

    public function test_ajax_requests_return_json_errors()
    {
        // Create a user without proper permissions
        $user = User::factory()->create();

        // Try to access API route without proper permissions
        $response = $this->actingAs($user)
            ->getJson('/api/players')
            ->assertStatus(403);

        $response->assertJson([
            'error' => 'Access Denied',
            'message' => 'You do not have permission to access this resource.'
        ]);
    }

    public function test_inertia_requests_return_inertia_errors()
    {
        // Create a user without proper permissions
        $user = User::factory()->create();

        // Try to access Inertia route without proper permissions
        $response = $this->actingAs($user)
            ->withHeaders(['X-Inertia' => 'true'])
            ->get('/admin/dashboard')
            ->assertStatus(403);

        $response->assertViewIs('Errors/Forbidden');
    }
}
