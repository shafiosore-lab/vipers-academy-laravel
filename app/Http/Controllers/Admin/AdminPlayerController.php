<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;

class AdminPlayerController extends Controller
{
    public function index()
    {
        $players = Player::all();
        return view('admin.players', compact('players'));
    }

    public function show($id)
    {
        $player = Player::findOrFail($id);
        return view('admin.player_show', compact('player'));
    }

    public function create()
    {
        return view('admin.player_create');
    }

    public function store(Request $request)
    {
        $request->validate([
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

            // Guardian Information
            'father_name' => 'nullable|string|max:255',
            'father_id_number' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'mother_name' => 'nullable|string|max:255',
            'mother_id_number' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_relationship' => 'nullable|string|max:255',

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
            'fifa_registration_number' => 'nullable|string|max:255|unique:players',
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
            'contract_end_date' => 'nullable|date|after:contract_start_date',
            'registration_status' => 'required|in:Pending,Approved,Rejected,Active,Inactive',
            'admin_notes' => 'nullable|string',
        ]);

        $data = $request->all();

        // Handle file uploads
        $fileFields = [
            'photo', 'passport_photo', 'birth_certificate', 'medical_certificate', 'school_certificate',
            'guardian_consent_form', 'participation_agreement', 'data_consent_form',
            'guardian_id_document', 'player_id_document'
        ];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('uploads/players', 'public');
            }
        }

        // Create full name from first and last name
        $data['name'] = $data['first_name'] . ' ' . $data['last_name'];

        // Calculate age from date of birth
        if (isset($data['date_of_birth'])) {
            $data['age'] = \Carbon\Carbon::parse($data['date_of_birth'])->age;
        }

        Player::create($data);

        return redirect()->route('admin.players.index')->with('success', 'Player registered successfully with FIFA compliance.');
    }

    public function edit($id)
    {
        $player = Player::findOrFail($id);
        return view('admin.player_edit', compact('player'));
    }

    public function update(Request $request, $id)
    {
        $player = Player::findOrFail($id);

        $request->validate([
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

            // Guardian Information
            'father_name' => 'nullable|string|max:255',
            'father_id_number' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'mother_name' => 'nullable|string|max:255',
            'mother_id_number' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_relationship' => 'nullable|string|max:255',

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
            'fifa_registration_number' => 'nullable|string|max:255|unique:players,fifa_registration_number,' . $id,
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
            'contract_end_date' => 'nullable|date|after:contract_start_date',
            'registration_status' => 'required|in:Pending,Approved,Rejected,Active,Inactive',
            'admin_notes' => 'nullable|string',
        ]);

        $data = $request->all();

        // Handle file uploads
        $fileFields = [
            'photo', 'passport_photo', 'birth_certificate', 'medical_certificate', 'school_certificate',
            'guardian_consent_form', 'participation_agreement', 'data_consent_form',
            'guardian_id_document', 'player_id_document'
        ];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('uploads/players', 'public');
            }
        }

        // Update full name from first and last name
        $data['name'] = $data['first_name'] . ' ' . $data['last_name'];

        // Recalculate age from date of birth
        if (isset($data['date_of_birth'])) {
            $data['age'] = \Carbon\Carbon::parse($data['date_of_birth'])->age;
        }

        $player->update($data);

        return redirect()->route('admin.players.index')->with('success', 'Player updated successfully with FIFA compliance.');
    }

    public function destroy($id)
    {
        $player = Player::findOrFail($id);
        $player->delete();

        return redirect()->route('admin.players.index')->with('success', 'Player deleted successfully.');
    }

    /**
     * Approve player with full approval
     */
    public function approve(Player $player)
    {
        $player->grantFullApproval();

        return redirect()->back()->with('success', 'Player approved with full access successfully.');
    }

    /**
     * Grant temporary approval for 5 working days
     */
    public function approveTemporary(Request $request, Player $player)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        $notes = $request->input('notes', 'Temporary approval granted for 5 working days to allow document submission.');

        $player->grantTemporaryApproval($notes);

        return redirect()->back()->with('success', 'Player granted temporary approval for 5 working days. Access will be revoked if documents are not completed by expiry date.');
    }

    /**
     * Reject player application
     */
    public function reject(Player $player)
    {
        $player->revokeApproval();

        return redirect()->back()->with('success', 'Player application rejected and returned to pending status.');
    }

    /**
     * Check and update expired temporary approvals
     */
    public function checkExpiredApprovals()
    {
        $expiredPlayers = Player::where('approval_type', 'temporary')
            ->where('temporary_approval_expires_at', '<', now())
            ->where('documents_completed', false)
            ->get();

        foreach ($expiredPlayers as $player) {
            $player->revokeApproval();
        }

        return redirect()->back()->with('success', 'Checked and updated ' . $expiredPlayers->count() . ' expired temporary approvals.');
    }
}
