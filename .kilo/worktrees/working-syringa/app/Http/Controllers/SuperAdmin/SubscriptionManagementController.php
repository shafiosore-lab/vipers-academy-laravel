<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionManagementController extends Controller
{
    /**
     * Display a listing of subscriptions with advanced filtering.
     */
    public function index(Request $request)
    {
        $query = Subscription::with(['organization', 'plan', 'payments']);

        // Advanced filtering
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('organization', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('plan')) {
            $query->where('plan_id', $request->input('plan'));
        }

        if ($request->filled('billing_cycle')) {
            $query->where('billing_cycle', $request->input('billing_cycle'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('end_date', '<=', $request->input('date_to'));
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $subscriptions = $query->paginate(15);

        // Get filter options
        $statuses = Subscription::select('status')->distinct()->pluck('status');
        $billingCycles = Subscription::select('billing_cycle')->distinct()->pluck('billing_cycle');
        $plans = SubscriptionPlan::all();

        return view('super-admin.subscriptions.index', compact('subscriptions', 'statuses', 'billingCycles', 'plans'));
    }

    /**
     * Show the form for creating a new subscription.
     */
    public function create()
    {
        $organizations = Organization::where('status', 'active')->get();
        $plans = SubscriptionPlan::all();
        return view('super-admin.subscriptions.create', compact('organizations', 'plans'));
    }

    /**
     * Store a newly created subscription in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'organization_id' => 'required|exists:organizations,id',
            'plan_id' => 'required|exists:subscription_plans,id',
            'status' => 'required|in:active,trial,suspended,cancelled',
            'billing_cycle' => 'required|in:monthly,annual',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'auto_renew' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $subscription = Subscription::create([
            'organization_id' => $request->input('organization_id'),
            'plan_id' => $request->input('plan_id'),
            'status' => $request->input('status'),
            'billing_cycle' => $request->input('billing_cycle'),
            'amount' => $request->input('amount'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'auto_renew' => $request->input('auto_renew', false),
            'notes' => $request->input('notes'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('super-admin.subscriptions.show', $subscription)
            ->with('success', 'Subscription created successfully.');
    }

    /**
     * Display the specified subscription.
     */
    public function show(Subscription $subscription)
    {
        $subscription->load(['organization', 'plan', 'payments']);
        return view('super-admin.subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified subscription.
     */
    public function edit(Subscription $subscription)
    {
        $organizations = Organization::all();
        $plans = SubscriptionPlan::all();
        return view('super-admin.subscriptions.edit', compact('subscription', 'organizations', 'plans'));
    }

    /**
     * Update the specified subscription in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $validator = Validator::make($request->all(), [
            'organization_id' => 'required|exists:organizations,id',
            'plan_id' => 'required|exists:subscription_plans,id',
            'status' => 'required|in:active,trial,suspended,cancelled',
            'billing_cycle' => 'required|in:monthly,annual',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'auto_renew' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $subscription->update([
            'organization_id' => $request->input('organization_id'),
            'plan_id' => $request->input('plan_id'),
            'status' => $request->input('status'),
            'billing_cycle' => $request->input('billing_cycle'),
            'amount' => $request->input('amount'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'auto_renew' => $request->input('auto_renew', false),
            'notes' => $request->input('notes'),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('super-admin.subscriptions.show', $subscription)
            ->with('success', 'Subscription updated successfully.');
    }

    /**
     * Remove the specified subscription from storage.
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('super-admin.subscriptions.index')
            ->with('success', 'Subscription deleted successfully.');
    }

    /**
     * Update subscription status.
     */
    public function updateStatus(Request $request, Subscription $subscription)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,trial,suspended,cancelled',
            'reason' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $subscription->update([
            'status' => $request->input('status'),
            'notes' => $request->input('reason'),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Subscription status updated successfully.');
    }

    /**
     * Renew subscription.
     */
    public function renew(Subscription $subscription)
    {
        $endDate = $subscription->end_date;
        $newStartDate = $endDate->copy()->addDay();
        $newEndDate = $newStartDate->copy()->addMonths($subscription->billing_cycle === 'monthly' ? 1 : 12);

        $subscription->update([
            'start_date' => $newStartDate,
            'end_date' => $newEndDate,
            'status' => 'active',
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Subscription renewed successfully.');
    }

    /**
     * Cancel subscription.
     */
    public function cancel(Subscription $subscription)
    {
        $subscription->update([
            'status' => 'cancelled',
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Subscription cancelled successfully.');
    }

    /**
     * Generate invoice for subscription.
     */
    public function generateInvoice(Subscription $subscription)
    {
        // Create payment record for the subscription
        $payment = Payment::create([
            'organization_id' => $subscription->organization_id,
            'amount' => $subscription->amount,
            'description' => "Subscription renewal for {$subscription->plan->name}",
            'status' => 'pending',
            'payment_date' => now(),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('super-admin.payments.show', $payment)
            ->with('success', 'Invoice generated successfully.');
    }

    /**
     * Get subscription analytics.
     */
    public function analytics()
    {
        $analytics = [
            'total_subscriptions' => Subscription::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'trial_subscriptions' => Subscription::where('status', 'trial')->count(),
            'suspended_subscriptions' => Subscription::where('status', 'suspended')->count(),
            'cancelled_subscriptions' => Subscription::where('status', 'cancelled')->count(),
            'monthly_revenue' => Subscription::where('status', 'active')->sum('amount'),
            'renewal_rate' => $this->calculateRenewalRate(),
            'churn_rate' => $this->calculateChurnRate(),
        ];

        return view('super-admin.subscriptions.analytics', compact('analytics'));
    }

    /**
     * Calculate renewal rate.
     */
    private function calculateRenewalRate()
    {
        // Implementation for calculating renewal rate
        return 0;
    }

    /**
     * Calculate churn rate.
     */
    private function calculateChurnRate()
    {
        // Implementation for calculating churn rate
        return 0;
    }
}
