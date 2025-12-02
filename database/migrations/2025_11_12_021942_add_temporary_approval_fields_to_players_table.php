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
            $table->enum('approval_type', ['full', 'temporary', 'none'])->default('none')->after('registration_status');
            $table->timestamp('temporary_approval_granted_at')->nullable()->after('approval_type');
            $table->timestamp('temporary_approval_expires_at')->nullable()->after('temporary_approval_granted_at');
            $table->text('temporary_approval_notes')->nullable()->after('temporary_approval_expires_at');
            $table->boolean('documents_completed')->default(false)->after('temporary_approval_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn([
                'approval_type',
                'temporary_approval_granted_at',
                'temporary_approval_expires_at',
                'temporary_approval_notes',
                'documents_completed'
            ]);
        });
    }
};
