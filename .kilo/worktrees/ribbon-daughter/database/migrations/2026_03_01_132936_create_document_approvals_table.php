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
        Schema::create('document_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_document_id')->constrained()->onDelete('cascade');
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', array_keys(\App\Models\DocumentApproval::getStatuses()))
                ->default('pending');
            $table->text('comments')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->integer('required_approval_level')->default(1);
            $table->integer('approval_sequence')->default(1);
            $table->boolean('is_final_approval')->default(false);
            $table->timestamps();

            // Indexes for performance
            $table->index(['organization_document_id', 'status']);
            $table->index(['approver_id', 'status']);
            $table->index('required_approval_level');
            $table->index('approval_sequence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_approvals');
    }
};
