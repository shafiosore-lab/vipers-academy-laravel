<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

echo "Starting database initialization...\n";

// Create migrations table
if (!Schema::hasTable('migrations')) {
    Schema::create('migrations', function (Blueprint $table) {
        $table->id();
        $table->string('migration');
        $table->integer('batch');
    });
    echo "✓ Created migrations table\n";
} else {
    echo "✓ Migrations table already exists\n";
}

echo "\nRunning Laravel migrations...\n";
