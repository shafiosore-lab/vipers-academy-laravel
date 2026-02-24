<?php

require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel without database connection
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Connect to MySQL without database
$host = '127.0.0.1';
$user = 'root';
$password = '';
$database = 'webviper';

echo "Attempting to drop and recreate database...\n\n";

try {
    // Connect without database
    $pdo = new PDO("mysql:host=$host", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "✓ Connected to MySQL server\n";

    // Drop database if exists
    echo "Dropping database '$database'...\n";
    $pdo->exec("DROP DATABASE IF EXISTS `$database`");
    echo "✓ Dropped database\n";

    // Create database
    echo "Creating database '$database'...\n";
    $pdo->exec("CREATE DATABASE `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Created database\n";

    echo "\n✓ Database recreated successfully!\n";
    echo "Now you can run: php artisan migrate\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
