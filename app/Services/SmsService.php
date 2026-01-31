<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $username;
    protected $apiKey;
    protected $baseUrl;
    protected $provider;

    public function __construct()
    {
        $this->provider = config('services.sms_provider', 'africas_talking');

        if ($this->provider === 'bulksms') {
            $this->apiToken = config('services.bulksms.api_token');
            $this->baseUrl = config('services.bulksms.base_url');
            $this->senderId = config('services.bulksms.sender_id');
        } else {
            $this->username = config('services.africas_talking.username');
            $this->apiKey = config('services.africas_talking.api_key');
            $this->baseUrl = config('services.africas_talking.sandbox', false)
                ? 'https://api.sandbox.africastalking.com'
                : 'https://api.africastalking.com';
        }
    }

    public function sendSms($to, $message)
    {
        if ($this->provider === 'bulksms') {
            return $this->sendBulkSms($to, $message);
        }
        return $this->sendAfricasTalking($to, $message);
    }

    /**
     * Send SMS via BulkSMS (Talksasa)
     */
    public function sendBulkSms($phone, $message)
    {
        $maxRetries = 3;
        $timeout = 30; // 30 seconds timeout

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiToken,
                ])->timeout($timeout)->post("{$this->baseUrl}/sms/send", [
                    'phone' => $this->formatPhoneNumber($phone),
                    'message' => $message,
                    'sender_id' => $this->senderId,
                ]);

                Log::info('BulkSMS API Request', [
                    'url' => "{$this->baseUrl}/sms/send",
                    'phone' => $phone,
                    'message' => $message,
                    'attempt' => $attempt,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('BulkSMS sent successfully', [
                        'phone' => $phone,
                        'message' => $message,
                        'response' => $data
                    ]);
                    return true;
                } else {
                    Log::error('BulkSMS sending failed', [
                        'phone' => $phone,
                        'message' => $message,
                        'status' => $response->status(),
                        'response' => $response->body(),
                        'attempt' => $attempt,
                    ]);
                    return false;
                }
            } catch (\Exception $e) {
                Log::warning('BulkSMS attempt ' . $attempt . ' failed', [
                    'phone' => $phone,
                    'message' => $message,
                    'exception' => $e->getMessage(),
                ]);

                if ($attempt === $maxRetries) {
                    Log::error('BulkSMS sending exception', [
                        'phone' => $phone,
                        'message' => $message,
                        'exception' => $e->getMessage()
                    ]);
                    return false;
                }

                // Wait before retry (exponential backoff)
                sleep(pow(2, $attempt - 1));
            }
        }

        return false;
    }

    /**
     * Send SMS via Africa's Talking
     */
    public function sendAfricasTalking($to, $message)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'apiKey' => $this->apiKey,
            ])->post("{$this->baseUrl}/version1/messaging", [
                'username' => $this->username,
                'to' => $to,
                'message' => $message,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('SMS sent successfully', [
                    'to' => $to,
                    'message' => $message,
                    'response' => $data
                ]);
                return true;
            } else {
                Log::error('SMS sending failed', [
                    'to' => $to,
                    'message' => $message,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('SMS sending exception', [
                'to' => $to,
                'message' => $message,
                'exception' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Format phone number to international format
     */
    protected function formatPhoneNumber($phone)
    {
        // Remove any spaces or special characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If phone starts with 0, replace with 254 (Kenya country code)
        if (str_starts_with($phone, '0')) {
            return '254' . substr($phone, 1);
        }

        // If phone doesn't start with 254, add it
        if (!str_starts_with($phone, '254')) {
            return '254' . $phone;
        }

        return $phone;
    }

    public function sendAdmissionNotification($player, $session)
    {
        if (!$player->parent_phone) {
            Log::warning('No parent phone number for player', ['player_id' => $player->id]);
            return false;
        }

        $message = "Dear Parent/Guardian,\n\nYour child {$player->full_name} has been admitted to the {$session->team_category} {$session->session_type} session starting at {$session->scheduled_start_time->format('M j, Y g:i A')}.\n\nVipers Academy";

        return $this->sendSms($player->parent_phone, $message);
    }
}
