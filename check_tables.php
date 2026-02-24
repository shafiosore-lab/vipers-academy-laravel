<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking database tables...\n\n";

try {
    // Get all tables in the database
    $tables = DB::select('SHOW TABLES');

    if (empty($tables)) {
        echo "No tables found in database!\n";
    } else {
        echo "Tables in database:\n";
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            echo "  - $tableName\n";
        }
    }

    echo "\n";

    // Check if users table exists
    $usersExists = DB::select("SHOW TABLES LIKE 'users'");
    if (empty($usersExists)) {
        echo "✗ Users table does NOT exist\n";
    } else {
        echo "✓ Users table exists\n";

        // Try to query users table
        try {
            $users = DB::select('SELECT COUNT(*) as count FROM users');
            echo "  Users count: " . $users[0]->count . "\n";
        } catch (Exception $e) {
            echo "  ✗ Error querying users: " . $e->getMessage() . "\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
