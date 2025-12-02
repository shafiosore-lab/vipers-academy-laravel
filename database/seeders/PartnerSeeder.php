<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample partners
        $partners = [
            [
                'name' => 'Mumias Sugar Company',
                'email' => 'partnership@mumiassugar.co.ke',
                'password' => Hash::make('password'),
                'user_type' => 'partner',
                'status' => 'active',
                'company_description' => 'Mumias Sugar Company is Kenya\'s leading sugar producer, committed to sustainable agriculture and community development. As a proud partner of Vipers Academy, we provide comprehensive support for youth development through football.',
                'company_website' => 'https://www.mumiassugar.co.ke',
                'industry' => 'Agriculture & Manufacturing',
                'company_size' => 5000,
                'contact_person' => 'Dr. Erick Kiprotich',
                'partnership_type' => 'platinum_sponsor',
                'partnership_interests' => 'Youth development, community outreach, sports sponsorship, educational programs',
                'partnership_start_date' => now()->subMonths(24),
                'partner_details' => json_encode([
                    'sponsorship_level' => 'Platinum',
                    'annual_contribution' => 5000000,
                    'benefits' => ['Logo on academy jerseys', 'VIP match tickets', 'Community outreach programs', 'Player scholarships'],
                    'contact_title' => 'Chief Executive Officer',
                    'phone' => '+254 700 000 001',
                    'address' => 'Mumias, Kenya'
                ])
            ],
            [
                'name' => 'Safaricom PLC',
                'email' => 'corporate@safaricom.co.ke',
                'password' => Hash::make('password'),
                'user_type' => 'partner',
                'status' => 'active',
                'company_description' => 'Safaricom PLC is Kenya\'s leading telecommunications company, driving digital innovation and connectivity across East Africa. Our partnership with Vipers Academy focuses on technology-driven youth development.',
                'company_website' => 'https://www.safaricom.co.ke',
                'industry' => 'Telecommunications',
                'company_size' => 8000,
                'contact_person' => 'Peter Ndegwa',
                'partnership_type' => 'technology_partner',
                'partnership_interests' => 'Digital literacy, technology education, innovation in sports, youth empowerment',
                'partnership_start_date' => now()->subMonths(18),
                'partner_details' => json_encode([
                    'sponsorship_level' => 'Gold',
                    'annual_contribution' => 3000000,
                    'benefits' => ['Technology workshops', 'Digital academy platform', 'Innovation grants', 'STEM programs'],
                    'contact_title' => 'Corporate Social Responsibility Manager',
                    'phone' => '+254 700 000 002',
                    'address' => 'Nairobi, Kenya'
                ])
            ],
            [
                'name' => 'Equity Bank Kenya',
                'email' => 'foundation@equitybank.co.ke',
                'password' => Hash::make('password'),
                'user_type' => 'partner',
                'status' => 'active',
                'company_description' => 'Equity Bank Kenya is East Africa\'s largest financial services provider, committed to financial inclusion and community development. Our partnership supports youth entrepreneurship and financial literacy.',
                'company_website' => 'https://www.equitybank.co.ke',
                'industry' => 'Financial Services',
                'company_size' => 12000,
                'contact_person' => 'Grace Wanjiku',
                'partnership_type' => 'education_partner',
                'partnership_interests' => 'Financial literacy, entrepreneurship education, youth development, community banking',
                'partnership_start_date' => now()->subMonths(12),
                'partner_details' => json_encode([
                    'sponsorship_level' => 'Gold',
                    'annual_contribution' => 2500000,
                    'benefits' => ['Financial literacy workshops', 'Youth entrepreneurship programs', 'Academy banking services', 'Scholarship funding'],
                    'contact_title' => 'Head of Corporate Social Responsibility',
                    'phone' => '+254 700 000 003',
                    'address' => 'Nairobi, Kenya'
                ])
            ],
            [
                'name' => 'KCB Group PLC',
                'email' => 'csr@kcbgroup.com',
                'password' => Hash::make('password'),
                'user_type' => 'partner',
                'status' => 'active',
                'company_description' => 'KCB Group PLC is East Africa\'s largest banking group, providing comprehensive financial services. Our partnership with Vipers Academy focuses on youth financial empowerment and sports development.',
                'company_website' => 'https://www.kcbgroup.com',
                'industry' => 'Banking & Financial Services',
                'company_size' => 10000,
                'contact_person' => 'David Kiprop',
                'partnership_type' => 'platinum_sponsor',
                'partnership_interests' => 'Youth banking, financial education, sports sponsorship, community development',
                'partnership_start_date' => now()->subMonths(20),
                'partner_details' => json_encode([
                    'sponsorship_level' => 'Platinum',
                    'annual_contribution' => 4500000,
                    'benefits' => ['Youth banking accounts', 'Financial education programs', 'Match day hospitality', 'Player development support'],
                    'contact_title' => 'Group Head of Corporate Social Responsibility',
                    'phone' => '+254 700 000 004',
                    'address' => 'Nairobi, Kenya'
                ])
            ],
            [
                'name' => 'Nairobi Business Park',
                'email' => 'partnerships@nairobibusinesspark.com',
                'password' => Hash::make('password'),
                'user_type' => 'partner',
                'status' => 'active',
                'company_description' => 'Nairobi Business Park is Kenya\'s premier business district, home to leading corporations and innovative startups. We partner with Vipers Academy to bridge business and sports excellence.',
                'company_website' => 'https://www.nairobibusinesspark.com',
                'industry' => 'Real Estate & Business Development',
                'company_size' => 2000,
                'contact_person' => 'Sarah Kiprop',
                'partnership_type' => 'facility_partner',
                'partnership_interests' => 'Business mentorship, internship programs, facility development, youth career guidance',
                'partnership_start_date' => now()->subMonths(8),
                'partner_details' => json_encode([
                    'sponsorship_level' => 'Silver',
                    'annual_contribution' => 1500000,
                    'benefits' => ['Internship opportunities', 'Business mentorship', 'Career guidance workshops', 'Facility usage'],
                    'contact_title' => 'Partnerships Manager',
                    'phone' => '+254 700 000 005',
                    'address' => 'Nairobi, Kenya'
                ])
            ],
            [
                'name' => 'Kenya Commercial Bank Foundation',
                'email' => 'foundation@kcb.co.ke',
                'password' => Hash::make('password'),
                'user_type' => 'partner',
                'status' => 'active',
                'company_description' => 'The Kenya Commercial Bank Foundation supports education, health, and community development initiatives across Kenya. Our partnership with Vipers Academy focuses on holistic youth development.',
                'company_website' => 'https://www.kcb.co.ke/foundation',
                'industry' => 'Corporate Foundation',
                'company_size' => 500,
                'contact_person' => 'Dr. Mary Wambui',
                'partnership_type' => 'education_partner',
                'partnership_interests' => 'Education support, health programs, community development, youth empowerment',
                'partnership_start_date' => now()->subMonths(15),
                'partner_details' => json_encode([
                    'sponsorship_level' => 'Gold',
                    'annual_contribution' => 2000000,
                    'benefits' => ['Educational scholarships', 'Health programs', 'Community development projects', 'Youth empowerment initiatives'],
                    'contact_title' => 'Executive Director',
                    'phone' => '+254 700 000 006',
                    'address' => 'Nairobi, Kenya'
                ])
            ],
            [
                'name' => 'Coca-Cola Kenya',
                'email' => 'partnerships@coca-cola.co.ke',
                'password' => Hash::make('password'),
                'user_type' => 'partner',
                'status' => 'active',
                'company_description' => 'Coca-Cola Kenya brings refreshment and happiness to millions while supporting community development. Our partnership with Vipers Academy promotes active lifestyles and youth development.',
                'company_website' => 'https://www.coca-cola.co.ke',
                'industry' => 'Beverages & Consumer Goods',
                'company_size' => 3000,
                'contact_person' => 'James Kiprop',
                'partnership_type' => 'sports_sponsor',
                'partnership_interests' => 'Sports nutrition, healthy lifestyles, youth development, community engagement',
                'partnership_start_date' => now()->subMonths(10),
                'partner_details' => json_encode([
                    'sponsorship_level' => 'Silver',
                    'annual_contribution' => 1800000,
                    'benefits' => ['Sports drinks supply', 'Nutrition education', 'Healthy lifestyle campaigns', 'Community events'],
                    'contact_title' => 'Marketing Manager',
                    'phone' => '+254 700 000 007',
                    'address' => 'Nairobi, Kenya'
                ])
            ],
            [
                'name' => 'Microsoft Kenya',
                'email' => 'education@microsoft.co.ke',
                'password' => Hash::make('password'),
                'user_type' => 'partner',
                'status' => 'pending',
                'company_description' => 'Microsoft Kenya drives digital transformation across East Africa. Our partnership with Vipers Academy focuses on digital skills development and technology education for youth.',
                'company_website' => 'https://www.microsoft.com/en-us/africa/kenya',
                'industry' => 'Technology & Software',
                'company_size' => 1500,
                'contact_person' => 'Ann Wairimu',
                'partnership_type' => 'technology_partner',
                'partnership_interests' => 'Digital skills training, coding education, technology access, innovation programs',
                'partnership_start_date' => now()->subMonths(2),
                'partner_details' => json_encode([
                    'sponsorship_level' => 'Gold',
                    'annual_contribution' => 2800000,
                    'benefits' => ['Coding workshops', 'Technology training', 'Digital literacy programs', 'Innovation challenges'],
                    'contact_title' => 'Education Program Manager',
                    'phone' => '+254 700 000 008',
                    'address' => 'Nairobi, Kenya'
                ])
            ],
            [
                'name' => 'Toyota Kenya',
                'email' => 'csr@toyota.co.ke',
                'password' => Hash::make('password'),
                'user_type' => 'partner',
                'status' => 'active',
                'company_description' => 'Toyota Kenya is the leading automotive company in East Africa, committed to road safety and community development. Our partnership supports youth mobility and safety education.',
                'company_website' => 'https://www.toyota.co.ke',
                'industry' => 'Automotive',
                'company_size' => 2500,
                'contact_person' => 'Peter Mwangi',
                'partnership_type' => 'facility_partner',
                'partnership_interests' => 'Road safety education, youth mobility, community development, skills training',
                'partnership_start_date' => now()->subMonths(14),
                'partner_details' => json_encode([
                    'sponsorship_level' => 'Silver',
                    'annual_contribution' => 1600000,
                    'benefits' => ['Road safety workshops', 'Driving skills training', 'Vehicle donations', 'Mobility programs'],
                    'contact_title' => 'Corporate Social Responsibility Manager',
                    'phone' => '+254 700 000 009',
                    'address' => 'Nairobi, Kenya'
                ])
            ],
            [
                'name' => 'Nairobi County Government',
                'email' => 'youth@nairobi.go.ke',
                'password' => Hash::make('password'),
                'user_type' => 'partner',
                'status' => 'active',
                'company_description' => 'Nairobi County Government supports youth development and sports initiatives across the county. Our partnership with Vipers Academy promotes grassroots football development.',
                'company_website' => 'https://www.nairobi.go.ke',
                'industry' => 'Government',
                'company_size' => 15000,
                'contact_person' => 'Hon. Susan Kiprop',
                'partnership_type' => 'government_partner',
                'partnership_interests' => 'Youth development, sports infrastructure, community programs, education support',
                'partnership_start_date' => now()->subMonths(22),
                'partner_details' => json_encode([
                    'sponsorship_level' => 'Platinum',
                    'annual_contribution' => 6000000,
                    'benefits' => ['Facility development', 'Youth programs funding', 'Community outreach', 'Sports infrastructure'],
                    'contact_title' => 'County Executive for Youth and Sports',
                    'phone' => '+254 700 000 010',
                    'address' => 'Nairobi, Kenya'
                ])
            ]
        ];

        foreach ($partners as $partnerData) {
            User::create($partnerData);
        }
    }
}
