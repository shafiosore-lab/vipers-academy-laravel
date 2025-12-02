<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Player;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with('user')->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('payment_type', $request->type);
        }

        // Filter by payer type
        if ($request->filled('payer_type')) {
            $query->where('payer_type', $request->payer_type);
        }

        // Search by reference or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_reference', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $payments = $query->paginate(15);

        // Calculate summary statistics
        $stats = [
            'total_payments' => Payment::count(),
            'total_revenue' => Payment::completed()->sum('amount'),
            'pending_payments' => Payment::pending()->count(),
            'completed_payments' => Payment::completed()->count(),
            'overdue_payments' => Payment::overdue()->count(),
            'monthly_revenue' => Payment::completed()
                ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('amount'),
        ];

        // Revenue by payment type
        $revenueByType = Payment::completed()
            ->selectRaw('payment_type, SUM(amount) as total')
            ->groupBy('payment_type')
            ->pluck('total', 'payment_type')
            ->toArray();

        return view('admin.payments.index', compact('payments', 'stats', 'revenueByType'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $players = Player::all();
        $partners = User::where('user_type', 'partner')->get();

        return view('admin.payments.create', compact('players', 'partners'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'payer_type' => 'required|in:player,partner,customer',
            'payer_id' => 'required|integer',
            'payment_type' => 'required|in:registration_fee,subscription_fee,program_fee,tournament_fee,merchandise,donation,sponsorship,other',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'payment_method' => 'required|in:mpesa,card,bank_transfer,cash,cheque',
            'payment_gateway' => 'nullable|in:mpesa,stripe,paypal,bank,cash',
            'due_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $payment = Payment::create([
            'payment_reference' => Payment::generatePaymentReference(),
            'user_id' => auth()->id(),
            'payer_type' => $request->payer_type,
            'payer_id' => $request->payer_id,
            'payment_type' => $request->payment_type,
            'description' => $request->description,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'payment_method' => $request->payment_method,
            'payment_gateway' => $request->payment_gateway,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.payments.show', $payment)
                        ->with('success', 'Payment record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load('user');
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $players = Player::all();
        $partners = User::where('user_type', 'partner')->get();

        return view('admin.payments.edit', compact('payment', 'players', 'partners'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,completed,failed,refunded,cancelled',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $updateData = [
            'payment_status' => $request->payment_status,
            'transaction_id' => $request->transaction_id,
            'notes' => $request->notes,
        ];

        // Set paid_at timestamp when status changes to completed
        if ($request->payment_status === 'completed' && !$payment->paid_at) {
            $updateData['paid_at'] = now();
        }

        $payment->update($updateData);

        return redirect()->route('admin.payments.show', $payment)
                        ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        if ($payment->isPending()) {
            $payment->delete();
            return redirect()->route('admin.payments.index')
                            ->with('success', 'Payment record deleted successfully.');
        }

        return redirect()->route('admin.payments.show', $payment)
                        ->with('error', 'Cannot delete completed payment records.');
    }

    /**
     * Update payment status via AJAX
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,refunded,cancelled',
        ]);

        $payment->update(['payment_status' => $request->status]);

        if ($request->status === 'completed' && !$payment->paid_at) {
            $payment->update(['paid_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get financial summary report
     */
    public function financialReport(Request $request)
    {
        try {
            $startDate = $request->get('start_date', now()->startOfMonth());
            $endDate = $request->get('end_date', now()->endOfMonth());

            // Ensure dates are Carbon instances
            if (is_string($startDate)) {
                $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            }
            if (is_string($endDate)) {
                $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
            }

            $report = [
                'total_revenue' => 0,
                'revenue_by_type' => collect(),
                'revenue_by_payer_type' => collect(),
                'pending_payments' => 0,
                'overdue_payments' => 0,
            ];

            // Only calculate if there are any payments
            if (Payment::count() > 0) {
                $report['total_revenue'] = Payment::completed()
                    ->whereBetween('paid_at', [$startDate, $endDate])
                    ->sum('amount') ?? 0;

                $report['revenue_by_type'] = Payment::completed()
                    ->whereBetween('paid_at', [$startDate, $endDate])
                    ->selectRaw('payment_type, SUM(amount) as total')
                    ->groupBy('payment_type')
                    ->pluck('total', 'payment_type') ?? collect();

                $report['revenue_by_payer_type'] = Payment::completed()
                    ->whereBetween('paid_at', [$startDate, $endDate])
                    ->selectRaw('payer_type, SUM(amount) as total')
                    ->groupBy('payer_type')
                    ->pluck('total', 'payer_type') ?? collect();

                $report['pending_payments'] = Payment::pending()
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('amount') ?? 0;

                $report['overdue_payments'] = Payment::overdue()
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('amount') ?? 0;
            }

            return view('admin.payments.report', compact('report', 'startDate', 'endDate'));
        } catch (\Exception $e) {
            \Log::error('Financial report error: ' . $e->getMessage());
            return back()->with('error', 'Error generating financial report: ' . $e->getMessage());
        }
    }
}
