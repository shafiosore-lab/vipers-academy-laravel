<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;

test('coach can access dashboard', function () {
    // Create a coach user
    $user = User::factory()->create([
        'email' => 'coach@mumiasvipers.com',
        'password' => bcrypt('password'),
        'user_type' => 'staff',
        'approval_status' => 'approved',
        'status' => 'active',
    ]);

    // Assign coach role
    $coachRole = Role::firstOrCreate(['slug' => 'coach'], [
        'name' => 'Coach',
        'description' => 'Team coach',
        'type' => 'partner_staff',
        'is_default' => false,
    ]);
    $staffBaseRole = Role::firstOrCreate(['slug' => 'staff-base'], [
        'name' => 'Staff Base',
        'description' => 'Base role for all staff members',
        'type' => 'partner_staff',
        'is_default' => true,
    ]);
    $user->roles()->sync([$coachRole->id, $staffBaseRole->id]);

    // Login as coach
    $response = $this->actingAs($user)->get('/coach/dashboard');

    // Verify the response
    $response->assertStatus(200);
    $response->assertViewIs('staff.coach.dashboard');
});
