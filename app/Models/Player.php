<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'age',
        'position',
        'bio',
        'achievements',
        'photo',
        'program_id',
        'date_of_birth',
        'place_of_birth',
        'nationality',
        'gender',
        'address',
        'city',
        'country',
        'postal_code',
        'phone',
        'email',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'father_name',
        'father_id_number',
        'father_phone',
        'father_occupation',
        'mother_name',
        'mother_id_number',
        'mother_phone',
        'mother_occupation',
        'guardian_name',
        'guardian_phone',
        'guardian_relationship',
        'guardian_consent_form',
        'participation_agreement',
        'data_consent_form',
        'safeguarding_policy_acknowledged',
        'accommodation_provided',
        'accommodation_details',
        'age_group',
        'training_schedule',
        'competition_plan',
        'guardian_id_document',
        'player_id_document',
        'previous_domicile',
        'relocation_reason',
        'medical_conditions',
        'allergies',
        'blood_type',
        'medical_insurance_provider',
        'medical_insurance_number',
        'last_medical_checkup',
        'medications',
        'height_cm',
        'weight_kg',
        'dominant_foot',
        'fifa_registration_number',
        'license_type',
        'registration_date',
        'previous_clubs',
        'transfer_status',
        'contract_status',
        'school_name',
        'school_grade',
        'academic_performance',
        'academic_gpa',
        'academic_notes',
        'passport_photo',
        'birth_certificate',
        'medical_certificate',
        'school_certificate',
        'registration_status',
        'admin_notes',
        'contract_start_date',
        'contract_end_date',
        'approval_type',
        'temporary_approval_granted_at',
        'temporary_approval_expires_at',
        'temporary_approval_notes',
        'documents_completed',
        'partner_id',
        'matches_played',
        'goals_scored',
        'assists',
        'performance_rating',
        'performance_notes',
        'last_follow_up',
        'follow_up_notes',
        'needs_attention',
        'attention_reason',
        'development_stage',
        'international_eligible',
        'has_professional_contract',
        'contract_team',
        'contract_type',
        'milestones',
        'academy_join_date',
        'current_level',
        'preferred_position',
        'image', // Added for view compatibility
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'last_medical_checkup' => 'date',
        'registration_date' => 'date',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'academy_join_date' => 'date',
        'temporary_approval_granted_at' => 'datetime',
        'temporary_approval_expires_at' => 'datetime',
        'last_follow_up' => 'date',
        'milestones' => 'array',
        'safeguarding_policy_acknowledged' => 'boolean',
        'accommodation_provided' => 'boolean',
        'documents_completed' => 'boolean',
        'needs_attention' => 'boolean',
        'international_eligible' => 'boolean',
        'has_professional_contract' => 'boolean',
        'height_cm' => 'decimal:2',
        'weight_kg' => 'decimal:2',
        'performance_rating' => 'decimal:2',
        'academic_gpa' => 'decimal:2',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    // Accessor for jersey_number (assuming it's not in DB, maybe derived)
    public function getJerseyNumberAttribute()
    {
        return $this->id % 100; // Placeholder, adjust as needed
    }
}
