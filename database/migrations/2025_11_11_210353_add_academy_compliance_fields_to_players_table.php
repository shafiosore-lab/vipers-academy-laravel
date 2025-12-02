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
            // Academy Compliance Fields (Additional to FIFA requirements)

            // Guardian Consent and Legal Documents
            $table->string('guardian_consent_form')->nullable()->after('guardian_relationship');
            $table->string('participation_agreement')->nullable()->after('guardian_consent_form');
            $table->string('data_consent_form')->nullable()->after('participation_agreement');
            $table->boolean('safeguarding_policy_acknowledged')->default(false)->after('data_consent_form');

            // Accommodation Information
            $table->boolean('accommodation_provided')->default(false)->after('safeguarding_policy_acknowledged');
            $table->text('accommodation_details')->nullable()->after('accommodation_provided');

            // Training and Competition Information
            $table->string('age_group')->nullable()->after('accommodation_details');
            $table->text('training_schedule')->nullable()->after('age_group');
            $table->text('competition_plan')->nullable()->after('training_schedule');

            // Additional Identity Documents
            $table->string('guardian_id_document')->nullable()->after('competition_plan');
            $table->string('player_id_document')->nullable()->after('guardian_id_document');

            // Previous Domicile (for relocated players)
            $table->string('previous_domicile')->nullable()->after('player_id_document');
            $table->text('relocation_reason')->nullable()->after('previous_domicile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn([
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
                'relocation_reason'
            ]);
        });
    }
};
