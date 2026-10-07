<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminSmsController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display SMS dashboard.
     */
    public function index()
    {
        $players = Player::where('registration_status', 'Approved')
            ->whereNotNull('parent_phone')
            ->orderBy('full_name')
            ->get();

        $staff = User::where('phone', '!=', null)->get();

        return view('admin.sms.index', compact('players', 'staff'));
    }

    /**
     * Send SMS to selected recipients.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'required|string',
            'message' => 'required|string|min:1|max:1600',
            'recipient_type' => 'required|in:players,staff,custom',
        ]);

        $recipients = $validated['recipients'];
        $message = $validated['message'];
        $sentCount = 0;
        $failedCount = 0;

        foreach ($recipients as $phone) {
            try {
                $result = $this->smsService->sendSms($phone, $message);
                if ($result) {
                    $sentCount++;
                } else {
                    $failedCount++;
                }
            } catch (\Exception $e) {
                Log::error('SMS sending failed', [
                    'phone' => $phone,
                    'error' => $e->getMessage()
                ]);
                $failedCount++;
            }
        }

        if ($failedCount > 0) {
            return redirect()->route('admin.sms.index')
                ->with('warning', "SMS sent to {$sentCount} recipients. {$failedCount} failed.");
        }

        return redirect()->route('admin.sms.index')
            ->with('success', "SMS sent successfully to {$sentCount} recipients.");
    }

    /**
     * Send bulk SMS to all players.
     */
    public function sendToAllPlayers(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|min:1|max:1600',
        ]);

        $players = Player::where('registration_status', 'Approved')
            ->whereNotNull('parent_phone')
            ->get();

        $message = $validated['message'];
        $sentCount = 0;
        $failedCount = 0;

        foreach ($players as $player) {
            if ($player->parent_phone) {
                try {
                    $result = $this->smsService->sendSms($player->parent_phone, $message);
                    if ($result) {
                        $sentCount++;
                    } else {
                        $failedCount++;
                    }
                } catch (\Exception $e) {
                    $failedCount++;
                }
            }
        }

        return redirect()->route('admin.sms.index')
            ->with('success', "Bulk SMS sent to {$sentCount} players. {$failedCount} failed.");
    }

    /**
     * Get SMS history (placeholder - would need database table).
     */
    public function history()
    {
        // Placeholder - would need a database table for SMS history
        $history = [];
        return view('admin.sms.history', compact('history'));
    }
}
