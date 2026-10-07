<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_login_with_valid_credentials()
    {
        // Create a user for testing
        $user = User::factory()->create([
            'email' => 'coach@mumiasvipers.com',
            'password' => bcrypt('password'),
            'user_type' => 'staff',
            'approval_status' => 'approved',
            'status' => 'active',
        ]);

        // Create and assign coach role to user
        $coachRole = \App\Models\Role::where('slug', 'coach')->first();
        if (!$coachRole) {
            $coachRole = \App\Models\Role::create([
                'name' => 'Coach',
                'slug' => 'coach',
                'description' => 'Club coach',
                'type' => 'staff',
                'is_default' => false,
                'level' => 4,
            ]);
        }
        $user->roles()->attach($coachRole);

        // Test login
        $response = $this->post('/login', [
            'email' => 'coach@mumiasvipers.com',
            'password' => 'password',
        ]);

        // Assert login was successful
        $response->assertRedirect();
        $this->assertAuthenticated();

        // Check if authenticated user is the correct one
        $this->assertEquals('coach@mumiasvipers.com', auth()->user()->email);
    }

    /** @test */
    public function cannot_login_with_invalid_credentials()
    {
        // Test with invalid credentials
        $response = $this->post('/login', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ]);

        // Assert login failed and we're back on login page with error
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}
