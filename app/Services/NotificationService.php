<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    public function sendCheckInNotification($player, $sessionType, $checkInTime)
    {
        $parentPhone = $player->parent_phone;

        if (!$parentPhone) {
            Log::warning("No parent phone for player {$player->id}");
            return false;
        }

        $message = "Hi {$player->parent_guardian_name},\n\n👋🏽 This is to confirm that {$player->full_name} has arrived safely for today's {$sessionType} at Mumias Vipers Academy.\n\n⏰ Time in: {$checkInTime}\n\nThank you for trusting us with their development. ⚽";

        return $this->sendWhatsAppMessage($parentPhone, $message);
    }

    public function sendCheckOutNotification($player, $sessionType, $checkOutTime)
    {
        $parentPhone = $player->parent_phone;

        if (!$parentPhone) {
            Log::warning("No parent phone for player {$player->id}");
            return false;
        }

        $message = "Hi {$player->parent_guardian_name},\n\n✅ {$player->full_name} has been released safely after today's {$sessionType} at Mumias Vipers Academy.\n\n⏰ Time out: {$checkOutTime}\n\nSee you at the next session. ⚽";

        return $this->sendWhatsAppMessage($parentPhone, $message);
    }

    public function sendMonthlyReport($player, $reportData)
    {
        $parentPhone = $player->parent_phone;

        if (!$parentPhone) {
            Log::warning("No parent phone for player {$player->id}");
            return false;
        }

        $message = "MUMIAS VIPERS ACADEMY – MONTHLY ATTENDANCE REPORT\n\n📅 Month: {$reportData['month']}\n\n👤 Player: {$player->full_name}\n⚽ Trainings Attended: {$reportData['trainings']}\n🏟 Matches Attended: {$reportData['matches']}\n⏱ Total Time Spent: {$reportData['total_hours']} hours\n💰 Monthly Contribution: KES {$player->monthly_contribution}\n\nThank you for supporting your child's growth through discipline and sport.";

        return $this->sendWhatsAppMessage($parentPhone, $message);
    }

    public function sendMonthlySummary($groupData)
    {
        // This would be sent to a WhatsApp group
        // For now, log it
        $message = "MUMIAS VIPERS ACADEMY – MONTHLY SUMMARY\n\n📅 {$groupData['month']}\n\n👥 Active Players: {$groupData['total_players']}\n⚽ Trainings Held: {$groupData['total_trainings']}\n🏟 Matches Played: {$groupData['total_matches']}\n📊 Average Attendance: {$groupData['attendance_rate']}%\n\nThank you parents for your continued support. Together, we build disciplined players on and off the pitch. 💪🏽⚽";

        Log::info("Monthly summary notification: {$message}");

        // TODO: Send to WhatsApp group
        return true;
    }

    public function sendPaymentAcknowledgment($guardian, $player, $amount, $remainingBalance)
    {
        $message = "Hello {$guardian->full_name},\n\n🙏🏽 Thank you! We have received KES {$amount} for {$player->full_name} (KES {$player->getMonthlyFee()} category) at Mumias Vipers Academy.\n\n💼 Balance remaining: KES {$remainingBalance}\n\nWe appreciate your continued support. ⚽";

        if ($guardian->preferred_notification_channel === 'whatsapp' || $guardian->preferred_notification_channel === 'both') {
            $this->sendWhatsAppMessage($guardian->phone, $message);
        }

        if ($guardian->preferred_notification_channel === 'sms' || $guardian->preferred_notification_channel === 'both') {
            $this->sendSMS($guardian->phone, $message);
        }
    }

    public function sendMonthlyFinancialSummary($guardian, $summaryData)
    {
        $message = "MUMIAS VIPERS ACADEMY – MONTHLY FINANCIAL SUMMARY\n\n👤 Player: {$summaryData['player_name']}\n⚽ Fee Category: KES {$summaryData['monthly_fee']} / month\n📅 Month: {$summaryData['month']}\n\n💰 Monthly Charge: KES {$summaryData['monthly_fee']}\n💳 Amount Paid: KES {$summaryData['amount_paid']}\n📉 Balance Carried Forward: KES {$summaryData['closing_balance']}\n\nThank you for being part of the Mumias Vipers family. ⚽";

        if ($guardian->preferred_notification_channel === 'whatsapp' || $guardian->preferred_notification_channel === 'both') {
            $this->sendWhatsAppMessage($guardian->phone, $message);
        }

        if ($guardian->preferred_notification_channel === 'sms' || $guardian->preferred_notification_channel === 'both') {
            $this->sendSMS($guardian->phone, $message);
        }
    }

    private function sendWhatsAppMessage($phone, $message)
    {
        // Format phone number for WhatsApp (ensure international format with +)
        $phone = $this->formatPhoneNumber($phone);

        try {
            // Option 1: Twilio WhatsApp Integration
            return $this->sendWhatsAppViaTwilio($phone, $message);

            // Option 2: 360Dialog WhatsApp Business API
            // return $this->sendWhatsAppVia360Dialog($phone, $message);

            // Option 3: Africa's Talking (if WhatsApp not available)
            // return $this->sendWhatsAppViaAfricasTalking($phone, $message);

        } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp message: " . $e->getMessage());
            // Fallback to SMS
            return $this->sendSMS($phone, $message);
        }
    }

    private function sendWhatsAppViaTwilio($phone, $message)
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.whatsapp_from'); // WhatsApp enabled number

        $response = Http::withBasicAuth($sid, $token)
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                'From' => "whatsapp:{$from}",
                'To' => "whatsapp:{$phone}",
                'Body' => $message,
            ]);

        if ($response->successful()) {
            Log::info("WhatsApp message sent to {$phone} via Twilio");
            return true;
        } else {
            Log::error("Twilio WhatsApp failed: " . $response->body());
            return false;
        }
    }

    private function sendWhatsAppVia360Dialog($phone, $message)
    {
        $apiKey = config('services.360dialog.api_key');
        $phoneNumberId = config('services.360dialog.phone_number_id');

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type' => 'application/json',
        ])->post("https://waba.360dialog.io/v1/messages", [
            'to' => $phone,
            'type' => 'text',
            'text' => ['body' => $message],
        ]);

        if ($response->successful()) {
            Log::info("WhatsApp message sent to {$phone} via 360Dialog");
            return true;
        } else {
            Log::error("360Dialog WhatsApp failed: " . $response->body());
            return false;
        }
    }

    private function sendSMS($phone, $message)
    {
        $phone = $this->formatPhoneNumber($phone);

        try {
            // Option 1: Africa's Talking (Recommended for Kenya)
            return $this->sendSMSViaAfricasTalking($phone, $message);

            // Option 2: Twilio SMS
            // return $this->sendSMSViaTwilio($phone, $message);

        } catch (\Exception $e) {
            Log::error("Failed to send SMS: " . $e->getMessage());
            return false;
        }
    }

    private function sendSMSViaAfricasTalking($phone, $message)
    {
        $username = config('services.africas_talking.username');
        $apiKey = config('services.africas_talking.api_key');
        $shortcode = config('services.africas_talking.shortcode');

        $response = Http::post('https://api.africastalking.com/version1/messaging', [
            'username' => $username,
            'to' => $phone,
            'message' => $message,
            'from' => $shortcode,
        ])->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($username . ':' . $apiKey),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['SMSMessageData']['Recipients'][0]['status']) &&
                $data['SMSMessageData']['Recipients'][0]['status'] === 'Success') {
                Log::info("SMS sent to {$phone} via Africa's Talking");
                return true;
            }
        }

        Log::error("Africa's Talking SMS failed: " . $response->body());
        return false;
    }

    private function sendSMSViaTwilio($phone, $message)
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.sms_from');

        $response = Http::withBasicAuth($sid, $token)
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                'From' => $from,
                'To' => $phone,
                'Body' => $message,
            ]);

        if ($response->successful()) {
            Log::info("SMS sent to {$phone} via Twilio");
            return true;
        } else {
            Log::error("Twilio SMS failed: " . $response->body());
            return false;
        }
    }

    private function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/\D/', '', $phone);

        // Add country code if missing (assuming Kenya +254)
        if (strlen($phone) === 9 && str_starts_with($phone, '7')) {
            $phone = '254' . $phone;
        }

        // Ensure it starts with +
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        return $phone;
    }
}
