<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds fields required for tournament player registration:
     * - middle_name
     * - city/location
     * - id_number (with type: national_id, passport, birth_certificate)
     * - passport_photo_path
     * - date_of_birth (for exact age calculation)
     * - id_type
     */
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            // Check if middle_name column exists before adding
            if (!Schema::hasColumn('players', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }

            // Location
            if (!Schema::hasColumn('players', 'city')) {
                $table->string('city')->nullable()->after('middle_name');
            }

            // Identification - using separate columns for flexibility
            if (!Schema::hasColumn('players', 'id_number')) {
                $table->string('id_number')->nullable()->after('first_name');
            }

            if (!Schema::hasColumn('players', 'id_type')) {
                $table->enum('id_type', ['national_id', 'passport', 'birth_certificate', 'other'])->nullable()->after('id_number');
            }

            // Change id_number to nullable if not already
            if (Schema::hasColumn('players', 'id_number')) {
                $table->string('id_number')->nullable()->change();
            }

            // Passport photo for identity verification
            if (!Schema::hasColumn('players', 'passport_photo_path')) {
                $table->string('passport_photo_path')->nullable()->after('image_path');
            }

            // Date of birth for exact age calculation
            if (!Schema::hasColumn('players', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('age');
            }

            // Registration tracking for tournaments
            if (!Schema::hasColumn('players', 'registered_at')) {
                $table->timestamp('registered_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('players', 'registered_by')) {
                $table->unsignedBigInteger('registered_by')->nullable()->after('registered_at');
            }

            // Add indexes if they don't exist
            if (!Schema::hasIndex('players', 'players_id_type_id_number_index')) {
                $table->index(['id_type', 'id_number']);
            }
            if (!Schema::hasIndex('players', 'players_date_of_birth_index')) {
                $table->index('date_of_birth');
            }
            if (!Schema::hasIndex('players', 'players_city_index')) {
                $table->index('city');
            }
        });

        // Create tournament_team_admins table for team administrators
        Schema::create('tournament_team_admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('coach'); // coach, manager, admin
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->unique(['team_id', 'user_id']);
            $table->index('user_id');
        });

        // Create team_registrations table for self-registration
        Schema::create('team_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->string('team_name');
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->string('team_city')->nullable();
            $table->text('team_description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['tournament_id', 'status']);
            $table->index('contact_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_registrations');
        Schema::dropIfExists('tournament_team_admins');

        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn([
                'middle_name',
                'city',
                'id_type',
                'passport_photo_path',
                'date_of_birth',
                'registered_at',
                'registered_by',
            ]);

            $table->dropIndex(['players_id_type_id_number_index']);
            $table->dropIndex(['players_date_of_birth_index']);
            $table->dropIndex(['players_city_index']);
        });
    }
};
