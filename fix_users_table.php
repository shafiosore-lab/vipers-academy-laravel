<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Attempting to fix corrupted users table...\n\n";

try {
    // First, try to discard the tablespace if it exists
    echo "Discarding tablespace...\n";
    try {
        DB::statement('ALTER TABLE users DISCARD TABLESPACE');
    } catch (Exception $e) {
        echo "Note: " . $e->getMessage() . "\n";
    }

    // Drop the corrupted table
    echo "Dropping users table...\n";
    DB::statement('DROP TABLE IF EXISTS users');
    echo "✓ Dropped users table\n";

    // Create users table using migration
    echo "Creating users table...\n";
    Schema::create('users', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();
    });
    echo "✓ Created users table\n";

    // Try to query the table
    echo "\nVerifying users table...\n";
    $count = DB::table('users')->count();
    echo "✓ Users table is working! (Count: $count)\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
