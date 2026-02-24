<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking table engine status...\n\n";

try {
    // Check engine status for users table
    $result = DB::select("SHOW CREATE TABLE users");

    if (!empty($result)) {
        echo "Users table creation:\n";
        print_r($result[0]);
    }

    echo "\n\nChecking for corrupted tables...\n";

    // Try to check table status
    $status = DB::select("SHOW TABLE STATUS LIKE 'users'");

    if (!empty($status)) {
        echo "Users table status:\n";
        print_r($status[0]);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
