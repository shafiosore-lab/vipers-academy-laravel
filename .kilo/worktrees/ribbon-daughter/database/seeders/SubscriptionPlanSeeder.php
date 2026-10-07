<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Starter Plan - $10/month
        SubscriptionPlan::updateOrCreate(
            ['slug' => 'starter'],
            [
                'name' => 'Starter',
                'description' => 'Perfect for small academies starting out. Includes essential features for player and team management.',
                'price' => 1000, // KES 1,000
                'billing_cycle' => 'monthly',
                'max_users' => 2,
                'max_players' => 50,
                'max_staff' => 2,
                'features' => [
                    'players_management' => true,
                    'teams' => true,
                    'basic_reports' => true,
                    'attendance_tracking' => true,
                    'training_sessions' => true,
                    'parent_portal' => false,
                    'player_portal' => false,
                    'finance_module' => false,
                    'advanced_reports' => false,
                    'api_access' => false,
                    'custom_branding' => false,
                    'priority_support' => false,
                ],
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 1,
            ]
        );

        // Professional Plan - $29/month
        SubscriptionPlan::updateOrCreate(
            ['slug' => 'professional'],
            [
                'name' => 'Professional',
                'description' => 'Best for growing academies that need advanced features and more capacity.',
                'price' => 2900, // KES 2,900
                'billing_cycle' => 'monthly',
                'max_users' => -1, // Unlimited
                'max_players' => 500,
                'max_staff' => -1, // Unlimited
                'features' => [
                    'players_management' => true,
                    'teams' => true,
                    'basic_reports' => true,
                    'advanced_reports' => true,
                    'attendance_tracking' => true,
                    'training_sessions' => true,
                    'parent_portal' => true,
                    'player_portal' => true,
                    'finance_module' => true,
                    'api_access' => false,
                    'custom_branding' => false,
                    'priority_support' => false,
                ],
                'is_active' => true,
                'is_popular' => true,
                'sort_order' => 2,
            ]
        );

        // Enterprise Plan - $99/month
        SubscriptionPlan::updateOrCreate(
            ['slug' => 'enterprise'],
            [
                'name' => 'Enterprise',
                'description' => 'For large organizations requiring unlimited access, API, and premium support.',
                'price' => 9900, // KES 9,900
                'billing_cycle' => 'monthly',
                'max_users' => -1, // Unlimited
                'max_players' => -1, // Unlimited
                'max_staff' => -1, // Unlimited
                'features' => [
                    'players_management' => true,
                    'teams' => true,
                    'basic_reports' => true,
                    'advanced_reports' => true,
                    'custom_reports' => true,
                    'attendance_tracking' => true,
                    'training_sessions' => true,
                    'parent_portal' => true,
                    'player_portal' => true,
                    'finance_module' => true,
                    'api_access' => true,
                    'custom_branding' => true,
                    'priority_support' => true,
                    'white_label' => true,
                ],
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 3,
            ]
        );
    }
}
