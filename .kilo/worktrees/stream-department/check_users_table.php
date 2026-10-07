<?php

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $_ENV['DB_HOST'],
    'database'  => $_ENV['DB_DATABASE'],
    'username'  => $_ENV['DB_USERNAME'],
    'password'  => $_ENV['DB_PASSWORD'],
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

use Illuminate\Support\Facades\DB;

echo "Users table structure:\n";
echo "=====================\n";

$columns = DB::select('SHOW COLUMNS FROM users');

foreach ($columns as $column) {
    echo "{$column->Field} ({$column->Type}) - " . ($column->Null == 'YES' ? 'Nullable' : 'Not Nullable');
    if (!empty($column->Default)) {
        echo " - Default: '{$column->Default}'";
    }
    if ($column->Key == 'PRI') {
        echo " - Primary Key";
    } elseif ($column->Key == 'UNI') {
        echo " - Unique";
    }
    echo "\n";
}

echo "\nNumber of users in table: " . DB::table('users')->count() . "\n";
