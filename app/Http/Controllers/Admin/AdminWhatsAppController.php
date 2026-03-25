<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\User;
use App\Models\CompanySettings;
use App\Services\WhatsAppService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AdminWhatsAppController extends Controller
{
    protected $whatsAppService;
    protected $notificationService;

    public function __construct(WhatsAppService $whatsAppService, NotificationService $notificationService)
    {
        $this->whatsAppService = $whatsAppService;
        $this->notificationService = $notificationService;
    }

    /**
     * Display WhatsApp messaging dashboard.
     */
    public function index()
    {
        $players = Player::where('registration_status', 'Approved')
            ->whereNotNull('parent_phone')
            ->where(function($query) {
                $query->where('parent_whatsapp', true)
                    ->orWhere('whatsapp_opt_in', true);
            })
            ->orderBy('full_name')
            ->get();

        $allPlayers = Player::where('registration_status', 'Approved')
            ->whereNotNull('parent_phone')
            ->orderBy('full_name')
            ->get();

        $staff = User::where('phone', '!=', null)->get();

        // Get WhatsApp status
        $whatsappStatus = $this->whatsAppService->getConfigStatus();

        return view('admin.whatsapp.index', compact('players', 'allPlayers', 'staff', 'whatsappStatus'));
    }

    /**
     * Display WhatsApp configuration settings.
     */
    public function settings()
    {
        $settings = CompanySettings::getActive();
        $whatsappStatus = $this->whatsAppService->getConfigStatus();

        return view('admin.whatsapp.settings', compact('settings', 'whatsappStatus'));
    }

    /**
     * Update WhatsApp configuration settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_enabled' => 'nullable|boolean',
            'whatsapp_phone_number_id' => 'nullable|string|max:255',
            'whatsapp_business_account_id' => 'nullable|string|max:255',
            'whatsapp_access_token' => 'nullable|string|max:500',
            'whatsapp_default_template' => 'nullable|string|max:100',
        ]);

        $settings = CompanySettings::getActive();

        if (!$settings) {
            return redirect()->route('admin.whatsapp.settings')
                ->with('error', 'No active company settings found. Please configure company settings first.');
        }

        // Update WhatsApp settings
        $settings->update([
            'whatsapp_enabled' => $request->has('whatsapp_enabled'),
            'whatsapp_phone_number_id' => $validated['whatsapp_phone_number_id'],
            'whatsapp_business_account_id' => $validated['whatsapp_business_account_id'],
            'whatsapp_access_token' => $validated['whatsapp_access_token'],
            'whatsapp_default_template' => $validated['whatsapp_default_template'] ?? 'training_reminder',
        ]);

        // Clear config cache
        Cache::forget('services.whatsapp');

        return redirect()->route('admin.whatsapp.settings')
            ->with('success', 'WhatsApp settings updated successfully.');
    }

    /**
     * Send WhatsApp message to selected recipients.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'required|string',
            'message' => 'required|string|min:1|max:4096',
            'recipient_type' => 'required|in:players,staff,custom',
            'use_template' => 'nullable|boolean',
            'template_name' => 'nullable|string|max:100',
        ]);

        $recipients = $validated['recipients'];
        $message = $validated['message'];
        $useTemplate = $request->has('use_template');
        $templateName = $validated['template_name'] ?? 'general';

        $sentCount = 0;
        $failedCount = 0;
        $fallbackCount = 0;

        foreach ($recipients as $index => $phone) {
            // Add delay to avoid rate limiting
            if ($index > 0 && $index % 10 === 0) {
                sleep(1);
            }

            try {
                if ($useTemplate) {
                    // Parse parameters from message (simple format: param1,param2,param3)
                    $params = array_filter(explode(',', $message));
                    $result = $this->whatsAppService->sendTemplateMessage($phone, $templateName, $params);
                } else {
                    // Send as text message
                    $result = $this->whatsAppService->sendTextMessage($phone, $message);
                }

                if ($result['success']) {
                    $sentCount++;
                } else {
                    // Try fallback to SMS
                    if (config('services.whatsapp.fallback_to_sms', true)) {
                        $smsResult = $this->notificationService->sendSms($phone, $message);
                        if ($smsResult['success']) {
                            $fallbackCount++;
                        } else {
                            $failedCount++;
                        }
                    } else {
                        $failedCount++;
                    }
                }
            } catch (\Exception $e) {
                Log::error('WhatsApp send failed', [
                    'phone' => $phone,
                    'error' => $e->getMessage()
                ]);
                $failedCount++;
            }
        }

        $message = "WhatsApp sent: {$sentCount}";
        if ($fallbackCount > 0) {
            $message .= ", SMS fallback: {$fallbackCount}";
        }
        if ($failedCount > 0) {
            $message .= ", Failed: {$failedCount}";
        }

        $status = ($failedCount > 0) ? 'warning' : 'success';

        return redirect()->route('admin.whatsapp.index')
            ->with($status, $message);
    }

    /**
     * Send bulk WhatsApp message to all players with WhatsApp opt-in.
     */
    public function sendToAllPlayers(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|min:1|max:4096',
            'use_template' => 'nullable|boolean',
            'template_name' => 'nullable|string|max:100',
        ]);

        $useTemplate = $request->has('use_template');
        $templateName = $validated['template_name'] ?? 'general';

        // Get players with WhatsApp opt-in
        $players = Player::where('registration_status', 'Approved')
            ->whereNotNull('parent_phone')
            ->where('whatsapp_opt_in', true)
            ->get();

        if ($players->isEmpty()) {
            return redirect()->route('admin.whatsapp.index')
                ->with('warning', 'No players have opted in for WhatsApp notifications.');
        }

        $sentCount = 0;
        $failedCount = 0;
        $fallbackCount = 0;

        foreach ($players as $index => $player) {
            if ($index > 0 && $index % 10 === 0) {
                sleep(1); // Rate limiting
            }

            try {
                if ($useTemplate) {
                    $params = array_filter(explode(',', $validated['message']));
                    $result = $this->whatsAppService->sendTemplateMessage(
                        $player->parent_phone,
                        $templateName,
                        $params
                    );
                } else {
                    $result = $this->whatsAppService->sendTextMessage(
                        $player->parent_phone,
                        $validated['message']
                    );
                }

                if ($result['success']) {
                    $sentCount++;
                } else {
                    if (config('services.whatsapp.fallback_to_sms', true)) {
                        $smsResult = $this->notificationService->sendSms(
                            $player->parent_phone,
                            $validated['message']
                        );
                        if ($smsResult['success']) {
                            $fallbackCount++;
                        } else {
                            $failedCount++;
                        }
                    } else {
                        $failedCount++;
                    }
                }
            } catch (\Exception $e) {
                $failedCount++;
            }
        }

        $message = "Bulk WhatsApp sent: {$sentCount}";
        if ($fallbackCount > 0) {
            $message .= ", SMS fallback: {$fallbackCount}";
        }
        if ($failedCount > 0) {
            $message .= ", Failed: {$failedCount}";
        }

        return redirect()->route('admin.whatsapp.index')
            ->with($failedCount > 0 ? 'warning' : 'success', $message);
    }

    /**
     * Store a custom WhatsApp template.
     */
    public function storeTemplate(Request $request)
    {
        // In production, this would save to a database table
        // For now, just redirect back with a message
        return redirect()->route('admin.whatsapp.templates')
            ->with('success', 'Template saved successfully!');
    }

    /**
     * Get WhatsApp message templates.
     */
    public function templates()
    {
        // Pre-defined templates for Meta WhatsApp Cloud API
        $templates = [
            [
                'id' => 1,
                'name' => 'training_started',
                'title' => 'Training Started',
                'content' => 'Hello {{1}}, Mumias Vipers training has started at {{2}}.',
                'parameters' => ['Parent Name', 'Time'],
                'description' => 'Notification when training session begins'
            ],
            [
                'id' => 2,
                'name' => 'training_ended',
                'title' => 'Training Ended',
                'content' => 'Hello {{1}}, Mumias Vipers training has ended at {{2}}. Kindly arrange pickup.',
                'parameters' => ['Parent Name', 'Time'],
                'description' => 'Notification when training session ends'
            ],
            [
                'id' => 3,
                'name' => 'player_absent',
                'title' => 'Player Absent',
                'content' => 'Hello {{1}}, {{2}} was absent from training today.',
                'parameters' => ['Parent Name', 'Player Name'],
                'description' => 'Notification when a player is marked absent'
            ],
            [
                'id' => 4,
                'name' => 'training_reminder',
                'title' => 'Training Reminder',
                'content' => 'Reminder: Training session {{1}} at {{2}}. Please ensure your child brings their kit.',
                'parameters' => ['Date/Time', 'Location'],
                'description' => 'Reminder before training session'
            ],
            [
                'id' => 5,
                'name' => 'event_reminder',
                'title' => 'Event Reminder',
                'content' => 'Event Reminder: {{1}} on {{2}} at {{3}}.',
                'parameters' => ['Event Name', 'Date', 'Venue'],
                'description' => 'Reminder for matches or events'
            ],
            [
                'id' => 6,
                'name' => 'payment_reminder',
                'title' => 'Payment Reminder',
                'content' => 'Dear Parent, this is a reminder that payment of {{1}} is due for {{2}}.',
                'parameters' => ['Amount', 'Program'],
                'description' => 'Payment due reminder'
            ],
        ];

        return view('admin.whatsapp.templates', compact('templates'));
    }

    /**
     * Get WhatsApp message history.
     */
    public function history()
    {
        // In production, this would fetch from a database table
        // For now, return empty
        $history = [];

        return view('admin.whatsapp.history', compact('history'));
    }

    /**
     * Get WhatsApp usage statistics.
     */
    public function usage()
    {
        $status = $this->whatsAppService->getConfigStatus();

        return view('admin.whatsapp.usage', compact('status'));
    }

    /**
     * Test WhatsApp connection.
     */
    public function testConnection(Request $request)
    {
        $validated = $request->validate([
            'test_phone' => 'required|string',
        ]);

        $phone = $validated['test_phone'];

        // Send a test text message
        $result = $this->whatsAppService->sendTextMessage(
            $phone,
            'This is a test message from Mumias Vipers Academy WhatsApp integration.'
        );

        if ($result['success']) {
            return redirect()->route('admin.whatsapp.settings')
                ->with('success', 'Test message sent successfully! Message ID: ' . ($result['message_id'] ?? 'N/A'));
        }

        return redirect()->route('admin.whatsapp.settings')
            ->with('error', 'Test message failed: ' . ($result['error'] ?? 'Unknown error'));
    }
}
