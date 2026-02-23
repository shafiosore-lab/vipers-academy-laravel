<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminWhatsAppController extends Controller
{
    /**
     * Display WhatsApp messaging dashboard.
     */
    public function index()
    {
        $players = Player::where('registration_status', 'Approved')
            ->whereNotNull('parent_phone')
            ->orderBy('full_name')
            ->get();

        $staff = User::where('phone', '!=', null)->get();

        return view('admin.whatsapp.index', compact('players', 'staff'));
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
        ]);

        // Placeholder for WhatsApp API integration
        // Would require WhatsApp Business API (Meta)
        // This is a placeholder implementation

        Log::info('WhatsApp message would be sent', [
            'recipients' => $validated['recipients'],
            'message' => $validated['message'],
        ]);

        $recipientCount = count($validated['recipients']);

        return redirect()->route('admin.whatsapp.index')
            ->with('info', "WhatsApp integration pending. {$recipientCount} recipients would receive the message.");
    }

    /**
     * Send bulk WhatsApp message to all players.
     */
    public function sendToAllPlayers(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|min:1|max:4096',
        ]);

        $players = Player::where('registration_status', 'Approved')
            ->whereNotNull('parent_phone')
            ->count();

        Log::info('Bulk WhatsApp message would be sent', [
            'player_count' => $players,
            'message' => $validated['message'],
        ]);

        return redirect()->route('admin.whatsapp.index')
            ->with('info', "WhatsApp integration pending. Message would be sent to {$players} players.");
    }

    /**
     * Get WhatsApp message templates.
     */
    public function templates()
    {
        // Placeholder - would integrate with WhatsApp Business API
        $templates = [
            [
                'id' => 1,
                'name' => 'Training Reminder',
                'content' => 'Reminder: Training session tomorrow at {time}. Please ensure your child brings their kit.',
            ],
            [
                'id' => 2,
                'name' => 'Match Day Notice',
                'content' => 'Match day! {team_name} vs {opponent} on {date} at {venue}.',
            ],
            [
                'id' => 3,
                'name' => 'Payment Reminder',
                'content' => 'Dear Parent, this is a reminder that payment of {amount} is due for {program}.',
            ],
            [
                'id' => 4,
                'name' => 'General Announcement',
                'content' => 'Important announcement from Vipers Academy: {message}',
            ],
        ];

        return view('admin.whatsapp.templates', compact('templates'));
    }

    /**
     * Get WhatsApp message history (placeholder).
     */
    public function history()
    {
        // Placeholder - would need database table
        $history = [];
        return view('admin.whatsapp.history', compact('history'));
    }
}
