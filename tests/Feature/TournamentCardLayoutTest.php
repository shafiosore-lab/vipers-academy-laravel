<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\FootballMatch;
use App\Models\User;
use App\Models\Organization;

class TournamentCardLayoutTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $tournament;
    protected $teams;
    protected $matches;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com'
        ]);

        // Create test organization
        $organization = Organization::factory()->create([
            'name' => 'Test Organization'
        ]);

        // Create test tournament
        $this->tournament = Tournament::factory()->create([
            'name' => 'Test Tournament',
            'organization_id' => $organization->id,
            'season' => '2024-2025',
            'format' => 'round_robin',
            'status' => 'active',
            'max_teams' => 8,
            'max_players_per_team' => 25
        ]);

        // Create test teams
        $this->teams = Team::factory()->count(6)->create([
            'organization_id' => $organization->id
        ]);

        // Register teams to tournament
        foreach ($this->teams as $team) {
            $this->tournament->teams()->attach($team->id, [
                'status' => 'approved',
                'registration_fee_paid' => true,
                'registration_form_submitted' => true,
                'player_list_submitted' => true
            ]);
        }

        // Create test matches
        $this->matches = FootballMatch::factory()->count(10)->create([
            'tournament_id' => $this->tournament->id,
            'home_team_id' => $this->teams->first()->id,
            'away_team_id' => $this->teams->last()->id,
            'status' => 'completed',
            'home_score' => 2,
            'away_score' => 1,
            'home_yellow_cards' => 1,
            'away_yellow_cards' => 2,
            'home_red_cards' => 0,
            'away_red_cards' => 0,
            'kickoff_time' => now()->subDays(1),
            'venue' => 'Test Stadium',
            'referee' => 'Test Referee'
        ]);
    }

    /**
     * Test that all card-based layouts are accessible
     */
    public function testCardLayoutsAreAccessible()
    {
        $this->actingAs($this->admin);

        // Test tournament show card layout
        $response = $this->get(route('admin.tournaments.show', $this->tournament->id));
        $response->assertStatus(200);
        $response->assertSee('card tournament-card');
        $response->assertSee('Tournament Overview');
        $response->assertSee('Quick Actions');

        // Test tournament index card layout
        $response = $this->get(route('admin.tournaments.index'));
        $response->assertStatus(200);
        $response->assertSee('tournament-card-grid');
        $response->assertSee('Test Tournament');

        // Test standings card layout
        $response = $this->get(route('admin.tournaments.standings.index', $this->tournament->id));
        $response->assertStatus(200);
        $response->assertSee('tournament-card-grid');
        $response->assertSee('Standings Summary');

        // Test teams card layout
        $response = $this->get(route('admin.tournaments.teams.index', $this->tournament->id));
        $response->assertStatus(200);
        $response->assertSee('tournament-card-grid');
        $response->assertSee('Teams Summary');

        // Test matches card layout
        $response = $this->get(route('admin.tournaments.matches.index', $this->tournament->id));
        $response->assertStatus(200);
        $response->assertSee('tournament-card-grid');
        $response->assertSee('Matches Summary');

        // Test schedule card layout
        $response = $this->get(route('admin.tournaments.schedule.card', $this->tournament->id));
        $response->assertStatus(200);
        $response->assertSee('tournament-card');
        $response->assertSee('Schedule Overview');

        // Test statistics card layout
        $response = $this->get(route('admin.tournaments.statistics.index', $this->tournament->id));
        $response->assertStatus(200);
        $response->assertSee('tournament-card-row');
        $response->assertSee('Statistics Overview');
    }

    /**
     * Test that card components render correctly
     */
    public function testCardComponentsRenderCorrectly()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.tournaments.show', $this->tournament->id));
        $response->assertStatus(200);

        // Check that summary cards are rendered
        $response->assertSee('tournament-summary-card');
        $response->assertSee('action-card');
        $response->assertSee('expandable-card');

        // Check that card-specific content is present
        $response->assertSee('Test Tournament');
        $response->assertSee('Test Organization');
        $response->assertSee('2024-2025');
    }

    /**
     * Test that card layouts handle empty states correctly
     */
    public function testCardLayoutsHandleEmptyStates()
    {
        // Create tournament with no teams or matches
        $emptyTournament = Tournament::factory()->create([
            'name' => 'Empty Tournament',
            'organization_id' => $this->tournament->organization_id
        ]);

        $this->actingAs($this->admin);

        // Test teams page with no teams
        $response = $this->get(route('admin.tournaments.teams.index', $emptyTournament->id));
        $response->assertStatus(200);
        $response->assertSee('No Teams Registered');
        $response->assertSee('Add First Team');

        // Test matches page with no matches
        $response = $this->get(route('admin.tournaments.matches.index', $emptyTournament->id));
        $response->assertStatus(200);
        $response->assertSee('No Matches Scheduled');
        $response->assertSee('Create First Match');
    }

    /**
     * Test that card layouts are responsive
     */
    public function testCardLayoutsAreResponsive()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.tournaments.index'));
        $response->assertStatus(200);

        // Check that responsive CSS classes are present
        $response->assertSee('tournament-card-grid');
        $response->assertSee('@media (max-width: 768px)');
        $response->assertSee('grid-template-columns: 1fr');
    }

    /**
     * Test that card layouts maintain functionality
     */
    public function testCardLayoutsMaintainFunctionality()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.tournaments.show', $this->tournament->id));
        $response->assertStatus(200);

        // Check that action buttons are present
        $response->assertSee('btn-outline-primary');
        $response->assertSee('btn-outline-warning');
        $response->assertSee('btn-outline-danger');

        // Check that links work
        $response->assertSee(route('admin.tournaments.standings.index', $this->tournament->id));
        $response->assertSee(route('admin.tournaments.teams.index', $this->tournament->id));
        $response->assertSee(route('admin.tournaments.matches.index', $this->tournament->id));
    }

    /**
     * Test that card layouts display correct data
     */
    public function testCardLayoutsDisplayCorrectData()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.tournaments.show', $this->tournament->id));
        $response->assertStatus(200);

        // Check that tournament data is displayed
        $response->assertSee($this->tournament->name);
        $response->assertSee($this->tournament->organization->name);
        $response->assertSee($this->tournament->season);
        $response->assertSee(ucfirst($this->tournament->status));

        // Check that team count is displayed
        $response->assertSee($this->teams->count());
    }

    /**
     * Test that card layouts work with different tournament statuses
     */
    public function testCardLayoutsWithDifferentStatuses()
    {
        $this->actingAs($this->admin);

        // Test with different tournament statuses
        $statuses = ['active', 'completed', 'cancelled', 'upcoming'];

        foreach ($statuses as $status) {
            $this->tournament->update(['status' => $status]);

            $response = $this->get(route('admin.tournaments.show', $this->tournament->id));
            $response->assertStatus(200);
            $response->assertSee(ucfirst($status));
        }
    }

    /**
     * Test that card layouts handle errors gracefully
     */
    public function testCardLayoutsHandleErrors()
    {
        $this->actingAs($this->admin);

        // Test with non-existent tournament
        $response = $this->get(route('admin.tournaments.show', 999));
        $response->assertStatus(404);

        // Test with invalid team ID
        $response = $this->get(route('admin.tournaments.teams.index', 999));
        $response->assertStatus(404);
    }

    /**
     * Test that card layouts maintain SEO and accessibility
     */
    public function testCardLayoutsMaintainSEOAndAccessibility()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.tournaments.show', $this->tournament->id));
        $response->assertStatus(200);

        // Check for proper headings
        $response->assertSee('<h4');
        $response->assertSee('<h5');
        $response->assertSee('<h6');

        // Check for alt text on images
        $response->assertSee('alt="');

        // Check for proper semantic structure
        $response->assertSee('<main');
        $response->assertSee('<section');
        $response->assertSee('<article');
    }

    /**
     * Test that card layouts work with JavaScript disabled
     */
    public function testCardLayoutsWithoutJavaScript()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.tournaments.show', $this->tournament->id));
        $response->assertStatus(200);

        // Check that the page works without JavaScript
        $response->assertSee('Test Tournament');
        $response->assertSee('Test Organization');

        // Verify that essential functionality works
        $response->assertSee('href="' . route('admin.tournaments.standings.index', $this->tournament->id));
        $response->assertSee('href="' . route('admin.tournaments.teams.index', $this->tournament->id));
    }
}
