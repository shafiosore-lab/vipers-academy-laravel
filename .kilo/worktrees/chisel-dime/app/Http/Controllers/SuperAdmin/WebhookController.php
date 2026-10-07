<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Webhook;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    /**
     * Display a listing of webhooks.
     */
    public function index()
    {
        $webhooks = Webhook::with('organization')
            ->latest()
            ->paginate(20);

        return view('super-admin.webhooks.index', compact('webhooks'));
    }

    /**
     * Show the form for creating a new webhook.
     */
    public function create()
    {
        $organizations = Organization::where('status', 'active')->get();
        $events = $this->getAvailableEvents();

        return view('super-admin.webhooks.create', compact('organizations', 'events'));
    }

    /**
     * Store a newly created webhook in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'events' => 'required|array',
            'events.*' => 'string|in:' . implode(',', array_keys($this->getAvailableEvents())),
            'organization_id' => 'required|exists:organizations,id',
            'secret' => 'nullable|string|min:16',
            'enabled' => 'boolean',
            'retry_attempts' => 'integer|min:0|max:5',
            'timeout' => 'integer|min:5|max:60',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $webhook = Webhook::create([
            'name' => $request->name,
            'url' => $request->url,
            'events' => $request->events,
            'organization_id' => $request->organization_id,
            'secret' => $request->secret ?: Str::random(32),
            'enabled' => $request->enabled ?? true,
            'retry_attempts' => $request->retry_attempts ?? 3,
            'timeout' => $request->timeout ?? 10,
            'headers' => $request->headers ? json_decode($request->headers, true) : [],
        ]);

        return redirect()->route('super-admin.webhooks.index')
            ->with('success', 'Webhook created successfully.');
    }

    /**
     * Display the specified webhook.
     */
    public function show(Webhook $webhook)
    {
        $webhook->load('organization');
        $logs = $webhook->logs()->latest()->paginate(50);

        return view('super-admin.webhooks.show', compact('webhook', 'logs'));
    }

    /**
     * Show the form for editing the specified webhook.
     */
    public function edit(Webhook $webhook)
    {
        $organizations = Organization::where('status', 'active')->get();
        $events = $this->getAvailableEvents();

        return view('super-admin.webhooks.edit', compact('webhook', 'organizations', 'events'));
    }

    /**
     * Update the specified webhook in storage.
     */
    public function update(Request $request, Webhook $webhook)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'events' => 'required|array',
            'events.*' => 'string|in:' . implode(',', array_keys($this->getAvailableEvents())),
            'organization_id' => 'required|exists:organizations,id',
            'secret' => 'nullable|string|min:16',
            'enabled' => 'boolean',
            'retry_attempts' => 'integer|min:0|max:5',
            'timeout' => 'integer|min:5|max:60',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $webhook->update([
            'name' => $request->name,
            'url' => $request->url,
            'events' => $request->events,
            'organization_id' => $request->organization_id,
            'secret' => $request->secret ?: $webhook->secret,
            'enabled' => $request->enabled ?? $webhook->enabled,
            'retry_attempts' => $request->retry_attempts ?? $webhook->retry_attempts,
            'timeout' => $request->timeout ?? $webhook->timeout,
            'headers' => $request->headers ? json_decode($request->headers, true) : $webhook->headers,
        ]);

        return redirect()->route('super-admin.webhooks.index')
            ->with('success', 'Webhook updated successfully.');
    }

    /**
     * Remove the specified webhook from storage.
     */
    public function destroy(Webhook $webhook)
    {
        $webhook->delete();

        return redirect()->route('super-admin.webhooks.index')
            ->with('success', 'Webhook deleted successfully.');
    }

    /**
     * Toggle webhook status.
     */
    public function toggle(Webhook $webhook)
    {
        $webhook->update(['enabled' => !$webhook->enabled]);

        return redirect()->back()
            ->with('success', 'Webhook status updated successfully.');
    }

    /**
     * Test webhook by sending a test payload.
     */
    public function test(Webhook $webhook)
    {
        try {
            $payload = [
                'event' => 'test',
                'timestamp' => now()->toISOString(),
                'data' => [
                    'message' => 'This is a test webhook payload',
                    'organization_id' => $webhook->organization_id,
                ],
            ];

            $response = $this->sendWebhook($webhook, $payload);

            return redirect()->back()
                ->with('success', 'Test webhook sent successfully. Response: ' . $response->status());
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send test webhook: ' . $e->getMessage());
        }
    }

    /**
     * Get available webhook events.
     */
    private function getAvailableEvents()
    {
        return [
            'organization.created' => 'Organization Created',
            'organization.updated' => 'Organization Updated',
            'organization.deleted' => 'Organization Deleted',
            'organization.status_changed' => 'Organization Status Changed',
            'subscription.created' => 'Subscription Created',
            'subscription.updated' => 'Subscription Updated',
            'subscription.expired' => 'Subscription Expired',
            'subscription.cancelled' => 'Subscription Cancelled',
            'payment.completed' => 'Payment Completed',
            'payment.failed' => 'Payment Failed',
            'user.created' => 'User Created',
            'user.updated' => 'User Updated',
            'user.deleted' => 'User Deleted',
            'user.role_changed' => 'User Role Changed',
        ];
    }

    /**
     * Send webhook payload.
     */
    private function sendWebhook(Webhook $webhook, array $payload)
    {
        $headers = array_merge([
            'Content-Type' => 'application/json',
            'X-Webhook-Signature' => hash_hmac('sha256', json_encode($payload), $webhook->secret),
            'X-Webhook-Timestamp' => time(),
        ], $webhook->headers);

        return \Http::withHeaders($headers)
            ->timeout($webhook->timeout)
            ->post($webhook->url, $payload);
    }
}
