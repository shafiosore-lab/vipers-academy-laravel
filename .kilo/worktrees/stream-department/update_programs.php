<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Program;
use Illuminate\Support\Facades\DB;

echo "=== Current Programs ===\n";
$programs = Program::all();
foreach($programs as $p) {
    echo "ID: {$p->id} | Title: {$p->title} | Status: {$p->status}\n";
}

echo "\n=== Updating to Mumias Vipers Programs ===\n";

// Disable foreign key checks temporarily
DB::statement('SET FOREIGN_KEY_CHECKS=0');

// Delete all existing programs
Program::truncate();

// Re-enable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=1');

// Create the 3 programs
$programs = [
    [
        'title' => 'Weekend Football & Life-Skills Program',
        'description' => 'Our flagship weekend program combining professional football training with essential life skills. Perfect for young athletes who want to develop both on and off the pitch. Training sessions every Saturday and Sunday with qualified coaches.',
        'status' => 'active',
        'age_group' => '6-18 years',
        'regular_fee' => 5000,
        'mumias_fee' => 5000,
        'mumias_discount_percentage' => 0,
        'duration' => '3 months',
        'schedule' => 'Saturdays and Sundays, 8:00 AM - 12:00 PM',
        'category' => 'football',
    ],
    [
        'title' => 'Long Holiday Intensive Camp',
        'description' => 'An immersive holiday camp program focused on intensive football training, teamwork, and personal development. During school holidays, players train daily and participate in friendly matches and tournaments.',
        'status' => 'active',
        'age_group' => '8-18 years',
        'regular_fee' => 15000,
        'mumias_fee' => 15000,
        'mumias_discount_percentage' => 0,
        'duration' => '2 weeks',
        'schedule' => 'Daily, 9:00 AM - 3:00 PM',
        'category' => 'football',
    ],
    [
        'title' => 'Computer & Coding Classes',
        'description' => 'Prepare your child for the digital future with our comprehensive computer and coding program. Learn programming basics, web development, robotics, and digital literacy skills from experienced instructors.',
        'status' => 'active',
        'age_group' => '8-16 years',
        'regular_fee' => 8000,
        'mumias_fee' => 8000,
        'mumias_discount_percentage' => 0,
        'duration' => '3 months',
        'schedule' => 'Fridays, 4:00 PM - 6:00 PM',
        'category' => 'coding',
    ],
];

foreach ($programs as $programData) {
    $program = Program::create($programData);
    echo "Created: {$program->title}\n";
}

echo "\n=== Updated Programs ===\n";
$programs = Program::all();
foreach($programs as $p) {
    echo "ID: {$p->id} | Title: {$p->title} | Status: {$p->status} | Fee: KES {$p->regular_fee}\n";
}

echo "\nDone!\n";
