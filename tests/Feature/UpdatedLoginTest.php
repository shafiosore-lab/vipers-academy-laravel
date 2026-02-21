<?php

use App\Models\User;

test('coach can login with valid credentials and is redirected to coach dashboard', function () {
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
            'type' => 'partner_staff',
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
    $response->assertRedirect(route('coach.dashboard'));
    $this->assertAuthenticated();

    // Check if authenticated user is the correct one
    $this->assertEquals('coach@mumiasvipers.com', auth()->user()->email);
});

test('admin can login with valid credentials and is redirected to admin dashboard', function () {
    // Create a user for testing
    $user = User::factory()->create([
        'email' => 'admin@mumiasvipers.com',
        'password' => bcrypt('password'),
        'user_type' => 'admin',
        'approval_status' => 'approved',
        'status' => 'active',
    ]);

    // Create and assign admin role to user
    $adminRole = \App\Models\Role::where('slug', 'super-admin')->first();
    if (!$adminRole) {
        $adminRole = \App\Models\Role::create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'description' => 'Super administrator',
            'type' => 'admin',
            'is_default' => false,
            'level' => 100,
        ]);
    }
    $user->roles()->attach($adminRole);

    // Test login
    $response = $this->post('/login', [
        'email' => 'admin@mumiasvipers.com',
        'password' => 'password',
    ]);

    // Assert login was successful
    $response->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticated();

    // Check if authenticated user is the correct one
    $this->assertEquals('admin@mumiasvipers.com', auth()->user()->email);
});
