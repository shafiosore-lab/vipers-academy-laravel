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
        Schema::table('website_uploaded_players', function (Blueprint $table) {
            // Biography fields for player profile
            if (!Schema::hasColumn('website_uploaded_players', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('age');
            }
            if (!Schema::hasColumn('website_uploaded_players', 'place_of_birth')) {
                $table->string('place_of_birth')->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('website_uploaded_players', 'raised_where')) {
                $table->string('raised_where')->nullable()->after('place_of_birth');
            }
            if (!Schema::hasColumn('website_uploaded_players', 'joined_date')) {
                $table->date('joined_date')->nullable()->after('raised_where');
            }
            if (!Schema::hasColumn('website_uploaded_players', 'passion')) {
                $table->text('passion')->nullable()->after('joined_date');
            }
            if (!Schema::hasColumn('website_uploaded_players', 'favorite_thing_about_academy')) {
                $table->text('favorite_thing_about_academy')->nullable()->after('passion');
            }
            if (!Schema::hasColumn('website_uploaded_players', 'favorite_player')) {
                $table->string('favorite_player')->nullable()->after('favorite_thing_about_academy');
            }
            if (!Schema::hasColumn('website_uploaded_players', 'favorite_team')) {
                $table->string('favorite_team')->nullable()->after('favorite_player');
            }
            if (!Schema::hasColumn('website_uploaded_players', 'favorite_meal')) {
                $table->string('favorite_meal')->nullable()->after('favorite_team');
            }
            if (!Schema::hasColumn('website_uploaded_players', 'hobbies')) {
                $table->text('hobbies')->nullable()->after('favorite_meal');
            }
            if (!Schema::hasColumn('website_uploaded_players', 'role_model')) {
                $table->string('role_model')->nullable()->after('hobbies');
            }
            if (!Schema::hasColumn('website_uploaded_players', 'career_aspiration')) {
                $table->text('career_aspiration')->nullable()->after('role_model');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_uploaded_players', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'place_of_birth',
                'raised_where',
                'joined_date',
                'passion',
                'favorite_thing_about_academy',
                'favorite_player',
                'favorite_team',
                'favorite_meal',
                'hobbies',
                'role_model',
                'career_aspiration',
            ]);
        });
    }
};
