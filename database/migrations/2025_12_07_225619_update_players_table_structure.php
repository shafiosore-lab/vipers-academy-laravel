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
            // Add user_id relationship
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // Rename name to full_name
            $table->renameColumn('name', 'full_name');

            // Add missing fields
            $table->date('dob')->nullable()->after('full_name');
            $table->float('height')->nullable()->after('position');
            $table->float('weight')->nullable()->after('height');

            // Rename photo to website_image
            $table->renameColumn('photo', 'website_image');

            // Remove program_id as it's not in the required schema
            $table->dropForeign(['program_id']);
            $table->dropColumn('program_id');

            // Remove achievements as it's not in the required schema
            $table->dropColumn('achievements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            // Reverse the changes
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->renameColumn('full_name', 'name');
            $table->dropColumn(['dob', 'height', 'weight']);
            $table->renameColumn('website_image', 'photo');

            // Recreate program_id
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->text('achievements')->nullable();
        });
    }
};
