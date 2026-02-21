<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentCategory;
use App\Models\Player;
use App\Models\PaymentApprovalRequest;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class FinanceDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:finance-officer']);
    }

    /**
     * Dashboard Overview
     */
    public function index()
    {
        $user = auth()->user();

        // Get payment statistics
        $totalRevenue = Payment::completed()->sum('amount');
        $monthlyRevenue = Payment::completed()
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');
        $pendingPayments = Payment::pending()->sum('amount');
        $overduePayments = Payment::overdue()->sum('amount');

        // Get order statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $completedOrders = Order::where('order_status', 'delivered')->count();

        // Get recent payments
        $recentPayments = Payment::with('player')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get payments by method
        $paymentsByMethod = Payment::select('payment_method')
            ->selectRaw('SUM(amount) as total, COUNT(*) as count')
            ->where('payment_status', 'completed')
            ->groupBy('payment_method')
            ->get();

        return view('staff.finance.dashboard', compact(
            'user',
            'totalRevenue',
            'monthlyRevenue',
            'pendingPayments',
            'overduePayments',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'recentPayments',
            'paymentsByMethod'
        ));
    }

    /**
     * List all payments with filtering
     */
    public function payments(Request $request)
    {
        $query = Payment::with(['player', 'category']);

        // Search by reference or player name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_reference', 'like', "%{$search}%")
                  ->orWhereHas('player', function($pq) use ($search) {
                      $pq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('payment_status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('payment_category_id', $request->category_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        $categories = PaymentCategory::active()->get();
        $statuses = ['pending', 'completed', 'failed', 'cancelled'];
        $paymentMethods = ['cash', 'mpesa', 'bank_transfer', 'cheque', 'card', 'online'];

        return view('staff.finance.payments', compact('payments', 'categories', 'statuses', 'paymentMethods'));
    }

    /**
     * Show create payment form
     */
    public function createPayment()
    {
        $players = Player::with('paymentCategory')->get();
        $categories = PaymentCategory::active()->get();
        $paymentMethods = ['cash', 'mpesa', 'bank_transfer', 'cheque', 'card', 'online'];
        $paymentTypes = [
            'monthly_fee' => 'Monthly Fee',
            'joining_fee' => 'Joining Fee',
            'registration_fee' => 'Registration Fee',
            'program_fee' => 'Program Fee',
            'tournament_fee' => 'Tournament Fee',
            'merchandise' => 'Merchandise',
            'other' => 'Other',
        ];

        return view('staff.finance.create-payment', compact('players', 'categories', 'paymentMethods', 'paymentTypes'));
    }

    /**
     * Store a new payment
     */
    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'payment_type' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'payment_reference' => 'nullable|string|unique:payments,payment_reference',
            'paid_at' => 'required|date',
            'due_date' => 'nullable|date',
            'payment_category_id' => 'nullable|exists:payment_categories,id',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Generate payment reference if not provided
        if (empty($validated['payment_reference'])) {
            $validated['payment_reference'] = Payment::generatePaymentReference();
        }

        $validated['payment_status'] = 'completed';
        $validated['created_by'] = auth()->id();

        // If payment category is not set, try to get from player
        if (empty($validated['payment_category_id'])) {
            $player = Player::find($validated['player_id']);
            if ($player && $player->payment_category_id) {
                $validated['payment_category_id'] = $player->payment_category_id;
            }
        }

        $payment = Payment::create($validated);

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'payment_created',
            'description' => "Created payment {$payment->payment_reference} for KSh " . number_format($payment->amount, 2),
            'metadata' => [
                'payment_id' => $payment->id,
                'payment_reference' => $payment->payment_reference,
                'player_id' => $payment->player_id,
                'amount' => $payment->amount,
            ],
        ]);

        Log::info('Payment created', [
            'payment_id' => $payment->id,
            'reference' => $payment->payment_reference,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('finance.payments')
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Show edit payment form
     */
    public function editPayment(Payment $payment)
    {
        $players = Player::with('paymentCategory')->get();
        $categories = PaymentCategory::active()->get();
        $paymentMethods = ['cash', 'mpesa', 'bank_transfer', 'cheque', 'card', 'online'];
        $paymentTypes = [
            'monthly_fee' => 'Monthly Fee',
            'joining_fee' => 'Joining Fee',
            'registration_fee' => 'Registration Fee',
            'program_fee' => 'Program Fee',
            'tournament_fee' => 'Tournament Fee',
            'merchandise' => 'Merchandise',
            'other' => 'Other',
        ];

        return view('staff.finance.edit-payment', compact('payment', 'players', 'categories', 'paymentMethods', 'paymentTypes'));
    }

    /**
     * Update a payment (requires admin approval)
     */
    public function updatePayment(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'payment_type' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'payment_reference' => 'nullable|string',
            'paid_at' => 'required|date',
            'due_date' => 'nullable|date',
            'payment_category_id' => 'nullable|exists:payment_categories,id',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'payment_status' => 'required|string',
            'approval_reason' => 'nullable|string',
        ]);

        // Check if significant changes were made (amount, status change, etc.)
        $significantChanges =
            $payment->amount != $validated['amount'] ||
            $payment->payment_status !== $validated['payment_status'];

        if ($significantChanges) {
            // Create approval request
            PaymentApprovalRequest::create([
                'payment_id' => $payment->id,
                'requested_by' => auth()->id(),
                'request_type' => 'update',
                'old_values' => $payment->toArray(),
                'new_values' => $validated,
                'reason' => $validated['approval_reason'] ?? 'Payment update requested',
            ]);

            // Log the activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'payment_update_requested',
                'description' => "Requested update for payment {$payment->payment_reference} - requires admin approval",
                'metadata' => [
                    'payment_id' => $payment->id,
                    'payment_reference' => $payment->payment_reference,
                ],
            ]);

            // Notify admin (would integrate with notification system)
            Log::warning('Payment update requires approval', [
                'payment_id' => $payment->id,
                'requested_by' => auth()->id(),
            ]);

            return redirect()->route('finance.payments')
                ->with('warning', 'Payment update has been submitted for admin approval.');
        }

        // For non-significant changes, apply directly
        $validated['updated_by'] = auth()->id();
        $payment->update($validated);

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'payment_updated',
            'description' => "Updated payment {$payment->payment_reference}",
            'metadata' => [
                'payment_id' => $payment->id,
                'payment_reference' => $payment->payment_reference,
            ],
        ]);

        return redirect()->route('finance.payments')
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Delete a payment (requires admin approval)
     */
    public function deletePayment(Request $request, Payment $payment)
    {
        $request->validate([
            'delete_reason' => 'required|string|min:10',
        ]);

        // Create approval request
        PaymentApprovalRequest::create([
            'payment_id' => $payment->id,
            'requested_by' => auth()->id(),
            'request_type' => 'delete',
            'old_values' => $payment->toArray(),
            'reason' => $request->delete_reason,
        ]);

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'payment_delete_requested',
            'description' => "Requested deletion of payment {$payment->payment_reference} - requires admin approval",
            'metadata' => [
                'payment_id' => $payment->id,
                'payment_reference' => $payment->payment_reference,
            ],
        ]);

        Log::warning('Payment deletion requires approval', [
            'payment_id' => $payment->id,
            'requested_by' => auth()->id(),
        ]);

        return redirect()->route('finance.payments')
            ->with('warning', 'Payment deletion has been submitted for admin approval.');
    }

    /**
     * View payment details
     */
    public function viewPayment(Payment $payment)
    {
        $payment->load(['player', 'category', 'requestedBy', 'approvedBy']);

        return view('staff.finance.view-payment', compact('payment'));
    }

    /**
     * Generate reports
     */
    public function reports(Request $request)
    {
        $query = Payment::completed();

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('paid_at', '>=', $request->date_from);
        } else {
            $query->where('paid_at', '>=', now()->subMonths(12));
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('paid_at', '<=', $request->date_to);
        }

        $monthlyData = $query
            ->selectRaw('SUM(amount) as total, DATE_FORMAT(paid_at, "%Y-%m") as month')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Revenue by category
        $byCategory = Payment::completed()
            ->selectRaw('payment_category_id, SUM(amount) as total, COUNT(*) as count')
            ->where('paid_at', '>=', now()->subMonths(12))
            ->groupBy('payment_category_id')
            ->with('category')
            ->get();

        // Revenue by method
        $byMethod = Payment::completed()
            ->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->where('paid_at', '>=', now()->subMonths(12))
            ->groupBy('payment_method')
            ->get();

        return view('staff.finance.reports', compact('monthlyData', 'byCategory', 'byMethod'));
    }

    public function recordPayment()
    {
        $pendingPayments = Payment::pending()
            ->with('player')
            ->orderBy('due_date', 'asc')
            ->get();

        return view('staff.finance.record-payment', compact('pendingPayments'));
    }

    public function sendReminders()
    {
        $overduePayments = Payment::overdue()
            ->with('player')
            ->orderBy('due_date', 'asc')
            ->get();

        $pendingPayments = Payment::pending()
            ->with('player')
            ->where('due_date', '<=', now()->addDays(7))
            ->orderBy('due_date', 'asc')
            ->get();

        return view('staff.finance.reminders', compact('overduePayments', 'pendingPayments'));
    }

    public function analytics()
    {
        $monthlyRevenue = Payment::completed()
            ->selectRaw('SUM(amount) as total, DATE_FORMAT(paid_at, "%Y-%m") as month')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $paymentsByStatus = Payment::select('payment_status')
            ->selectRaw('COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_status')
            ->get();

        $paymentsByMethod = Payment::completed()
            ->select('payment_method')
            ->selectRaw('COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        return view('staff.finance.analytics', compact('monthlyRevenue', 'paymentsByStatus', 'paymentsByMethod'));
    }
}
