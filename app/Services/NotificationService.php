<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Unified Notification Service
 * Handles both SMS and WhatsApp messaging with automatic failover
 *
 * Usage:
 * $notificationService->send('sms', $phone, $message);
 * $notificationService->send('whatsapp', $phone, $template, $params);
 */
class NotificationService
{
    protected $smsService;
    protected $whatsAppService;

    // WhatsApp enabled for notifications
    protected $whatsAppEnabled;

    // Fallback to SMS when WhatsApp fails
    protected $fallbackToSms;

    public function __construct(SmsService $smsService, WhatsAppService $whatsAppService)
    {
        $this->smsService = $smsService;
        $this->whatsAppService = $whatsAppService;
        $this->whatsAppEnabled = config('services.whatsapp.enabled', false);
        $this->fallbackToSms = config('services.whatsapp.fallback_to_sms', true);
    }

    /**
     * Send notification via specified channel
     *
     * @param string $channel 'sms' or 'whatsapp'
     * @param string $phone Recipient phone number
     * @param string|array $message Message text or template name for WhatsApp
     * @param array $params Parameters for WhatsApp template
     * @return array Result with success status and details
     */
    public function send(string $channel, string $phone, $message, array $params = []): array
    {
        if ($channel === 'whatsapp') {
            return $this->sendWhatsApp($phone, $message, $params);
        }

        return $this->sendSms($phone, $message);
    }

    /**
     * Send SMS notification
     */
    public function sendSms(string $phone, string $message): array
    {
        try {
            $result = $this->smsService->sendSms($phone, $message);

            return [
                'success' => $result,
                'channel' => 'sms',
                'phone' => $phone,
                'message' => $message
            ];
        } catch (\Exception $e) {
            Log::error('SMS notification failed', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'channel' => 'sms',
                'phone' => $phone,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send WhatsApp notification with automatic SMS fallback
     */
    public function sendWhatsApp(string $phone, string $templateName, array $params = [], string $language = 'en'): array
    {
        // If WhatsApp is not enabled, fall back to SMS
        if (!$this->whatsAppService->isEnabled()) {
            Log::info('WhatsApp not enabled, falling back to SMS', ['phone' => $phone]);
            return [
                'success' => false,
                'channel' => 'whatsapp',
                'phone' => $phone,
                'fallback' => 'sms',
                'error' => 'WhatsApp not enabled'
            ];
        }

        // Try sending via WhatsApp
        $result = $this->whatsAppService->sendTemplateMessage($phone, $templateName, $params, $language);

        if ($result['success']) {
            return [
                'success' => true,
                'channel' => 'whatsapp',
                'phone' => $phone,
                'message_id' => $result['message_id'] ?? null
            ];
        }

        // Check if fallback is needed (limit exceeded or other error)
        if (isset($result['fallback']) || $this->fallbackToSms) {
            Log::warning('WhatsApp failed, falling back to SMS', [
                'phone' => $phone,
                'error' => $result['error'] ?? 'Unknown error'
            ]);

            // Try SMS fallback with formatted message based on template
            $smsMessage = $this->formatTemplateToSms($templateName, $params);
            return $this->sendSms($phone, $smsMessage);
        }

        return [
            'success' => false,
            'channel' => 'whatsapp',
            'phone' => $phone,
            'error' => $result['error'] ?? 'Failed to send WhatsApp message'
        ];
    }

    /**
     * Send notification with smart channel selection
     *
     * Uses WhatsApp if:
     * - WhatsApp is enabled
     * - Player has opted in to WhatsApp
     * - Monthly limit not exceeded
     *
     * Otherwise uses SMS
     */
    public function sendSmartNotification(
        string $phone,
        string $message,
        string $templateName = '',
        array $templateParams = [],
        bool $whatsAppOptIn = false
    ): array {
        // Determine best channel
        $useWhatsApp = $this->shouldUseWhatsApp($whatsAppOptIn);

        if ($useWhatsApp && !empty($templateName)) {
            return $this->sendWhatsApp($phone, $templateName, $templateParams);
        }

        return $this->sendSms($phone, $message);
    }

    /**
     * Check if WhatsApp should be used
     */
    protected function shouldUseWhatsApp(bool $optIn): bool
    {
        if (!$this->whatsAppEnabled) {
            return false;
        }

        if (!$optIn) {
            return false;
        }

        // Check monthly limit
        return $this->whatsAppService->isWithinLimit();
    }

    /**
     * Format WhatsApp template parameters to SMS text
     */
    protected function formatTemplateToSms(string $templateName, array $params): string
    {
        $templates = [
            'training_started' => 'Hello, Mumias Vipers training has started. ' . ($params[0] ?? '') . ' - ' . ($params[1] ?? ''),
            'training_ended' => 'Hello ' . ($params[0] ?? '') . ', Mumias Vipers training has ended at ' . ($params[1] ?? '') . '. Kindly arrange pickup.',
            'player_absent' => 'Hello ' . ($params[0] ?? '') . ', ' . ($params[1] ?? 'Your child') . ' was absent from training today.',
            'training_reminder' => 'Reminder: Training session ' . ($params[0] ?? 'tomorrow') . ' at ' . ($params[1] ?? ''),
            'event_reminder' => 'Event Reminder: ' . ($params[0] ?? '') . ' on ' . ($params[1] ?? '') . ' at ' . ($params[2] ?? ''),
        ];

        return $templates[$templateName] ?? 'Mumias Vipers: ' . implode(' ', $params);
    }

    /**
     * Send bulk notifications
     */
    public function sendBulk(array $recipients, string $channel, string $message, array $params = []): array
    {
        $results = [
            'total' => count($recipients),
            'success' => 0,
            'failed' => 0,
            'fallback' => 0,
            'details' => []
        ];

        foreach ($recipients as $index => $recipient) {
            // Add delay to avoid rate limiting (especially for WhatsApp)
            if ($index > 0 && $index % 10 === 0) {
                sleep(1); // 1 second delay every 10 messages
            }

            $phone = is_array($recipient) ? ($recipient['phone'] ?? '') : $recipient;
            $optIn = is_array($recipient) ? ($recipient['whatsapp_opt_in'] ?? false) : false;

            if (empty($phone)) {
                $results['failed']++;
                continue;
            }

            if ($channel === 'whatsapp') {
                $result = $this->sendWhatsApp($phone, $message, $params);
            } else {
                $result = $this->sendSms($phone, $message);
            }

            if ($result['success']) {
                $results['success']++;
            } else {
                if (isset($result['fallback'])) {
                    $results['fallback']++;
                }
                $results['failed']++;
            }

            $results['details'][] = $result;
        }

        return $results;
    }

    /**
     * Get notification channel status
     */
    public function getStatus(): array
    {
        return [
            'whatsapp' => $this->whatsAppService->getConfigStatus(),
            'sms' => [
                'provider' => config('services.sms_provider', 'africas_talking'),
                'configured' => !empty(config('services.africas_talking.api_key'))
            ]
        ];
    }
}
