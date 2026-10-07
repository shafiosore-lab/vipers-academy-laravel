<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TournamentStatisticsBasicTest extends TestCase
{
    /**
     * Test that the tournament statistics routes exist and are accessible.
     *
     * @return void
     */
    public function test_tournament_statistics_routes_exist()
    {
        // Test that the main statistics route exists
        $response = $this->get('/admin/tournaments/statistics');

        // This will redirect to login since we're not authenticated,
        // but it proves the route exists
        $response->assertStatus(302);
    }

    /**
     * Test that the controller methods exist.
     *
     * @return void
     */
    public function test_tournament_statistics_controller_methods_exist()
    {
        $controller = new \App\Http\Controllers\Admin\TournamentStatisticsController();

        // Test that all required methods exist
        $this->assertTrue(method_exists($controller, 'index'));
        $this->assertTrue(method_exists($controller, 'topScorers'));
        $this->assertTrue(method_exists($controller, 'discipline'));
        $this->assertTrue(method_exists($controller, 'groups'));
        $this->assertTrue(method_exists($controller, 'rankings'));
        $this->assertTrue(method_exists($controller, 'summary'));
        $this->assertTrue(method_exists($controller, 'live'));
        $this->assertTrue(method_exists($controller, 'export'));
    }

    /**
     * Test that the views exist.
     *
     * @return void
     */
    public function test_tournament_statistics_views_exist()
    {
        // Test that all required views exist
        $this->assertTrue(view()->exists('admin.tournaments.statistics.index'));
        $this->assertTrue(view()->exists('admin.tournaments.statistics.top-scorers'));
        $this->assertTrue(view()->exists('admin.tournaments.statistics.discipline'));
        $this->assertTrue(view()->exists('admin.tournaments.statistics.groups'));
        $this->assertTrue(view()->exists('admin.tournaments.statistics.rankings'));
        $this->assertTrue(view()->exists('admin.tournaments.statistics.summary'));
    }
}
