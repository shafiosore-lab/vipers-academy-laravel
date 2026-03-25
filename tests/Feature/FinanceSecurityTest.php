<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Payment;
use App\Models\PaymentCategory;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinanceSecurityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that unauthorized users cannot access finance routes
     */
    public function test_unauthorized_users_cannot_access_finance_routes()
    {
        // Create a user without finance permissions
        $user = User::factory()->create();

        // Test accessing finance dashboard
        $response = $this->actingAs($user)
            ->get('/finance/dashboard');

        $response->assertStatus(403);
    }

    /**
     * Test that finance-officer can access basic finance functions
     */
    public function test_finance_officer_can_access_basic_functions()
    {
        // Create a finance-officer user
        $user = User::factory()->create();
        $user->assignRole('finance-officer');

        // Test accessing finance dashboard
        $response = $this->actingAs($user)
            ->get('/finance/dashboard');

        $response->assertStatus(200);

        // Test accessing payments list
        $response = $this->actingAs($user)
            ->get('/finance/payments');

        $response->assertStatus(200);
    }

    /**
     * Test that finance-officer cannot update payments (requires higher permissions)
     */
    public function test_finance_officer_cannot_update_payments()
    {
        // Create a finance-officer user
        $user = User::factory()->create();
        $user->assignRole('finance-officer');

        // Create a payment
        $payment = Payment::factory()->create();

        // Test trying to update payment
        $response = $this->actingAs($user)
            ->put("/finance/payments/{$payment->id}", [
                'player_id' => $payment->player_id,
                'payment_type' => 'monthly_fee',
                'amount' => 1000,
                'payment_method' => 'cash',
                'payment_status' => 'completed',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test that finance-admin can update payments
     */
    public function test_finance_admin_can_update_payments()
    {
        // Create a finance-admin user
        $user = User::factory()->create();
        $user->assignRole('finance-admin');

        // Create a payment
        $payment = Payment::factory()->create();

        // Test updating payment
        $response = $this->actingAs($user)
            ->put("/finance/payments/{$payment->id}", [
                'player_id' => $payment->player_id,
                'payment_type' => 'monthly_fee',
                'amount' => 1000,
                'payment_method' => 'cash',
                'payment_status' => 'completed',
            ]);

        $response->assertStatus(302); // Redirect after successful update
    }

    /**
     * Test that finance-officer cannot delete payments
     */
    public function test_finance_officer_cannot_delete_payments()
    {
        // Create a finance-officer user
        $user = User::factory()->create();
        $user->assignRole('finance-officer');

        // Create a payment
        $payment = Payment::factory()->create();

        // Test trying to delete payment
        $response = $this->actingAs($user)
            ->delete("/finance/payments/{$payment->id}", [
                'delete_reason' => 'Test deletion reason'
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test that finance-admin can delete payments
     */
    public function test_finance_admin_can_delete_payments()
    {
        // Create a finance-admin user
        $user = User::factory()->create();
        $user->assignRole('finance-admin');

        // Create a payment
        $payment = Payment::factory()->create();

        // Test deleting payment
        $response = $this->actingAs($user)
            ->delete("/finance/payments/{$payment->id}", [
                'delete_reason' => 'Test deletion reason'
            ]);

        $response->assertStatus(302); // Redirect after successful request
    }

    /**
     * Test that finance-officer can create payments
     */
    public function test_finance_officer_can_create_payments()
    {
        // Create a finance-officer user
        $user = User::factory()->create();
        $user->assignRole('finance-officer');

        // Create a player and payment category
        $player = Player::factory()->create();
        $category = PaymentCategory::factory()->create();

        // Test creating payment
        $response = $this->actingAs($user)
            ->post('/finance/payments', [
                'player_id' => $player->id,
                'payment_type' => 'monthly_fee',
                'amount' => 1000,
                'payment_method' => 'cash',
                'payment_category_id' => $category->id,
                'paid_at' => now(),
            ]);

        $response->assertStatus(302); // Redirect after successful creation
        $this->assertDatabaseHas('payments', [
            'player_id' => $player->id,
            'amount' => 1000,
        ]);
    }
}
