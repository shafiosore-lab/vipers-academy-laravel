<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        try {
            Schema::table('attendances', function (Blueprint $table) {
                // Check current column type and change if needed
                $columns = \DB::getSchemaBuilder()->getColumnListing('attendances');

                // Only change if columns exist and are still time type
                if (in_array('check_in_time', $columns)) {
                    $columnType = \DB::select("SHOW COLUMNS FROM attendances WHERE Field = 'check_in_time'")[0]->Type ?? '';
                    if (str_contains($columnType, 'time') && !str_contains($columnType, 'datetime')) {
                        $table->dateTime('check_in_time')->nullable()->change();
                    }
                }

                if (in_array('check_out_time', $columns)) {
                    $columnType = \DB::select("SHOW COLUMNS FROM attendances WHERE Field = 'check_out_time'")[0]->Type ?? '';
                    if (str_contains($columnType, 'time') && !str_contains($columnType, 'datetime')) {
                        $table->dateTime('check_out_time')->nullable()->change();
                    }
                }
            });
        } catch (\Exception $e) {
            // Table may not have these columns or doctrine/dbal not installed
        }
    }

    public function down(): void
    {
        // Keep for rollback compatibility
    }
};
