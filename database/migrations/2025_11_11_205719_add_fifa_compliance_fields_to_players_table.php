<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            // Personal Information (FIFA Required)
            $table->string('first_name')->after('name');
            $table->string('last_name')->after('first_name');
            $table->date('date_of_birth')->nullable()->after('age');
            $table->string('place_of_birth')->nullable()->after('date_of_birth');
            $table->string('nationality')->nullable()->after('place_of_birth');
            $table->enum('gender', ['Male', 'Female'])->nullable()->after('nationality');

            // Contact Information
            $table->text('address')->nullable()->after('gender');
            $table->string('city')->nullable()->after('address');
            $table->string('country')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('country');
            $table->string('phone')->nullable()->after('postal_code');
            $table->string('email')->nullable()->after('phone');
            $table->string('emergency_contact_name')->nullable()->after('email');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_phone');

            // Guardian/Parent Information
            $table->string('father_name')->nullable()->after('emergency_contact_relationship');
            $table->string('father_phone')->nullable()->after('father_name');
            $table->string('father_occupation')->nullable()->after('father_phone');
            $table->string('mother_name')->nullable()->after('father_occupation');
            $table->string('mother_phone')->nullable()->after('mother_name');
            $table->string('mother_occupation')->nullable()->after('mother_phone');
            $table->string('guardian_name')->nullable()->after('mother_occupation');
            $table->string('guardian_phone')->nullable()->after('guardian_name');
            $table->string('guardian_relationship')->nullable()->after('guardian_phone');

            // Medical Information (FIFA Required)
            $table->text('medical_conditions')->nullable()->after('guardian_relationship');
            $table->text('allergies')->nullable()->after('medical_conditions');
            $table->string('blood_type')->nullable()->after('allergies');
            $table->string('medical_insurance_provider')->nullable()->after('blood_type');
            $table->string('medical_insurance_number')->nullable()->after('medical_insurance_provider');
            $table->date('last_medical_checkup')->nullable()->after('medical_insurance_number');
            $table->text('medications')->nullable()->after('last_medical_checkup');

            // Physical Information
            $table->decimal('height_cm', 5, 2)->nullable()->after('medications');
            $table->decimal('weight_kg', 5, 2)->nullable()->after('height_cm');
            $table->enum('dominant_foot', ['Left', 'Right', 'Both'])->nullable()->after('weight_kg');

            // Football Registration Information (FIFA Required)
            $table->string('fifa_registration_number')->nullable()->after('dominant_foot');
            $table->string('license_type')->nullable()->after('fifa_registration_number');
            $table->date('registration_date')->nullable()->after('license_type');
            $table->string('previous_clubs')->nullable()->after('registration_date');
            $table->string('transfer_status')->nullable()->after('previous_clubs');
            $table->string('contract_status')->nullable()->after('transfer_status');

            // Academic Information
            $table->string('school_name')->nullable()->after('contract_status');
            $table->string('school_grade')->nullable()->after('school_name');
            $table->string('academic_performance')->nullable()->after('school_grade');

            // Additional Images/Documents
            $table->string('passport_photo')->nullable()->after('photo');
            $table->string('birth_certificate')->nullable()->after('passport_photo');
            $table->string('medical_certificate')->nullable()->after('birth_certificate');
            $table->string('school_certificate')->nullable()->after('medical_certificate');

            // Status and Notes
            $table->enum('registration_status', ['Pending', 'Approved', 'Rejected', 'Active', 'Inactive'])->default('Pending')->after('school_certificate');
            $table->text('admin_notes')->nullable()->after('registration_status');
            $table->date('contract_start_date')->nullable()->after('admin_notes');
            $table->date('contract_end_date')->nullable()->after('contract_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'date_of_birth', 'place_of_birth', 'nationality', 'gender',
                'address', 'city', 'country', 'postal_code', 'phone', 'email',
                'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship',
                'father_name', 'father_phone', 'father_occupation', 'mother_name', 'mother_phone', 'mother_occupation',
                'guardian_name', 'guardian_phone', 'guardian_relationship',
                'medical_conditions', 'allergies', 'blood_type', 'medical_insurance_provider', 'medical_insurance_number',
                'last_medical_checkup', 'medications', 'height_cm', 'weight_kg', 'dominant_foot',
                'fifa_registration_number', 'license_type', 'registration_date', 'previous_clubs', 'transfer_status', 'contract_status',
                'school_name', 'school_grade', 'academic_performance',
                'passport_photo', 'birth_certificate', 'medical_certificate', 'school_certificate',
                'registration_status', 'admin_notes', 'contract_start_date', 'contract_end_date'
            ]);
        });
    }
};
