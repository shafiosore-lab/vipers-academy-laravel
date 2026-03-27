<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\Player;
use App\Models\TournamentMatch;
use App\Models\TournamentStanding;
use App\Models\User;
use App\Models\Role;

class TournamentStatisticsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $tournament;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create();
        $adminRole = Role::create(['name' => 'admin']);
        $this->admin->assignRole($adminRole);

        // Create tournament
        $this->tournament = Tournament::factory()->create([
            'name' => 'Test Tournament',
            'format' => 'round_robin',
            'status' => 'in_progress'
        ]);

        // Create teams
        $teams = TournamentTeam::factory(4)->create([
            'tournament_id' => $this->tournament->id
        ]);

        // Create players
        foreach ($teams as $team) {
            Player::factory(11)->create([
                'team_id' => $team->id,
                'tournament_id' => $this->tournament->id
            ]);
        }

        // Create matches
        TournamentMatch::factory(6)->create([
            'tournament_id' => $this->tournament->id,
            'status' => 'completed',
            'home_score' => rand(0, 5),
            'away_score' => rand(0, 5)
        ]);

        // Create standings
        TournamentStanding::factory(4)->create([
            'tournament_id' => $this->tournament->id,
            'team_id' => $teams->random()->id,
            'position' => rand(1, 4),
            'points' => rand(0, 12),
            'goals_for' => rand(0, 10),
            'goals_against' => rand(0, 10)
        ]);
    }

    /**
     * Test that admin can view tournament statistics index
     */
    public function test_admin_can_view_tournament_statistics_index()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.tournaments.statistics.index', $this->tournament));

        $response->assertStatus(200);
        $response->assertViewHas('tournament', $this->tournament);
        $response->assertViewHas('summary');
        $response->assertViewHas('topScorers');
        $response->assertViewHas('teamCards');
        $response->assertViewHas('groups');
    }

    /**
     * Test that admin can view top scorers
     */
    public function test_admin_can_view_top_scorers()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.tournaments.statistics.top-scorers', $this->tournament));

        $response->assertStatus(200);
        $response->assertViewHas('tournament', $this->tournament);
        $response->assertViewHas('summary');
        $response->assertViewHas('topScorers');
        $response->assertViewHas('topAssists');
        $response->assertViewHas('topGoalContributions');
    }

    /**
     * Test that admin can view discipline statistics
     */
    public function test_admin_can_view_discipline_statistics()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.tournaments.statistics.discipline', $this->tournament));

        $response->assertStatus(200);
        $response->assertViewHas('tournament', $this->tournament);
        $response->assertViewHas('summary');
        $response->assertViewHas('teamCards');
        $response->assertViewHas('playerCards');
        $response->assertViewHas('suspensions');
    }

    /**
     * Test that admin can view groups statistics
     */
    public function test_admin_can_view_groups_statistics()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.tournaments.statistics.groups', $this->tournament));

        $response->assertStatus(200);
        $response->assertViewHas('tournament', $this->tournament);
        $response->assertViewHas('groups');
        $response->assertViewHas('groupMatches');
        $response->assertViewHas('formatInfo');
    }

    /**
     * Test that admin can view rankings
     */
    public function test_admin_can_view_rankings()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.tournaments.statistics.rankings', $this->tournament));

        $response->assertStatus(200);
        $response->assertViewHas('tournament', $this->tournament);
        $response->assertViewHas('rankings');
        $response->assertViewHas('eloRankings');
        $response->assertViewHas('formTable');
        $response->assertViewHas('formatInfo');
    }

    /**
     * Test that admin can view tournament summary
     */
    public function test_admin_can_view_tournament_summary()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.tournaments.statistics.summary', $this->tournament));

        $response->assertStatus(200);
        $response->assertViewHas('tournament', $this->tournament);
        $response->assertViewHas('summary');
        $response->assertViewHas('topScorers');
        $response->assertViewHas('topAssists');
        $response->assertViewHas('cleanSheets');
        $response->assertViewHas('rankings');
        $response->assertViewHas('formatInfo');
    }

    /**
     * Test that admin can access live API endpoint
     */
    public function test_admin_can_access_live_api()
    {
        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.tournaments.statistics.live', $this->tournament));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'summary',
            'top_scorers',
            'team_cards',
            'rankings',
            'timestamp'
        ]);
    }

    /**
     * Test that non-admin cannot access statistics
     */
    public function test_non_admin_cannot_access_statistics()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.tournaments.statistics.index', $this->tournament));

        $response->assertStatus(403);
    }

    /**
     * Test that unauthorized user cannot access statistics
     */
    public function test_unauthorized_user_cannot_access_statistics()
    {
        $response = $this->get(route('admin.tournaments.statistics.index', $this->tournament));

        $response->assertRedirect('/login');
    }

    /**
     * Test export functionality
     */
    public function test_export_functionality()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.tournaments.statistics.export', [
                'tournament' => $this->tournament,
                'format' => 'csv'
            ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="tournament-summary.csv"');
    }

    /**
     * Test that statistics are properly scoped to tournament
     */
    public function test_statistics_are_tournament_scoped()
    {
        // Create another tournament
        $otherTournament = Tournament::factory()->create([
            'name' => 'Other Tournament'
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.tournaments.statistics.index', $this->tournament));

        $response->assertStatus(200);

        // Verify that the response contains data for the correct tournament
        $viewData = $response->original->getData();
        $this->assertEquals($this->tournament->id, $viewData['tournament']->id);
    }

    /**
     * Test real-time updates functionality
     */
    public function test_real_time_updates()
    {
        // Create a new match to trigger statistics update
        $newMatch = TournamentMatch::factory()->create([
            'tournament_id' => $this->tournament->id,
            'status' => 'completed',
            'home_score' => 3,
            'away_score' => 1
        ]);

        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.tournaments.statistics.live', $this->tournament));

        $response->assertStatus(200);

        // Verify that the response includes updated statistics
        $data = $response->json();
        $this->assertArrayHasKey('summary', $data);
        $this->assertArrayHasKey('total_goals', $data['summary']);
    }

    /**
     * Test that statistics handle empty data gracefully
     */
    public function test_statistics_handle_empty_data()
    {
        // Create tournament with no matches
        $emptyTournament = Tournament::factory()->create([
            'name' => 'Empty Tournament',
            'status' => 'upcoming'
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.tournaments.statistics.index', $emptyTournament));

        $response->assertStatus(200);
        $response->assertViewHas('summary');

        // Verify that empty statistics are handled gracefully
        $viewData = $response->original->getData();
        $this->assertIsArray($viewData['summary']);
        $this->assertEquals(0, $viewData['summary']['total_goals']);
        $this->assertEquals(0, $viewData['summary']['completed_matches']);
    }
}
