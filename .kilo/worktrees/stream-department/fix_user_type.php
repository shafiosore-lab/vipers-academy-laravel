<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Check current user_type column definition
$columns = DB::select('DESCRIBE users');
foreach ($columns as $col) {
    if ($col->Field === 'user_type') {
        echo "Current user_type column type: " . $col->Type . "\n";
    }
}

// Fix the column - change to VARCHAR(50)
echo "\nAltering user_type column to VARCHAR(50)...\n";
DB::statement("ALTER TABLE users MODIFY COLUMN user_type VARCHAR(50)");

// Verify the change
$columns = DB::select('DESCRIBE users');
foreach ($columns as $col) {
    if ($col->Field === 'user_type') {
        echo "New user_type column type: " . $col->Type . "\n";
    }
}

echo "\nDone! The user_type column has been updated.\n";
