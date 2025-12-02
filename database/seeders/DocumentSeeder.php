<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            [
                'document_id' => 'player_coc_' . strtoupper(substr(md5('player_coc'), 0, 8)),
                'title' => 'Player Code of Conduct',
                'description' => 'Official code of conduct for all players covering behavior, sportsmanship, and expectations.',
                'category' => 'codes_of_conduct',
                'subcategory' => 'player_conduct',
                'file_path' => 'documents/player_code_of_conduct_v1.pdf',
                'file_name' => 'player_code_of_conduct_v1.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 245760,
                'language' => 'en',
                'version' => '1.0',
                'is_mandatory' => true,
                'target_roles' => ['player'],
                'requires_signature' => true,
                'expiry_days' => 365,
                'is_active' => true,
                'metadata' => ['compliance_level' => 'high'],
                'published_at' => now(),
            ],
            [
                'document_id' => 'handbook_' . strtoupper(substr(md5('handbook'), 0, 8)),
                'title' => 'Academy Handbook',
                'description' => 'Comprehensive handbook with academy rules, expectations, and important guidelines.',
                'category' => 'academy_information',
                'subcategory' => 'policies_manual',
                'file_path' => 'documents/academy_handbook_v2.pdf',
                'file_name' => 'academy_handbook_v2.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 567890,
                'language' => 'en',
                'version' => '2.0',
                'is_mandatory' => true,
                'target_roles' => ['player', 'parent', 'coach'],
                'requires_signature' => true,
                'expiry_days' => 365,
                'is_active' => true,
                'metadata' => ['compliance_level' => 'high'],
                'published_at' => now(),
            ],
            [
                'document_id' => 'safety_' . strtoupper(substr(md5('safety'), 0, 8)),
                'title' => 'Child Safety & Protection Policy',
                'description' => 'Comprehensive policy for protecting children and providing safe academy environments.',
                'category' => 'safety_protection',
                'subcategory' => 'child_protection',
                'file_path' => 'documents/child_safety_protection_policy_v1.pdf',
                'file_name' => 'child_safety_protection_policy_v1.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 456789,
                'language' => 'en',
                'version' => '1.0',
                'is_mandatory' => true,
                'target_roles' => ['player', 'parent', 'coach', 'staff', 'admin'],
                'requires_signature' => true,
                'expiry_days' => 365,
                'is_active' => true,
                'metadata' => ['compliance_level' => 'critical'],
                'published_at' => now(),
            ],
            [
                'document_id' => 'registration_' . strtoupper(substr(md5('registration'), 0, 8)),
                'title' => 'Player Registration Form',
                'description' => 'Official player registration form with personal, contact, and medical details.',
                'category' => 'contracts_agreements',
                'subcategory' => 'registration_forms',
                'file_path' => 'documents/player_registration_form_v1.pdf',
                'file_name' => 'player_registration_form_v1.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 145234,
                'language' => 'en',
                'version' => '1.0',
                'is_mandatory' => true,
                'target_roles' => ['player', 'parent'],
                'requires_signature' => true,
                'expiry_days' => 365,
                'is_active' => true,
                'metadata' => ['compliance_level' => 'critical'],
                'published_at' => now(),
            ],
            [
                'document_id' => 'emergency_' . strtoupper(substr(md5('emergency'), 0, 8)),
                'title' => 'Emergency Action Plan',
                'description' => 'Comprehensive emergency response procedures for medical and safety situations.',
                'category' => 'safety_protection',
                'subcategory' => 'emergency_procedures',
                'file_path' => 'documents/emergency_action_plan_v1.pdf',
                'file_name' => 'emergency_action_plan_v1.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 324567,
                'language' => 'en',
                'version' => '1.0',
                'is_mandatory' => true,
                'target_roles' => ['player', 'parent', 'coach', 'staff', 'admin'],
                'requires_signature' => true,
                'expiry_days' => 365,
                'is_active' => true,
                'metadata' => ['compliance_level' => 'critical'],
                'published_at' => now(),
            ],
        ];

        foreach ($documents as $documentData) {
            if (isset($documentData['metadata'])) {
                $documentData['metadata'] = json_encode($documentData['metadata']);
            }
            Document::create($documentData);
        }
    }
}
