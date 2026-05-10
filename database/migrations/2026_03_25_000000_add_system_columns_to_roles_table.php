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
            Schema::table('roles', function (Blueprint $table) {

                // System and template flags
                if (!Schema::hasColumn('roles', 'is_system')) {
                    $table->boolean('is_system')->default(false)->after('type');
                }

                if (!Schema::hasColumn('roles', 'is_template')) {
                    $table->boolean('is_template')->default(false);
                }

                if (!Schema::hasColumn('roles', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }

                // Organization scoping
                if (!Schema::hasColumn('roles', 'organization_id')) {
                    $table->unsignedBigInteger('organization_id')->nullable();

                    $table->foreign('organization_id')
                        ->references('id')
                        ->on('organizations')
                        ->onDelete('cascade');
                }

                // Role hierarchy
                if (!Schema::hasColumn('roles', 'parent_role_id')) {
                    $table->unsignedBigInteger('parent_role_id')->nullable();

                    $table->foreign('parent_role_id')
                        ->references('id')
                        ->on('roles')
                        ->onDelete('set null');
                }

                if (!Schema::hasColumn('roles', 'inherit_permissions')) {
                    $table->boolean('inherit_permissions')->default(true);
                }

                // Department and module
                if (!Schema::hasColumn('roles', 'department')) {
                    $table->string('department')->nullable();
                }

                if (!Schema::hasColumn('roles', 'module')) {
                    $table->string('module')->nullable();
                }

                // Additional fields
                if (!Schema::hasColumn('roles', 'name_key')) {
                    $table->string('name_key')->nullable();
                }

                if (!Schema::hasColumn('roles', 'description_key')) {
                    $table->string('description_key')->nullable();
                }

                if (!Schema::hasColumn('roles', 'combined_role_ids')) {
                    $table->text('combined_role_ids')->nullable();
                }

                if (!Schema::hasColumn('roles', 'metadata')) {
                    $table->json('metadata')->nullable();
                }
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropForeign(['parent_role_id']);
            $table->dropColumn([
                'is_system',
                'is_template',
                'is_active',
                'organization_id',
                'parent_role_id',
                'inherit_permissions',
                'department',
                'module',
                'name_key',
                'description_key',
                'combined_role_ids',
                'metadata',
            ]);
        });
    }
};
