<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Player;
use App\Models\Guardian;
use App\Models\MonthlyBilling;
use App\Services\NotificationService;
use App\Http\Requests\PaymentFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BillingController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $query = Payment::with(['player', 'guardian']);

        // Filter by month if provided
        if ($request->filled('month')) {
            $query->where('month_applied_to', $request->month);
        }

        // Filter by payment status
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        $payments = $query->orderBy('paid_at', 'desc')->paginate(20);

        return view('billing.index', compact('payments'));
    }

    public function create()
    {
        $guardians = Guardian::with('players')->get();
        return view('billing.create', compact('guardians'));
    }

    public function store(PaymentFormRequest $request)
    {

        DB::transaction(function () use ($request) {
            $player = Player::find($request->player_id);
            $guardian = Guardian::find($request->guardian_id);

            // Create payment record
            $payment = Payment::create([
                'payment_reference' => Payment::generatePaymentReference(),
                'user_id' => auth()->id(),
                'guardian_id' => $request->guardian_id,
                'player_id' => $request->player_id,
                'amount' => $request->amount,
                'currency' => 'KES',
                'payment_method' => $request->payment_method,
                'payment_status' => 'completed',
                'payment_type' => 'subscription_fee',
                'month_applied_to' => $request->month_applied_to,
                'paid_at' => now(),
                'notes' => $request->notes,
            ]);

            // Apply payment to billing
            $billing = $player->applyPayment($request->amount, $request->month_applied_to);

            // Send acknowledgment to guardian
            $remainingBalance = $billing->outstanding_balance;
            $this->sendPaymentAcknowledgment($guardian, $player, $request->amount, $remainingBalance);
        });

        return redirect()->route('billing.index')->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        return view('billing.show', compact('payment'));
    }

    public function billingOverview()
    {
        $currentMonth = now()->format('Y-m');

        // Get total outstanding balances
        $players = Player::where('status', 'active')->get();
        $totalOutstanding = 0;
        $monthlyRevenue = 0;

        foreach ($players as $player) {
            $totalOutstanding += $player->getCurrentOutstandingBalance();
            $monthlyRevenue += $player->getMonthlyFee();
        }

        // Get payments for current month
        $monthlyPayments = Payment::where('month_applied_to', $currentMonth)
            ->where('payment_status', 'completed')
            ->sum('amount');

        // Get guardian payment summary
        $guardians = Guardian::with('players')->get()->map(function ($guardian) {
            $totalBalance = $guardian->getTotalOutstandingBalance();
            $players = $guardian->players->count();

            return [
                'guardian' => $guardian,
                'total_balance' => $totalBalance,
                'players_count' => $players,
            ];
        })->filter(function ($data) {
            return $data['total_balance'] > 0;
        })->sortByDesc('total_balance');

        return view('billing.overview', compact(
            'totalOutstanding',
            'monthlyRevenue',
            'monthlyPayments',
            'guardians'
        ));
    }

    public function playerBilling(Player $player)
    {
        $billings = $player->monthlyBillings()->orderBy('month_year', 'desc')->get();
        $payments = $player->payments()->orderBy('paid_at', 'desc')->get();

        return view('billing.player', compact('player', 'billings', 'payments'));
    }

    public function guardianBilling(Guardian $guardian)
    {
        $players = $guardian->players;
        $payments = Payment::where('guardian_id', $guardian->id)->orderBy('paid_at', 'desc')->get();

        return view('billing.guardian', compact('guardian', 'players', 'payments'));
    }

    private function sendPaymentAcknowledgment($guardian, $player, $amount, $remainingBalance)
    {
        $message = "Hello {$guardian->full_name},\n\n🙏🏽 Thank you! We have received KES {$amount} for {$player->full_name} (KES {$player->getMonthlyFee()} category) at Mumias Vipers Academy.\n\n💼 Balance remaining: KES {$remainingBalance}\n\nWe appreciate your continued support. ⚽";

        if ($guardian->preferred_notification_channel === 'whatsapp' || $guardian->preferred_notification_channel === 'both') {
            $this->notificationService->sendWhatsAppMessage($guardian->phone, $message);
        }

        if ($guardian->preferred_notification_channel === 'sms' || $guardian->preferred_notification_channel === 'both') {
            $this->notificationService->sendSMS($guardian->phone, $message);
        }
    }
}
