<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Database config:\n";
echo "Default: " . config('database.default') . "\n";
echo "Database: " . config('database.connections.mysql.database') . "\n";
echo "Username: " . config('database.connections.mysql.username') . "\n";
echo "Host: " . config('database.connections.mysql.host') . "\n";

echo "\nChecking MySQL directly:\n";
$pdo = new PDO('mysql:host=127.0.0.1;dbname=webviper', 'root', '');
$stmt = $pdo->query("SELECT COUNT(*) as cnt FROM users");
$count = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Users in MySQL: " . $count['cnt'] . "\n";

$stmt = $pdo->query("SELECT COUNT(*) as cnt FROM roles");
$count = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Roles in MySQL: " . $count['cnt'] . "\n";
