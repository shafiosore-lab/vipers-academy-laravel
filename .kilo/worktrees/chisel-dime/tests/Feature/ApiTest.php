<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Tournament;
use App\Models\Player;
use App\Models\Team;
use App\Models\TournamentTeam;
use App\Models\TournamentMatch;
use App\Models\AgeAlertRule;
use App\Models\DisciplinaryCase;
use App\Models\Appeal;
use App\Models\Protest;
use App\Models\PlayerSuspension;

class ApiTest extends TestCase
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
        $this->adminUser->assignRole('admin');

        // Create test data
        $this->player = Player::factory()->create([
            'organization_id' => $this->organization->id,
        ]);

        $this->team = Team::factory()->create([
            'organization_id' => $this->organization->id,
        ]);

        $this->tournament = Tournament::factory()->create([
            'organization_id' => $this->organization->id,
        ]);

        $this->match = TournamentMatch::factory()->create([
            'tournament_id' => $this->tournament->id,
            'home_team_id' => $this->team->id,
        ]);

        $this->disciplinaryCase = DisciplinaryCase::factory()->create([
            'organization_id' => $this->organization->id,
            'player_id' => $this->player->id,
        ]);
    }

    /**
     * Test age verification API endpoints
     */
    public function test_age_verification_api_endpoints()
    {
        $this->actingAs($this->adminUser);

        // Test GET player status
        $response = $this->getJson("/api/governance/age-verification/players/{$this->player->id}/status");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'is_verified',
            'verification_date',
            'needs_verification',
            'verification_flagged_at',
            'verification_flagged_by',
            'age_alerts',
            'verification_history',
        ]);

        // Test GET active rules
        $response = $this->getJson("/api/governance/age-verification/rules/{$this->organization->id}/active");
        $response->assertStatus(200);
        $response->assertJson([]);

        // Test POST verify player
        $response = $this->postJson("/api/governance/age-verification/players/{$this->player->id}/verify", [
            'verification_date' => now()->toDateString(),
            'verified_by' => 'Admin User',
            'verification_method' => 'Document Review',
            'notes' => 'Age verified successfully',
            'is_verified' => true,
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Player verification recorded successfully.',
        ]);
    }

    /**
     * Test disciplinary API endpoints
     */
    public function test_disciplinary_api_endpoints()
    {
        $this->actingAs($this->adminUser);

        // Test GET case status
        $response = $this->getJson("/api/governance/disciplinary/cases/{$this->disciplinaryCase->id}/status");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'case_number',
            'status',
            'decision',
            'decision_date',
            'review_started_at',
            'review_started_by',
            'has_suspension',
            'suspension_details',
            'has_appeal',
            'appeal_details',
        ]);

        // Test GET player history
        $response = $this->getJson("/api/governance/disciplinary/players/{$this->player->id}/history");
        $response->assertStatus(200);
        $response->assertJson([]);

        // Test GET active suspensions
        $response = $this->getJson('/api/governance/disciplinary/suspensions/active');
        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /**
     * Test appeals API endpoints
     */
    public function test_appeals_api_endpoints()
    {
        $this->actingAs($this->adminUser);

        // Test GET case appeals
        $response = $this->getJson("/api/governance/appeals/cases/{$this->disciplinaryCase->id}/appeals");
        $response->assertStatus(200);
        $response->assertJson([]);

        // Test GET player appeals
        $response = $this->getJson("/api/governance/appeals/players/{$this->player->id}/appeals");
        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /**
     * Test protests API endpoints
     */
    public function test_protests_api_endpoints()
    {
        $this->actingAs($this->adminUser);

        // Test GET match protests
        $response = $this->getJson("/api/governance/protests/matches/{$this->match->id}/protests");
        $response->assertStatus(200);
        $response->assertJson([]);

        // Test GET team protests
        $response = $this->getJson("/api/governance/protests/teams/{$this->team->id}/protests");
        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /**
     * Test API authentication requirements
     */
    public function test_api_authentication_required()
    {
        // Test unauthenticated access
        $response = $this->getJson("/api/governance/age-verification/players/{$this->player->id}/status");
        $response->assertStatus(401);

        $response = $this->postJson("/api/governance/age-verification/players/{$this->player->id}/verify", []);
        $response->assertStatus(401);
    }

    /**
     * Test API validation
     */
    public function test_api_validation()
    {
        $this->actingAs($this->adminUser);

        // Test invalid player verification data
        $response = $this->postJson("/api/governance/age-verification/players/{$this->player->id}/verify", [
            'verification_date' => 'invalid-date',
            'verified_by' => '', // Too short
            'verification_method' => '', // Too short
            'notes' => '', // Too short
            'is_verified' => 'not-boolean',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'verification_date',
            'verified_by',
            'verification_method',
            'notes',
            'is_verified',
        ]);
    }

    /**
     * Test API authorization (organization isolation)
     */
    public function test_api_authorization_organization_isolation()
    {
        // Create another organization and user
        $otherOrganization = Organization::factory()->create();
        $otherUser = User::factory()->create([
            'organization_id' => $otherOrganization->id,
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ]);
        $otherUser->assignRole('admin');

        $this->actingAs($otherUser);

        // Try to access player from different organization
        $response = $this->getJson("/api/governance/age-verification/players/{$this->player->id}/status");
        $response->assertStatus(403);
    }

    /**
     * Test API response format
     */
    public function test_api_response_format()
    {
        $this->actingAs($this->adminUser);

        // Test successful response format
        $response = $this->getJson("/api/governance/age-verification/players/{$this->player->id}/status");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonStructure([
            'is_verified',
            'verification_date',
            'needs_verification',
            'verification_flagged_at',
            'verification_flagged_by',
            'age_alerts',
            'verification_history',
        ]);

        // Test error response format
        $response = $this->postJson("/api/governance/age-verification/players/999999/verify", []);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors',
        ]);
    }

    /**
     * Test API rate limiting (if implemented)
     */
    public function test_api_rate_limiting()
    {
        $this->actingAs($this->adminUser);

        // Make multiple requests rapidly
        for ($i = 0; $i < 10; $i++) {
            $response = $this->getJson("/api/governance/age-verification/players/{$this->player->id}/status");
            $response->assertStatus(200);
        }
    }

    /**
     * Test API with different user roles
     */
    public function test_api_with_different_user_roles()
    {
        // Test with super admin
        $superAdmin = User::factory()->create([
            'user_type' => 'super-admin',
            'approval_status' => 'approved',
        ]);
        $superAdmin->assignRole('super-admin');

        $this->actingAs($superAdmin);

        $response = $this->getJson("/api/governance/age-verification/players/{$this->player->id}/status");
        $response->assertStatus(200);

        // Test with regular user (should fail)
        $regularUser = User::factory()->create([
            'organization_id' => $this->organization->id,
            'user_type' => 'player',
            'approval_status' => 'approved',
        ]);

        $this->actingAs($regularUser);

        $response = $this->getJson("/api/governance/age-verification/players/{$this->player->id}/status");
        $response->assertStatus(403);
    }
}
