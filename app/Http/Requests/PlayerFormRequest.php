<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Consolidated Form Request for Player validation
 *
 * This consolidates duplicate validation rules from:
 * - AdminPlayerController::store() and update()
 * - PartnerController::storePlayer() and updatePlayer()
 */
class PlayerFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param bool $isUpdate Whether this is an update request (affects unique rules)
     * @param int|null $playerId The player ID for update requests
     * @return array
     */
    public function rules(bool $isUpdate = false, ?int $playerId = null): array
    {
        $baseRules = [
            // Basic Information
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'place_of_birth' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'position' => 'required|string|max:255',

            // Contact Information
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:255',

            // Medical Information
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'blood_type' => 'nullable|string|max:10',
            'medical_insurance_provider' => 'nullable|string|max:255',
            'medical_insurance_number' => 'nullable|string|max:255',
            'last_medical_checkup' => 'nullable|date',
            'medications' => 'nullable|string',

            // Physical Information
            'height_cm' => 'nullable|numeric|min:50|max:250',
            'weight_kg' => 'nullable|numeric|min:20|max:200',
            'dominant_foot' => 'nullable|in:Left,Right,Both',

            // Football Registration
            'fifa_registration_number' => 'nullable|string|max:255',
            'license_type' => 'nullable|string|max:255',
            'registration_date' => 'nullable|date',
            'previous_clubs' => 'nullable|string',
            'transfer_status' => 'nullable|string|max:255',
            'contract_status' => 'nullable|string|max:255',

            // Academic Information
            'school_name' => 'nullable|string|max:255',
            'school_grade' => 'nullable|string|max:255',
            'academic_performance' => 'nullable|string|max:255',

            // Images and Documents
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'passport_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:5120',
            'medical_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:5120',
            'school_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:5120',

            // Academy Compliance - Legal Documents
            'guardian_consent_form' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:5120',
            'participation_agreement' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:5120',
            'data_consent_form' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:5120',
            'safeguarding_policy_acknowledged' => 'required|boolean',

            // Academy Compliance - Identity Documents
            'guardian_id_document' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:5120',
            'player_id_document' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:5120',

            // Academy Compliance - Accommodation
            'accommodation_provided' => 'required|boolean',
            'accommodation_details' => 'nullable|string',

            // Academy Compliance - Training & Competition
            'age_group' => 'nullable|string|max:255',
            'training_schedule' => 'nullable|string',
            'competition_plan' => 'nullable|string',

            // Academy Compliance - Previous Domicile
            'previous_domicile' => 'nullable|string|max:255',
            'relocation_reason' => 'nullable|string',

            // Contract Information
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date',
            'registration_status' => 'required|in:Pending,Approved,Rejected,Active,Inactive',
            'admin_notes' => 'nullable|string',
        ];

        // Add unique rule for FIFA registration number on update
        if ($isUpdate && $playerId) {
            $baseRules['fifa_registration_number'] = 'nullable|string|max:255|unique:players,fifa_registration_number,' . $playerId;
        }

        return $baseRules;
    }

    /**
     * Get basic validation rules (for partner simplified form)
     * Used by PartnerController
     *
     * @param bool $isUpdate Whether this is an update request
     * @return array
     */
    public function basicRules(bool $isUpdate = false): array
    {
        return [
            // Basic Information
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'place_of_birth' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'position' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',

            // Contact Information
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:255',

            // Guardian Information
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'guardian_relationship' => 'required|string|max:255',

            // Medical Information
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'blood_type' => 'nullable|string|max:10',

            // Academic Information
            'school_name' => 'nullable|string|max:255',
            'school_grade' => 'nullable|string|max:255',

            // Physical Information
            'height_cm' => 'nullable|numeric|min:50|max:250',
            'weight_kg' => 'nullable|numeric|min:20|max:200',
            'dominant_foot' => 'nullable|in:Left,Right,Both',
        ];
    }

    /**
     * Get file fields that should be processed
     *
     * @return string[]
     */
    public function getFileFields(): array
    {
        return [
            'photo', 'passport_photo', 'birth_certificate', 'medical_certificate', 'school_certificate',
            'guardian_consent_form', 'participation_agreement', 'data_consent_form',
            'guardian_id_document', 'player_id_document'
        ];
    }

    /**
     * Prepare the data for validation and storage
     * Handles common data transformations
     *
     * @return array
     */
    public function prepareData(): array
    {
        $data = $this->all();

        // Create full name from first and last name
        $data['full_name'] = $data['first_name'] . ' ' . $data['last_name'];

        // Calculate age from date of birth
        if (isset($data['date_of_birth'])) {
            $data['age'] = \Carbon\Carbon::parse($data['date_of_birth'])->age;
        }

        // Handle file uploads
        foreach ($this->getFileFields() as $field) {
            if ($this->hasFile($field)) {
                $data[$field] = $this->file($field)->store('uploads/players', 'public');
            }
        }

        return $data;
    }
}
