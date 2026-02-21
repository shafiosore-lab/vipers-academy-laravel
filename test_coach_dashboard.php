<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Get the user
$user = User::where('email', 'coach@mumiasvipers.com')->first();

if (!$user) {
    echo "User not found!\n";
    exit(1);
}

// Login the user
Auth::login($user);

echo "User logged in successfully!\n";
echo "User: {$user->email}\n";
echo "Name: {$user->name}\n";
echo "User Type: {$user->user_type}\n";
echo "Approval Status: {$user->approval_status}\n";
echo "Status: {$user->status}\n";
echo "-------------------\n";

// Check if we can access the dashboard route
$response = app('Illuminate\Http\Request')->create('/coach/dashboard', 'GET');
$response = app()->handle($response);

echo "Dashboard Response Status: " . $response->getStatusCode() . "\n";

if ($response->getStatusCode() === 200) {
    echo "User can access the coach dashboard!\n";
} else {
    echo "User cannot access the coach dashboard!\n";
    echo "Response: " . $response->getContent() . "\n";
}
