<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding sample payment data for finance dashboard...');

        // Get payment categories
        $premiumCategory = DB::table('payment_categories')->where('slug', 'premium')->first();
        $standardCategory = DB::table('payment_categories')->where('slug', 'standard')->first();

        // Check if there are existing users
        $userCount = DB::table('users')->count();

        if ($userCount === 0) {
            $this->command->warn('No users found. Please run seed_all_users.php first.');
            return;
        }

        // Get some user IDs for payments
        $userIds = DB::table('users')->limit(5)->pluck('id')->toArray();

        // Create sample payments directly
        $payments = [];
        $paymentMethods = ['mpesa', 'cash', 'bank_transfer'];

        // Create payments for different months
        $months = [
            ['month' => now()->subMonths(5), 'label' => 'September 2025'],
            ['month' => now()->subMonths(4), 'label' => 'October 2025'],
            ['month' => now()->subMonths(3), 'label' => 'November 2025'],
            ['month' => now()->subMonths(2), 'label' => 'December 2025'],
            ['month' => now()->subMonths(1), 'label' => 'January 2026'],
            ['month' => now(), 'label' => 'February 2026'],
        ];

        $paymentIndex = 0;
        foreach ($userIds as $userId) {
            foreach ($months as $monthData) {
                $isCompleted = rand(1, 100) > 10; // 90% completed
                $isPremium = $paymentIndex % 2 === 0;
                $monthlyFee = $isPremium ? 500 : 200;
                $joiningFee = $isPremium ? 1000 : 100;

                // Only add joining fee for first month - use registration_fee for ENUM
                if ($monthData['month']->format('Y-m') === now()->subMonths(5)->format('Y-m')) {
                    $payments[] = [
                        'payment_reference' => 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                        'user_id' => $userId,
                        'player_id' => null,
                        'payer_type' => 'customer',
                        'payer_id' => $userId,
                        'payment_type' => 'registration_fee',
                        'description' => 'Registration/Joining Fee - ' . ($isPremium ? 'Premium' : 'Standard') . ' Category',
                        'amount' => $joiningFee,
                        'currency' => 'KES',
                        'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                        'payment_status' => 'completed',
                        'paid_at' => $monthData['month'],
                        'due_date' => $monthData['month']->copy()->subDays(7),
                        'month_applied_to' => $monthData['month']->format('Y-m'),
                        'payment_category_id' => $isPremium ? $premiumCategory->id : $standardCategory->id,
                        'category_slug' => $isPremium ? 'premium' : 'standard',
                        'is_joining_fee_paid' => true,
                        'created_at' => $monthData['month'],
                        'updated_at' => now(),
                    ];
                }

                // Monthly fee - use subscription_fee for ENUM
                $payments[] = [
                    'payment_reference' => 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                    'user_id' => $userId,
                    'player_id' => null,
                    'payer_type' => 'customer',
                    'payer_id' => $userId,
                    'payment_type' => 'subscription_fee',
                    'description' => 'Monthly Subscription Fee - ' . $monthData['label'],
                    'amount' => $monthlyFee,
                    'currency' => 'KES',
                    'payment_method' => $isCompleted ? $paymentMethods[array_rand($paymentMethods)] : 'mpesa',
                    'payment_status' => $isCompleted ? 'completed' : 'pending',
                    'paid_at' => $isCompleted ? $monthData['month']->copy()->subDays(rand(0, 5)) : null,
                    'due_date' => $monthData['month']->copy()->subDays(7),
                    'month_applied_to' => $monthData['month']->format('Y-m'),
                    'payment_category_id' => $isPremium ? $premiumCategory->id : $standardCategory->id,
                    'category_slug' => $isPremium ? 'premium' : 'standard',
                    'is_joining_fee_paid' => true,
                    'created_at' => $monthData['month'],
                    'updated_at' => now(),
                ];
            }
            $paymentIndex++;
        }

        // Add some overdue payments
        $overdueMonths = [
            ['month' => now()->subMonths(6), 'label' => 'August 2025'],
            ['month' => now()->subMonths(7), 'label' => 'July 2025'],
        ];

        foreach (array_slice($userIds, 0, 3) as $userId) {
            foreach ($overdueMonths as $monthData) {
                $isPremium = rand(1, 100) > 50;
                $monthlyFee = $isPremium ? 500 : 200;

                $payments[] = [
                    'payment_reference' => 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                    'user_id' => $userId,
                    'player_id' => null,
                    'payer_type' => 'customer',
                    'payer_id' => $userId,
                    'payment_type' => 'subscription_fee',
                    'description' => 'Monthly Subscription Fee - ' . $monthData['label'] . ' (OVERDUE)',
                    'amount' => $monthlyFee,
                    'currency' => 'KES',
                    'payment_method' => 'mpesa',
                    'payment_status' => 'pending',
                    'paid_at' => null,
                    'due_date' => $monthData['month']->copy()->subDays(7),
                    'month_applied_to' => $monthData['month']->format('Y-m'),
                    'payment_category_id' => $isPremium ? $premiumCategory->id : $standardCategory->id,
                    'category_slug' => $isPremium ? 'premium' : 'standard',
                    'is_joining_fee_paid' => true,
                    'created_at' => $monthData['month'],
                    'updated_at' => now(),
                ];
            }
        }

        foreach ($payments as $payment) {
            DB::table('payments')->insert($payment);
        }

        $this->command->info('Created ' . count($payments) . ' payments');

        $this->command->info('Sample payment data seeding completed!');

        // Summary
        $totalPayments = DB::table('payments')->count();
        $completedPayments = DB::table('payments')->where('payment_status', 'completed')->count();
        $pendingPayments = DB::table('payments')->where('payment_status', 'pending')->count();
        $totalRevenue = DB::table('payments')->where('payment_status', 'completed')->sum('amount');

        $this->command->info("Summary:");
        $this->command->info("  Total Payments: {$totalPayments}");
        $this->command->info("  Completed: {$completedPayments}");
        $this->command->info("  Pending: {$pendingPayments}");
        $this->command->info("  Total Revenue: KES " . number_format($totalRevenue, 2));
    }
}
