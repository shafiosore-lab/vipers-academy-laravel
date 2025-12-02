<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Training Kits & Apparel
        Product::create([
            'name' => 'Vipers Academy Training Jersey',
            'description' => 'Official Vipers Academy training jersey made from breathable, moisture-wicking fabric. Features the academy logo and sponsor branding. Perfect for training sessions and matches.',
            'price' => 2500.00,
            'category' => 'Apparel',
            'images' => ['training-jersey-front.jpg', 'training-jersey-back.jpg'],
            'stock' => 50,
            'sku' => 'VA-TJ-001',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Vipers Academy Shorts',
            'description' => 'Comfortable training shorts with elastic waistband and academy branding. Made from lightweight, quick-dry material ideal for intense training sessions.',
            'price' => 1200.00,
            'category' => 'Apparel',
            'images' => ['training-shorts.jpg'],
            'stock' => 75,
            'sku' => 'VA-TS-001',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Vipers Academy Socks',
            'description' => 'High-quality football socks with cushioned soles and academy colors. Provides comfort and support during training and matches.',
            'price' => 800.00,
            'category' => 'Apparel',
            'images' => ['academy-socks.jpg'],
            'stock' => 100,
            'sku' => 'VA-SK-001',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Vipers Academy Hoodie',
            'description' => 'Warm and comfortable hoodie featuring the Vipers Academy logo. Perfect for cooler training sessions and casual wear.',
            'price' => 3500.00,
            'category' => 'Apparel',
            'images' => ['academy-hoodie.jpg'],
            'stock' => 30,
            'sku' => 'VA-HD-001',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Vipers Academy Cap',
            'description' => 'Adjustable baseball cap with embroidered Vipers Academy logo. Provides sun protection during outdoor training.',
            'price' => 1000.00,
            'category' => 'Accessories',
            'images' => ['academy-cap.jpg'],
            'stock' => 60,
            'sku' => 'VA-CP-001',
            'is_active' => true,
        ]);

        // Training Equipment
        Product::create([
            'name' => 'Vipers Academy Football',
            'description' => 'Official size 5 football used in academy training sessions. High-quality construction with academy branding.',
            'price' => 1800.00,
            'category' => 'Equipment',
            'images' => ['academy-football.jpg'],
            'stock' => 40,
            'sku' => 'VA-FB-001',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Training Cones Set',
            'description' => 'Set of 20 colorful training cones for agility and skill drills. Essential for football training sessions.',
            'price' => 2500.00,
            'category' => 'Equipment',
            'images' => ['training-cones.jpg'],
            'stock' => 15,
            'sku' => 'VA-CN-001',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Agility Ladder',
            'description' => 'Professional 6-meter agility ladder for footwork and speed training. Improves coordination and quickness.',
            'price' => 1500.00,
            'category' => 'Equipment',
            'images' => ['agility-ladder.jpg'],
            'stock' => 25,
            'sku' => 'VA-AL-001',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Resistance Bands Set',
            'description' => 'Set of 5 resistance bands with different strengths for strength training and rehabilitation. Includes carrying case.',
            'price' => 2200.00,
            'category' => 'Equipment',
            'images' => ['resistance-bands.jpg'],
            'stock' => 20,
            'sku' => 'VA-RB-001',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Vipers Academy Water Bottle',
            'description' => 'Insulated stainless steel water bottle with Vipers Academy logo. Keeps drinks cold for 12 hours or hot for 8 hours.',
            'price' => 1200.00,
            'category' => 'Accessories',
            'images' => ['academy-water-bottle.jpg'],
            'stock' => 80,
            'sku' => 'VA-WB-001',
            'is_active' => true,
        ]);

        // Fan Merchandise
        Product::create([
            'name' => 'Vipers Academy Scarf',
            'description' => 'Official Vipers Academy scarf in team colors. Perfect for showing support during matches and events.',
            'price' => 800.00,
            'category' => 'Fan Wear',
            'images' => ['academy-scarf.jpg'],
            'stock' => 45,
            'sku' => 'VA-SF-001',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Vipers Academy Mug',
            'description' => 'Ceramic mug with Vipers Academy logo and inspirational quote. Perfect for coffee or tea.',
            'price' => 600.00,
            'category' => 'Accessories',
            'images' => ['academy-mug.jpg'],
            'stock' => 70,
            'sku' => 'VA-MG-001',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Vipers Academy Keychain',
            'description' => 'Metal keychain with Vipers Academy logo and football charm. A great gift for fans.',
            'price' => 300.00,
            'category' => 'Accessories',
            'images' => ['academy-keychain.jpg'],
            'stock' => 120,
            'sku' => 'VA-KC-001',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Vipers Academy Poster',
            'description' => 'Large poster featuring Vipers Academy players and achievements. Perfect for bedrooms or fan spaces.',
            'price' => 500.00,
            'category' => 'Fan Wear',
            'images' => ['academy-poster.jpg'],
            'stock' => 90,
            'sku' => 'VA-PS-001',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Vipers Academy Tote Bag',
            'description' => 'Canvas tote bag with Vipers Academy logo. Eco-friendly and perfect for carrying training gear.',
            'price' => 900.00,
            'category' => 'Accessories',
            'images' => ['academy-tote-bag.jpg'],
            'stock' => 55,
            'sku' => 'VA-TB-001',
            'is_active' => true,
        ]);

        // Goalkeeper Equipment
        Product::create([
            'name' => 'Goalkeeper Gloves',
            'description' => 'Professional goalkeeper gloves with excellent grip and protection. Used by academy goalkeepers.',
            'price' => 2800.00,
            'category' => 'Equipment',
            'images' => ['gk-gloves.jpg'],
            'stock' => 35,
            'sku' => 'VA-GK-001',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Goalkeeper Training Jersey',
            'description' => 'Special goalkeeper jersey with different color scheme for easy identification during training.',
            'price' => 2600.00,
            'category' => 'Apparel',
            'images' => ['gk-jersey.jpg'],
            'stock' => 25,
            'sku' => 'VA-GK-002',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Shin Guards Set',
            'description' => 'Professional shin guards with ankle protection. Essential safety equipment for all players.',
            'price' => 1400.00,
            'category' => 'Equipment',
            'images' => ['shin-guards.jpg'],
            'stock' => 65,
            'sku' => 'VA-SG-001',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Vipers Academy Backpack',
            'description' => 'Durable backpack with multiple compartments for training gear and personal items. Features academy branding.',
            'price' => 3200.00,
            'category' => 'Accessories',
            'images' => ['academy-backpack.jpg'],
            'stock' => 40,
            'sku' => 'VA-BP-001',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Vipers Academy Polo Shirt',
            'description' => 'Smart polo shirt for official events and presentations. Features embroidered academy logo.',
            'price' => 1800.00,
            'category' => 'Apparel',
            'images' => ['academy-polo.jpg'],
            'stock' => 50,
            'sku' => 'VA-PL-001',
            'is_active' => true,
        ]);
    }
}
