<?php

/**
 * Direct SMS Test Script
 * Tests the BulkSMS API without database dependencies
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\SmsService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

echo "=== BulkSMS API Test ===\n\n";

// Get configuration
$apiToken = config('services.bulksms.api_token');
$baseUrl = config('services.bulksms.base_url');
$senderId = config('services.bulksms.sender_id');

echo "Configuration:\n";
echo "  API Token: " . (substr($apiToken, 0, 20) . '...') . "\n";
echo "  Base URL: $baseUrl\n";
echo "  Sender ID: $senderId\n\n";

// Test phone number
$testPhone = '0711263020';
$testMessage = 'Dear Parent/Guardian, Your child Osore B has been admitted to the U15 training session starting at Jan 31, 2025. Vipers Academy';

echo "Sending test SMS to: $testPhone\n";
echo "Message: $testMessage\n\n";

// Make the API request directly
try {
    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $apiToken,
    ])->post("{$baseUrl}/sms/send", [
        'phone' => $testPhone,
        'message' => $testMessage,
        'sender_id' => $senderId,
    ]);

    echo "Response Status: " . $response->status() . "\n";
    echo "Response Body: " . $response->body() . "\n";

    if ($response->successful()) {
        $data = $response->json();
        echo "\n✓ SMS sent successfully!\n";
        echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "\n✗ SMS sending failed!\n";
        echo "Error: " . $response->body() . "\n";
    }
} catch (\Exception $e) {
    echo "\n✗ Exception occurred!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
