<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $username;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->username = config('services.africas_talking.username');
        $this->apiKey = config('services.africas_talking.api_key');
        $this->baseUrl = config('services.africas_talking.sandbox', false)
            ? 'https://api.sandbox.africastalking.com'
            : 'https://api.africastalking.com';
    }

    public function sendSms($to, $message)
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
