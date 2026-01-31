<?php

namespace App\Console\Commands;

use App\Models\Guardian;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-payment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminders to guardians with outstanding balances';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dayOfMonth = now()->day;

        // Only send reminders on 5th and 15th
        if (!in_array($dayOfMonth, [5, 15])) {
            $this->info("Not a reminder day (5th or 15th). Today is the {$dayOfMonth}th.");
            return;
        }

        $this->info("Sending payment reminders for day {$dayOfMonth} of the month");

        // Get all guardians with outstanding balances
        $guardians = Guardian::all()->filter(function ($guardian) {
            return $guardian->shouldReceiveReminder();
        });

        $sent = 0;

        foreach ($guardians as $guardian) {
            $playersWithBalances = $guardian->getPlayersWithBalances();

            if (count($playersWithBalances) === 1) {
                // Single player reminder
                $playerData = $playersWithBalances[0];
                $this->sendSinglePlayerReminder($guardian, $playerData['player'], $playerData['balance'], $playerData['monthly_fee']);
            } else {
                // Multi-player consolidated reminder
                $this->sendMultiPlayerReminder($guardian, $playersWithBalances);
            }

            $sent++;
            $this->info("Sent reminder to {$guardian->full_name} for " . count($playersWithBalances) . " player(s)");
        }

        $this->info("Payment reminders sent: {$sent} guardians reminded");
    }

    private function sendSinglePlayerReminder($guardian, $player, $balance, $monthlyFee)
    {
        $message = "Hello {$guardian->full_name},\n\nKindly note that {$player->full_name} (KES {$monthlyFee} category) has an outstanding balance of KES {$balance}.\n\nPaybill: 400200\nAccount: {$player->id}\n\nThank you for your continued support.";

        if ($guardian->preferred_notification_channel === 'whatsapp' || $guardian->preferred_notification_channel === 'both') {
            $this->notificationService->sendWhatsAppMessage($guardian->phone, $message);
        }

        if ($guardian->preferred_notification_channel === 'sms' || $guardian->preferred_notification_channel === 'both') {
            $this->notificationService->sendSMS($guardian->phone, $message);
        }
    }

    private function sendMultiPlayerReminder($guardian, $playersData)
    {
        $totalBalance = array_sum(array_column($playersData, 'balance'));
        $playerLines = [];

        foreach ($playersData as $data) {
            $playerLines[] = "⚽ {$data['player']->full_name} (KES {$data['monthly_fee']} category) – Balance: KES {$data['balance']}";
        }

        $message = "Hello {$guardian->full_name},\n\nThis is a friendly reminder from Mumias Vipers Academy regarding outstanding training balances:\n\n" .
            implode("\n", $playerLines) . "\n\n💰 Total Outstanding: KES {$totalBalance}\n\nPaybill: 400200\nAccount: [Guardian ID: {$guardian->id}]\n\nThank you for supporting discipline and player development. ⚽";

        if ($guardian->preferred_notification_channel === 'whatsapp' || $guardian->preferred_notification_channel === 'both') {
            $this->notificationService->sendWhatsAppMessage($guardian->phone, $message);
        }

        if ($guardian->preferred_notification_channel === 'sms' || $guardian->preferred_notification_channel === 'both') {
            $this->notificationService->sendSMS($guardian->phone, $message);
        }
    }
}
