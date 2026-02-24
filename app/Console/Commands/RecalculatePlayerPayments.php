<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Models\Payment;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecalculatePlayerPayments extends Command
{
    protected $signature = 'payments:recalculate-players {--dry-run : Show what would be updated without making changes}';

    protected $description = 'Recalculate all player payment statuses based on payments received';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $players = Player::with(['program', 'payments'])->get();

        $bar = $this->output->createProgressBar($players->count());
        $bar->start();

        $updated = 0;
        $totalPaid = 0;
        $totalOwing = 0;

        foreach ($players as $player) {
            $playerPayments = $player->payments()->where('payment_status', 'completed')->get();

            // Calculate totals
            $programPayments = $playerPayments->where('payment_type', 'program_fee');
            $monthlyPayments = $playerPayments->where('payment_type', 'monthly_fee');
            $registrationPayments = $playerPayments->where('payment_type', 'registration_fee');

            $totalProgramPaid = $programPayments->sum('amount');
            $totalMonthlyPaid = $monthlyPayments->sum('amount') + $registrationPayments->sum('amount');
            $playerTotalPaid = $playerPayments->sum('amount');

            // Calculate amount owing
            $programOwing = 0;
            $monthlyOwing = 0;

            // Program fee calculation
            if ($player->program_id && $player->program) {
                $programFee = $player->program->price ?? 0;
                $programOwing = max(0, $programFee - $totalProgramPaid);
            }

            // Monthly subscription calculation - use payment_category_id to get category
            $paymentCategory = \App\Models\PaymentCategory::find($player->payment_category_id);
            if ($paymentCategory) {
                $monthlyFee = $paymentCategory->monthly_amount ?? 0;
                $joiningFee = $paymentCategory->joining_fee ?? 0;

                if ($player->academy_join_date) {
                    $joinDate = Carbon::parse($player->academy_join_date);
                    $now = Carbon::now();
                    $monthsSinceJoin = $joinDate->diffInMonths($now) + 1; // Include current month

                    // Calculate total expected (joining fee + monthly fees)
                    $totalExpected = $joiningFee + ($monthlyFee * $monthsSinceJoin);

                    // Amount owing = total expected - total paid (for monthly)
                    $monthlyOwing = max(0, $totalExpected - $totalMonthlyPaid - $playerTotalPaid);

                    // Also track months owing
                    $monthsOwing = floor($monthlyOwing / max($monthlyFee, 1));
                }
            }

            $totalOwing = $programOwing + $monthlyOwing;

            // Get last payment date
            $lastPayment = $playerPayments->sortByDesc('created_at')->first();
            $lastPaymentDate = $lastPayment ? $lastPayment->created_at->toDateString() : null;

            if (!$dryRun) {
                $player->update([
                    'total_paid' => $playerTotalPaid,
                    'program_total_paid' => $totalProgramPaid,
                    'monthly_total_paid' => $totalMonthlyPaid,
                    'amount_owing' => $totalOwing,
                    'program_amount_owing' => $programOwing,
                    'monthly_amount_owing' => $monthlyOwing,
                    'last_payment_date' => $lastPaymentDate,
                    'months_owing' => $monthsOwing ?? 0,
                ]);
            }

            $updated++;
            $totalPaid += $playerTotalPaid;
            $totalOwing += $totalOwing;

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($dryRun) {
            $this->info("DRY RUN: {$updated} players would be updated.");
        } else {
            $this->info("Successfully updated {$updated} players.");
        }

        $this->table(['Metric', 'Amount'], [
            ['Total Paid', 'KES ' . number_format($totalPaid, 2)],
            ['Total Owing', 'KES ' . number_format($totalOwing, 2)],
        ]);

        return Command::SUCCESS;
    }
}
