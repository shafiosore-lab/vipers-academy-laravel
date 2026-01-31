<?php

/**
 * SMS Diagnostic Script
 * Checks configuration and tests SMS sending
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Config;
use App\Services\SmsService;

echo "=== SMS Diagnostic ===\n\n";

// Check config
echo "1. Configuration Check:\n";
echo "   SMS Provider: " . config('services.sms_provider') . "\n";
echo "   BulkSMS API Token: " . (config('services.bulksms.api_token') ? 'SET' : 'NOT SET') . "\n";
echo "   BulkSMS Base URL: " . config('services.bulksms.base_url') . "\n";
echo "   BulkSMS Sender ID: " . config('services.bulksms.sender_id') . "\n\n";

// Test SmsService
echo "2. Testing SmsService:\n";
$smsService = new SmsService();
echo "   SmsService instantiated successfully\n\n";

// Test phone formatting
echo "3. Phone Formatting Test:\n";
$testPhones = ['0711263020', '254711263020', '0xxxxxxxxx'];
foreach ($testPhones as $phone) {
    $formatted = $smsService->formatPhoneNumber($phone);
    echo "   $phone -> $formatted\n";
}
echo "\n";

// Send test SMS
echo "4. Sending Test SMS to 0711263020:\n";
$result = $smsService->sendSms('0711263020', 'Test SMS from Vipers Academy. If you receive this, BulkSMS is working!');
echo "   Result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n\n";

echo "Check Laravel logs for detailed API response.\n";
