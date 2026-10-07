<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoachLoginDetailedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_detailed_coach_login()
    {
        // Create the coach user
        $user = User::factory()->create([
            'email' => 'coach@mumiasvipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'staff',
            'approval_status' => 'approved',
            'status' => 'active',
            'name' => 'Mumias Vipers Coach',
        ]);

        // Create and assign roles
        $coachRole = Role::firstOrCreate([
            'slug' => 'coach'
        ], [
            'name' => 'Coach',
            'description' => 'Club coach',
            'type' => 'partner_staff',
            'is_default' => false,
            'level' => 4,
        ]);

        $user->roles()->attach($coachRole);

        $staffBaseRole = Role::firstOrCreate([
            'slug' => 'staff-base'
        ], [
            'name' => 'Staff Base',
            'description' => 'Base role for all staff members',
            'type' => 'partner_staff',
            'is_default' => true,
            'level' => 0,
        ]);

        $user->roles()->attach($staffBaseRole);

        // Check user exists
        $this->assertDatabaseHas('users', [
            'email' => 'coach@mumiasvipers.com'
        ]);

        // Check roles are assigned
        $this->assertTrue($user->hasRole('coach'));
        $this->assertTrue($user->hasRole('staff-base'));
        $this->assertTrue($user->hasAnyRole(['coach', 'head-coach', 'assistant-coach']));

        // Check user status
        $this->assertEquals('staff', $user->user_type);
        $this->assertEquals('approved', $user->approval_status);
        $this->assertEquals('active', $user->status);

        // Attempt login
        $response = $this->post('/login', [
            'email' => 'coach@mumiasvipers.com',
            'password' => 'password',
        ]);

        // Check login was successful
        $response->assertStatus(302);
        $response->assertRedirect(route('coach.dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('coach@mumiasvipers.com', auth()->user()->email);

        // Check if user can access dashboard
        $response = $this->get(route('coach.dashboard'));
        $response->assertStatus(200);

        // Check dashboard content
        $response->assertViewIs('staff.coach.dashboard');
        $response->assertSee('Coach Dashboard');

        echo "✅ Coach login test passed successfully!\n";
    }
}
