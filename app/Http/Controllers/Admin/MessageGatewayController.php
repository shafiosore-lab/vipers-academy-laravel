<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessageGateway;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageGatewayController extends Controller
{
    public function index()
    {
        $gateways = MessageGateway::orderBy('gateway_type')->orderBy('id')->get();
        return view('admin.messaging.settings', compact('gateways'));
    }

    public function quick()
    {
        $players = Player::where('registration_status', 'Approved')
            ->whereNotNull('phone')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->limit(50)
            ->get();

        $gateways = MessageGateway::where('status', 'active')
            ->orderBy('gateway_type')
            ->orderBy('id')
            ->get();

        return view('admin.messaging.quick', compact('players', 'gateways'));
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gateway' => 'required|in:advanta,africas_talking',
            'message' => 'required|string|max:1600',
            'delivery' => 'required|in:immediate,schedule',
            'scheduled_time' => 'nullable|date',
            'groups' => 'nullable|array',
            'players' => 'nullable|array',
            'manual_phones' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Get active gateway
        $gateway = MessageGateway::where('gateway_type', 'sms')
            ->where('status', 'active')
            ->first();

        if (!$gateway) {
            return response()->json([
                'success' => false,
                'message' => 'No active SMS gateway found'
            ], 400);
        }

        // Collect all phone numbers
        $phoneNumbers = [];

        // Add manual phones
        if ($request->has('manual_phones')) {
            $phoneNumbers = array_merge($phoneNumbers, $request->manual_phones);
        }

        // Add player phones
        if ($request->has('players') && count($request->players) > 0) {
            $playerPhones = Player::whereIn('id', $request->players)
                ->whereNotNull('phone')
                ->pluck('phone')
                ->toArray();
            $phoneNumbers = array_merge($phoneNumbers, $playerPhones);
        }

        // For groups, we would need to implement group logic
        // For now, simulate sending

        if (empty($phoneNumbers)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid phone numbers found'
            ], 400);
        }

        // Remove duplicates
        $phoneNumbers = array_unique($phoneNumbers);

        // Calculate SMS count
        $messageLength = strlen($request->message);
        $smsCount = ceil($messageLength / 160) * count($phoneNumbers);

        // Simulate sending (in production, integrate with actual SMS API)
        // For now, just return success
        return response()->json([
            'success' => true,
            'message' => 'Message queued for ' . count($phoneNumbers) . ' recipients (' . $smsCount . ' SMS)',
            'details' => [
                'recipients' => count($phoneNumbers),
                'sms_count' => $smsCount,
                'gateway' => $gateway->gateway_name
            ]
        ]);
    }

    public function update(Request $request, MessageGateway $gateway)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:active,inactive',
            'is_primary' => 'sometimes|boolean',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'sender_id' => 'nullable|string|max:11',
            'account_id' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $data = $validator->validated();

        // Handle primary gateway toggle
        if (isset($data['is_primary']) && $data['is_primary']) {
            // Remove primary from other gateways of same type
            MessageGateway::where('gateway_type', $gateway->gateway_type)
                ->where('id', '!=', $gateway->id)
                ->update(['is_primary' => false]);
        }

        $gateway->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Gateway updated successfully',
            'gateway' => $gateway
        ]);
    }

    public function setPrimary(Request $request, MessageGateway $gateway)
    {
        // Remove primary from other gateways of same type
        MessageGateway::where('gateway_type', $gateway->gateway_type)
            ->where('id', '!=', $gateway->id)
            ->update(['is_primary' => false]);

        $gateway->update(['is_primary' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Primary gateway updated successfully'
        ]);
    }

    public function toggleStatus(Request $request, MessageGateway $gateway)
    {
        $newStatus = $gateway->status === 'active' ? 'inactive' : 'active';
        $gateway->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => "Gateway {$newStatus}",
            'status' => $newStatus
        ]);
    }

    public function testMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gateway_id' => 'required|exists:message_gateways,id',
            'phone_number' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/',
            'message' => 'required|string|max:1600',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $gateway = MessageGateway::findOrFail($request->gateway_id);

        if ($gateway->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Gateway is not active'
            ], 400);
        }

        // Simulate message sending (replace with actual API integration)
        $result = $this->sendTestMessage($gateway, $request->phone_number, $request->message);

        return response()->json($result);
    }

    private function sendTestMessage($gateway, $phone, $message)
    {
        // This is a simulation - replace with actual API calls for each gateway
        // In production, you would integrate with:
        // - Advanta SMS API
        // - Africa's Talking API
        // - WhatsApp Business API
        // - TalkSasa API

        try {
            // Simulate API call
            // $response = Http::withHeaders([...])->post($gateway->api_url, [...]);

            // For now, we'll simulate a successful response
            return [
                'success' => true,
                'message' => 'Test message sent successfully!',
                'details' => [
                    'gateway' => $gateway->gateway_name,
                    'phone' => $phone,
                    'status' => 'delivered'
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to send message: ' . $e->getMessage()
            ];
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gateway_name' => 'required|string|max:255',
            'gateway_type' => 'required|in:sms,whatsapp,talksasa,africaastalking',
            'status' => 'sometimes|in:active,inactive',
            'description' => 'nullable|string',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'sender_id' => 'nullable|string|max:11',
            'account_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $gateway = MessageGateway::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Gateway created successfully',
            'gateway' => $gateway
        ]);
    }

    public function destroy(MessageGateway $gateway)
    {
        $gateway->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gateway deleted successfully'
        ]);
    }
}
