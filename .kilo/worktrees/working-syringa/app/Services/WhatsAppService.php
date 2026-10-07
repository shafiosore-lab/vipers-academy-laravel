<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class WhatsAppService
{
    protected $phoneNumberId;
    protected $accessToken;
    protected $businessAccountId;
    protected $enabled;
    protected $baseUrl;

    // Meta Free Tier limit
    protected const MONTHLY_LIMIT = 1000;

    public function __construct()
    {
        $this->enabled = config('services.whatsapp.enabled', false);
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->accessToken = config('services.whatsapp.access_token');
        $this->businessAccountId = config('services.whatsapp.business_account_id');
        $this->baseUrl = 'https://graph.facebook.com/v18.0';
    }

    /**
     * Check if WhatsApp is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled && !empty($this->phoneNumberId) && !empty($this->accessToken);
    }

    /**
     * Check if monthly limit has been exceeded
     */
    public function isWithinLimit(): bool
    {
        $monthlyCount = $this->getMonthlyUsageCount();
        return $monthlyCount < self::MONTHLY_LIMIT;
    }

    /**
     * Get current monthly usage count
     */
    public function getMonthlyUsageCount(): int
    {
        $key = 'whatsapp_monthly_count_' . now()->format('Y_m');
        return Cache::get($key, 0);
    }

    /**
     * Increment monthly usage count
     */
    protected function incrementUsageCount(): void
    {
        $key = 'whatsapp_monthly_count_' . now()->format('Y_m');
        Cache::increment($key);
    }

    /**
     * Get available conversations remaining
     */
    public function getRemainingConversations(): int
    {
        return max(0, self::MONTHLY_LIMIT - $this->getMonthlyUsageCount());
    }

    /**
     * Send WhatsApp template message
     */
    public function sendTemplateMessage(string $to, string $templateName, array $parameters = [], string $language = 'en'): array
    {
        if (!$this->isEnabled()) {
            return [
                'success' => false,
                'error' => 'WhatsApp is not enabled'
            ];
        }

        // Check monthly limit
        if (!$this->isWithinLimit()) {
            return [
                'success' => false,
                'error' => 'Monthly WhatsApp limit exceeded, falling back to SMS',
                'fallback' => true
            ];
        }

        // Format phone number
        $to = $this->formatPhoneNumber($to);

        // Build template components
        $components = $this->buildTemplateComponents($templateName, $parameters);

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $language
                ]
            ]
        ];

        if (!empty($components)) {
            $payload['template']['components'] = $components;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("{$this->baseUrl}/{$this->phoneNumberId}/messages", $payload);

            if ($response->successful()) {
                $data = $response->json();

                // Increment usage count
                $this->incrementUsageCount();

                Log::info('WhatsApp message sent successfully', [
                    'to' => $to,
                    'template' => $templateName,
                    'message_id' => $data['messages'][0]['id'] ?? null
                ]);

                return [
                    'success' => true,
                    'message_id' => $data['messages'][0]['id'] ?? null
                ];
            } else {
                $error = $response->json();
                Log::error('WhatsApp sending failed', [
                    'to' => $to,
                    'template' => $templateName,
                    'status' => $response->status(),
                    'error' => $error
                ]);

                return [
                    'success' => false,
                    'error' => $error['error']['message'] ?? 'Failed to send WhatsApp message',
                    'error_code' => $error['error']['code'] ?? null
                ];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp exception', [
                'to' => $to,
                'template' => $templateName,
                'exception' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send custom text message (session init message)
     */
    public function sendTextMessage(string $to, string $message): array
    {
        if (!$this->isEnabled()) {
            return [
                'success' => false,
                'error' => 'WhatsApp is not enabled'
            ];
        }

        $to = $this->formatPhoneNumber($to);

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => [
                'body' => $message
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("{$this->baseUrl}/{$this->phoneNumberId}/messages", $payload);

            if ($response->successful()) {
                $data = $response->json();
                $this->incrementUsageCount();

                return [
                    'success' => true,
                    'message_id' => $data['messages'][0]['id'] ?? null
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Failed to send message'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Build template components based on template name and parameters
     */
    protected function buildTemplateComponents(string $templateName, array $parameters): array
    {
        $components = [];

        if (empty($parameters)) {
            return $components;
        }

        $params = [];
        foreach ($parameters as $value) {
            $params[] = [
                'type' => 'text',
                'text' => $value
            ];
        }

        $components[] = [
            'type' => 'body',
            'parameters' => $params
        ];

        return $components;
    }

    /**
     * Format phone number to international format (+254)
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove any spaces or special characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If phone starts with 0, replace with 254 (Kenya country code)
        if (str_starts_with($phone, '0')) {
            return '+254' . substr($phone, 1);
        }

        // If phone doesn't start with 254, add it
        if (!str_starts_with($phone, '254')) {
            return '+254' . $phone;
        }

        return '+' . $phone;
    }

    /**
     * Validate phone number format
     */
    public function validatePhoneNumber(string $phone): bool
    {
        $formatted = $this->formatPhoneNumber($phone);
        return preg_match('/^\+254[0-9]{9}$/', $formatted) === 1;
    }

    /**
     * Get WhatsApp configuration status
     */
    public function getConfigStatus(): array
    {
        return [
            'enabled' => $this->isEnabled(),
            'monthly_limit' => self::MONTHLY_LIMIT,
            'used_this_month' => $this->getMonthlyUsageCount(),
            'remaining' => $this->getRemainingConversations(),
            'configured' => !empty($this->phoneNumberId) && !empty($this->accessToken)
        ];
    }
}
