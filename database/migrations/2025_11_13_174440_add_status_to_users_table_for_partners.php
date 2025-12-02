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
        Schema::table('users', function (Blueprint $table) {
            // Only add columns that don't exist yet
            if (!Schema::hasColumn('users', 'company_description')) {
                $table->text('company_description')->nullable()->after('partner_details');
            }
            if (!Schema::hasColumn('users', 'company_website')) {
                $table->string('company_website')->nullable()->after('company_description');
            }
            if (!Schema::hasColumn('users', 'industry')) {
                $table->string('industry')->nullable()->after('company_website');
            }
            if (!Schema::hasColumn('users', 'company_size')) {
                $table->integer('company_size')->nullable()->after('industry');
            }
            if (!Schema::hasColumn('users', 'contact_person')) {
                $table->string('contact_person')->nullable()->after('company_size');
            }
            if (!Schema::hasColumn('users', 'partnership_type')) {
                $table->string('partnership_type')->nullable()->after('contact_person');
            }
            if (!Schema::hasColumn('users', 'partnership_interests')) {
                $table->text('partnership_interests')->nullable()->after('partnership_type');
            }
            if (!Schema::hasColumn('users', 'partnership_start_date')) {
                $table->date('partnership_start_date')->nullable()->after('partnership_interests');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('users', 'company_description')) {
                $columnsToDrop[] = 'company_description';
            }
            if (Schema::hasColumn('users', 'company_website')) {
                $columnsToDrop[] = 'company_website';
            }
            if (Schema::hasColumn('users', 'industry')) {
                $columnsToDrop[] = 'industry';
            }
            if (Schema::hasColumn('users', 'company_size')) {
                $columnsToDrop[] = 'company_size';
            }
            if (Schema::hasColumn('users', 'contact_person')) {
                $columnsToDrop[] = 'contact_person';
            }
            if (Schema::hasColumn('users', 'partnership_type')) {
                $columnsToDrop[] = 'partnership_type';
            }
            if (Schema::hasColumn('users', 'partnership_interests')) {
                $columnsToDrop[] = 'partnership_interests';
            }
            if (Schema::hasColumn('users', 'partnership_start_date')) {
                $columnsToDrop[] = 'partnership_start_date';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
