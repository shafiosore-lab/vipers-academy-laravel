<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'Premium package with full access to all academy facilities and services',
                'monthly_amount' => 500.00,
                'joining_fee' => 1000.00,
                'is_active' => true,
                'sort_order' => 1,
                'payment_interval_days' => 30,
                'grace_period_days' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Standard',
                'slug' => 'standard',
                'description' => 'Standard package with basic access to training sessions',
                'monthly_amount' => 200.00,
                'joining_fee' => 100.00,
                'is_active' => true,
                'sort_order' => 2,
                'payment_interval_days' => 30,
                'grace_period_days' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Trial',
                'slug' => 'trial',
                'description' => 'One-month trial period for new players',
                'monthly_amount' => 0.00,
                'joining_fee' => 0.00,
                'is_active' => true,
                'sort_order' => 3,
                'payment_interval_days' => 30,
                'grace_period_days' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('payment_categories')->insert($categories);
    }
}
