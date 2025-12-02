<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coupon::create([
            'code' => 'WELCOME10',
            'name' => 'Welcome Discount',
            'description' => '10% off your first order',
            'type' => 'percentage',
            'value' => 10,
            'minimum_order_amount' => 0,
            'usage_limit' => 1,
            'is_active' => true,
            'auto_apply' => true,
        ]);

        Coupon::create([
            'code' => 'SAVE20',
            'name' => '20% Off Orders',
            'description' => '20% off orders over KSH 100',
            'type' => 'percentage',
            'value' => 20,
            'minimum_order_amount' => 100,
            'maximum_discount' => 200,
            'usage_limit' => 100,
            'is_active' => true,
            'auto_apply' => false,
        ]);

        Coupon::create([
            'code' => 'FREESHIP',
            'name' => 'Free Shipping',
            'description' => 'Free shipping on orders over KSH 50',
            'type' => 'free_shipping',
            'value' => 0,
            'minimum_order_amount' => 50,
            'usage_limit' => 50,
            'is_active' => true,
            'auto_apply' => true,
        ]);

        Coupon::create([
            'code' => 'VIP50',
            'name' => 'KSH 50 Off',
            'description' => 'Fixed KSH 50 discount',
            'type' => 'fixed_amount',
            'value' => 50,
            'minimum_order_amount' => 100,
            'usage_limit' => null, // Unlimited
            'is_active' => true,
            'auto_apply' => false,
        ]);

        Coupon::create([
            'code' => 'EXPIRED_DEMO',
            'name' => 'Expired Coupon (Demo)',
            'description' => 'This coupon has expired',
            'type' => 'percentage',
            'value' => 15,
            'minimum_order_amount' => 0,
            'usage_limit' => 10,
            'is_active' => true,
            'expires_at' => now()->subDay(),
        ]);

        $this->command->info('Created 5 sample coupons!');
    }
}
