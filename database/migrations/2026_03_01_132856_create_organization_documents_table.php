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
       Schema::create('organization_documents', function (Blueprint $table) {
    $table->id();

    // Relationships
    $table->foreignId('organization_id')
        ->constrained()
        ->onDelete('cascade');

    $table->foreignId('letterhead_id')
        ->nullable()
        ->constrained('organization_letterheads')
        ->nullOnDelete();

    $table->foreignId('created_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->foreignId('approved_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    // Document Info
    $table->string('name');
    $table->string('title')->nullable();

    $table->text('description')->nullable();

    // HTML / JSON content support
    $table->longText('content')->nullable();
    $table->json('metadata')->nullable();

    // Document Type
    $table->enum('document_type', [
        'contract',
        'policy',
        'procedure',
        'form',
        'template',
        'report',
        'letter',
        'agreement'
    ])->default('report');

    // Status
    $table->enum('status', [
        'draft',
        'pending_approval',
        'approved',
        'rejected',
        'archived',
        'expired',
        'published'
    ])->default('draft');

    // Category
    $table->enum('category', [
        'hr',
        'finance',
        'operations',
        'legal',
        'compliance',
        'marketing',
        'training',
        'administrative'
    ])->default('administrative');

    // File Information
    $table->string('file_path')->nullable();
    $table->integer('file_size')->nullable();
    $table->string('mime_type')->nullable();

    // Versioning & Metrics
    $table->string('version')->default('1.0');
    $table->integer('page_count')->default(1);
    $table->unsignedInteger('views')->default(0);

    // Flags
    $table->boolean('is_template')->default(false);

    // Approval
    $table->timestamp('approved_at')->nullable();

    $table->timestamps();
    $table->softDeletes();

    /*
    |--------------------------------------------------------------------------
    | Optimized Indexes
    |--------------------------------------------------------------------------
    */

    $table->index(
        ['organization_id', 'status'],
        'org_doc_status_idx'
    );

    $table->index(
        ['organization_id', 'document_type'],
        'org_doc_type_idx'
    );

    $table->index(
        ['organization_id', 'category'],
        'org_doc_cat_idx'
    );

    $table->index('is_template', 'org_doc_template_idx');

    $table->index('created_by', 'org_doc_creator_idx');

    $table->index('approved_by', 'org_doc_approver_idx');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_documents');
    }
};
