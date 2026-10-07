<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_approval_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('request_type', ['create', 'update', 'delete']);
            $table->text('old_values')->nullable(); // JSON of old values
            $table->text('new_values')->nullable(); // JSON of new values
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // Add fields for tracking who created/updated payments
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->boolean('needs_approval')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_approval_requests');

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['created_by', 'updated_by', 'approved_by', 'approved_at', 'needs_approval']);
        });
    }
};
