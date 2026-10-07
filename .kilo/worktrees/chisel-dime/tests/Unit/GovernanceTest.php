<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Player;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\AgeAlertRule;
use App\Models\PlayerAgeVerification;
use App\Models\DisciplinaryCase;
use App\Models\PlayerSuspension;
use App\Models\Appeal;
use App\Models\Protest;
use App\Services\GovernanceService;

class GovernanceTest extends TestCase
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

        $this->governanceService = new GovernanceService();
    }

    /**
     * Test age verification functionality
     */
    public function test_age_verification_process()
    {
        // Create age alert rule
        $rule = AgeAlertRule::create([
            'organization_id' => $this->organization->id,
            'name' => 'U18 Verification',
            'category' => 'youth',
            'min_age' => 16,
            'max_age' => 18,
            'alert_threshold_days' => 30,
            'is_active' => true,
            'auto_flag' => true,
        ]);

        // Test age verification recording
        $verification = $this->governanceService->recordAgeVerification(
            $this->player->id,
            true,
            'Admin User',
            'Document Review',
            'Age verified successfully'
        );

        $this->assertNotNull($verification);
        $this->assertTrue($verification->is_verified);
        $this->assertEquals('Admin User', $verification->verified_by);
        $this->assertEquals('Document Review', $verification->verification_method);

        // Test player age verification status
        $status = $this->governanceService->getPlayerAgeVerificationStatus($this->player->id);

        $this->assertTrue($status['is_verified']);
        $this->assertNotNull($status['verification_date']);
        $this->assertFalse($status['needs_verification']);
    }

    /**
     * Test disciplinary case management
     */
    public function test_disciplinary_case_management()
    {
        // Create disciplinary case
        $case = $this->governanceService->createDisciplinaryCase(
            $this->player->id,
            'Red Card',
            'Violent conduct during match',
            $this->match->id,
            'Referee Report',
            $this->adminUser->id
        );

        $this->assertNotNull($case);
        $this->assertEquals('Red Card', $case->incident_type);
        $this->assertEquals('Violent conduct during match', $case->description);
        $this->assertEquals('open', $case->status);

        // Test case status update
        $updatedCase = $this->governanceService->updateCaseStatus(
            $case->id,
            'under_review',
            $this->adminUser->id
        );

        $this->assertEquals('under_review', $updatedCase->status);
        $this->assertEquals($this->adminUser->id, $updatedCase->review_started_by);

        // Test suspension creation
        $suspension = $this->governanceService->createPlayerSuspension(
            $this->player->id,
            $case->id,
            now()->addDays(7),
            now()->addDays(21),
            3,
            'Violent conduct suspension'
        );

        $this->assertNotNull($suspension);
        $this->assertEquals(3, $suspension->matches);
        $this->assertEquals('Violent conduct suspension', $suspension->reason);
    }

    /**
     * Test appeal management
     */
    public function test_appeal_management()
    {
        // Create disciplinary case first
        $case = DisciplinaryCase::factory()->create([
            'organization_id' => $this->organization->id,
            'player_id' => $this->player->id,
            'status' => 'closed',
            'decision' => 'Suspended for 3 matches',
        ]);

        // Create appeal
        $appeal = $this->governanceService->createAppeal(
            $case->id,
            $this->player->id,
            'Decision was too harsh',
            'Additional evidence available',
            $this->adminUser->id
        );

        $this->assertNotNull($appeal);
        $this->assertEquals('Decision was too harsh', $appeal->grounds);
        $this->assertEquals('pending', $appeal->status);

        // Test appeal review
        $reviewedAppeal = $this->governanceService->reviewAppeal(
            $appeal->id,
            'accepted',
            'Decision reduced to 2 matches',
            $this->adminUser->id
        );

        $this->assertEquals('accepted', $reviewedAppeal->outcome);
        $this->assertEquals('Decision reduced to 2 matches', $reviewedAppeal->reviewer);
    }

    /**
     * Test protest management
     */
    public function test_protest_management()
    {
        // Create protest
        $protest = $this->governanceService->createProtest(
            $this->match->id,
            $this->team->id,
            'Referee Decision',
            'Incorrect offside call',
            'Video evidence shows player was onside',
            $this->adminUser->id
        );

        $this->assertNotNull($protest);
        $this->assertEquals('Referee Decision', $protest->protest_type);
        $this->assertEquals('Incorrect offside call', $protest->description);
        $this->assertEquals('pending', $protest->status);

        // Test protest review
        $reviewedProtest = $this->governanceService->reviewProtest(
            $protest->id,
            'accepted',
            'Match result changed',
            $this->adminUser->id
        );

        $this->assertEquals('accepted', $reviewedProtest->outcome);
        $this->assertEquals('Match result changed', $reviewedProtest->reviewer);
    }

    /**
     * Test age alert rule functionality
     */
    public function test_age_alert_rules()
    {
        // Create age alert rule
        $rule = AgeAlertRule::create([
            'organization_id' => $this->organization->id,
            'name' => 'U18 Verification',
            'category' => 'youth',
            'min_age' => 16,
            'max_age' => 18,
            'alert_threshold_days' => 30,
            'is_active' => true,
            'auto_flag' => true,
        ]);

        // Test rule validation
        $this->assertTrue($rule->is_active);
        $this->assertTrue($rule->auto_flag);
        $this->assertEquals(16, $rule->min_age);
        $this->assertEquals(18, $rule->max_age);

        // Test active rules retrieval
        $activeRules = AgeAlertRule::where('organization_id', $this->organization->id)
            ->where('is_active', true)
            ->get();

        $this->assertCount(1, $activeRules);
        $this->assertEquals('U18 Verification', $activeRules->first()->name);
    }

    /**
     * Test player suspension validation
     */
    public function test_player_suspension_validation()
    {
        // Create disciplinary case
        $case = DisciplinaryCase::factory()->create([
            'organization_id' => $this->organization->id,
            'player_id' => $this->player->id,
        ]);

        // Test suspension creation with valid dates
        $suspension = PlayerSuspension::create([
            'player_id' => $this->player->id,
            'disciplinary_case_id' => $case->id,
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(7),
            'matches' => 3,
            'reason' => 'Test suspension',
        ]);

        $this->assertNotNull($suspension);
        $this->assertEquals(3, $suspension->matches);
        $this->assertEquals('Test suspension', $suspension->reason);

        // Test suspension validation
        $this->assertTrue($suspension->start_date < $suspension->end_date);
        $this->assertGreaterThan(0, $suspension->matches);
    }

    /**
     * Test governance service integration
     */
    public function test_governance_service_integration()
    {
        // Test complete governance workflow
        $playerId = $this->player->id;
        $matchId = $this->match->id;
        $adminId = $this->adminUser->id;

        // 1. Record age verification
        $verification = $this->governanceService->recordAgeVerification(
            $playerId,
            true,
            'Admin User',
            'Document Review',
            'Age verified successfully'
        );

        $this->assertTrue($verification->is_verified);

        // 2. Create disciplinary case
        $case = $this->governanceService->createDisciplinaryCase(
            $playerId,
            'Red Card',
            'Violent conduct',
            $matchId,
            'Referee Report',
            $adminId
        );

        $this->assertEquals('open', $case->status);

        // 3. Create suspension
        $suspension = $this->governanceService->createPlayerSuspension(
            $playerId,
            $case->id,
            now()->addDays(7),
            now()->addDays(21),
            3,
            'Violent conduct'
        );

        $this->assertNotNull($suspension);

        // 4. Create appeal
        $appeal = $this->governanceService->createAppeal(
            $case->id,
            $playerId,
            'Decision too harsh',
            'Additional evidence',
            $adminId
        );

        $this->assertEquals('pending', $appeal->status);

        // 5. Review appeal
        $reviewedAppeal = $this->governanceService->reviewAppeal(
            $appeal->id,
            'accepted',
            'Suspension reduced to 2 matches',
            $adminId
        );

        $this->assertEquals('accepted', $reviewedAppeal->outcome);

        // Verify complete workflow
        $this->assertNotNull($verification);
        $this->assertNotNull($case);
        $this->assertNotNull($suspension);
        $this->assertNotNull($appeal);
        $this->assertNotNull($reviewedAppeal);
    }

    /**
     * Test governance data integrity
     */
    public function test_governance_data_integrity()
    {
        // Test that all governance records are properly linked
        $playerId = $this->player->id;
        $matchId = $this->match->id;
        $adminId = $this->adminUser->id;

        // Create all governance records
        $verification = PlayerAgeVerification::create([
            'player_id' => $playerId,
            'is_verified' => true,
            'verification_date' => now(),
            'verified_by' => 'Admin User',
            'verification_method' => 'Document Review',
            'notes' => 'Age verified',
        ]);

        $case = DisciplinaryCase::create([
            'organization_id' => $this->organization->id,
            'player_id' => $playerId,
            'incident_type' => 'Red Card',
            'description' => 'Test incident',
            'incident_date' => now(),
            'match_id' => $matchId,
            'evidence' => 'Test evidence',
            'status' => 'open',
            'created_by' => $adminId,
        ]);

        $suspension = PlayerSuspension::create([
            'player_id' => $playerId,
            'disciplinary_case_id' => $case->id,
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(7),
            'matches' => 2,
            'reason' => 'Test suspension',
        ]);

        $appeal = Appeal::create([
            'disciplinary_case_id' => $case->id,
            'player_id' => $playerId,
            'grounds' => 'Test grounds',
            'evidence' => 'Test evidence',
            'status' => 'pending',
            'created_by' => $adminId,
        ]);

        $protest = Protest::create([
            'match_id' => $matchId,
            'team_id' => $this->team->id,
            'protest_type' => 'Referee Decision',
            'description' => 'Test protest',
            'grounds' => 'Test grounds',
            'status' => 'pending',
            'created_by' => $adminId,
        ]);

        // Verify relationships
        $this->assertEquals($playerId, $verification->player_id);
        $this->assertEquals($playerId, $case->player_id);
        $this->assertEquals($case->id, $suspension->disciplinary_case_id);
        $this->assertEquals($case->id, $appeal->disciplinary_case_id);
        $this->assertEquals($matchId, $protest->match_id);

        // Verify cascading relationships
        $this->assertNotNull($case->player);
        $this->assertNotNull($suspension->disciplinaryCase);
        $this->assertNotNull($appeal->disciplinaryCase);
        $this->assertNotNull($protest->match);
    }

    /**
     * Test governance permissions
     */
    public function test_governance_permissions()
    {
        // Create another organization and user
        $otherOrganization = Organization::factory()->create();
        $otherUser = User::factory()->create([
            'organization_id' => $otherOrganization->id,
            'user_type' => 'admin',
            'approval_status' => 'approved',
        ]);
        $otherUser->assignRole('admin');

        // Create governance records for original organization
        $case = DisciplinaryCase::factory()->create([
            'organization_id' => $this->organization->id,
            'player_id' => $this->player->id,
        ]);

        // Test that other user cannot access records from different organization
        $this->actingAs($otherUser);

        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);

        // This should fail due to organization isolation
        $this->getJson("/api/governance/disciplinary/cases/{$case->id}/status");
    }
}
