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
        Schema::table('user_documents', function (Blueprint $table) {
            // Fields for user-uploaded documents
            $table->string('document_type')->nullable()->after('document_id'); // Type of uploaded document (national_id, birth_certificate, etc.)
            $table->string('file_path')->nullable()->after('document_type'); // Path to uploaded file
            $table->string('file_name')->nullable()->after('file_path'); // Original filename
            $table->string('mime_type')->nullable()->after('file_name'); // File MIME type
            $table->bigInteger('file_size')->nullable()->after('mime_type'); // File size in bytes
            $table->timestamp('uploaded_at')->nullable()->after('file_size'); // When file was uploaded
            $table->text('review_notes')->nullable()->after('notes'); // Admin review notes
            $table->timestamp('reviewed_at')->nullable()->after('review_notes'); // When document was reviewed
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('reviewed_at'); // Admin who reviewed

            // Update status enum to include upload statuses
            $table->enum('status', [
                'pending',
                'viewed',
                'downloaded',
                'signed',
                'expired',
                'rejected',
                'pending_review', // For uploaded documents waiting admin review
                'approved' // For approved uploaded documents
            ])->default('pending')->change();

            // Update unique constraint to allow multiple document types per user
            $table->dropUnique(['user_id', 'document_id']);
            $table->unique(['user_id', 'document_id']); // Keep for official documents
            $table->unique(['user_id', 'document_type']); // For uploaded documents
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_documents', function (Blueprint $table) {
            // Remove added columns
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn([
                'document_type',
                'file_path',
                'file_name',
                'mime_type',
                'file_size',
                'uploaded_at',
                'review_notes',
                'reviewed_at',
                'reviewed_by'
            ]);

            // Revert status enum
            $table->enum('status', ['pending', 'viewed', 'downloaded', 'signed', 'expired', 'rejected'])->default('pending')->change();

            // Restore original unique constraint
            $table->dropUnique(['user_id', 'document_type']);
        });
    }
};
