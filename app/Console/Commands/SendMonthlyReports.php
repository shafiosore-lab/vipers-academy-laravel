<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Models\Attendance;
use App\Models\MonthlyBilling;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendMonthlyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-monthly-reports {--month= : Month in YYYY-MM format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send monthly attendance reports to parents and summary to WhatsApp group';

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
        $month = $this->option('month') ?: Carbon::now()->subMonth()->format('Y-m');
        $this->info("Sending monthly reports for {$month}");

        // Get all active players
        $players = Player::where('status', 'active')->get();
        $totalPlayers = $players->count();

        $totalTrainings = 0;
        $totalMatches = 0;
        $totalAttendanceRecords = 0;

        foreach ($players as $player) {
            $reportData = $this->generatePlayerReport($player, $month);
            $this->notificationService->sendMonthlyReport($player, $reportData);

            // Send financial summary if guardian exists
            if ($player->guardian) {
                $financialData = $this->generateFinancialReport($player, $month);
                $this->notificationService->sendMonthlyFinancialSummary($player->guardian, $financialData);
            }

            $totalTrainings += $reportData['trainings'];
            $totalMatches += $reportData['matches'];
            $totalAttendanceRecords += $reportData['trainings'] + $reportData['matches'];

            $this->info("Sent reports for {$player->full_name}");
        }

        // Calculate attendance rate
        $attendanceRate = $totalPlayers > 0 ? round(($totalAttendanceRecords / ($totalPlayers * 16)) * 100, 1) : 0; // Assuming 16 sessions per month

        // Send summary to WhatsApp group
        $groupData = [
            'month' => Carbon::createFromFormat('Y-m', $month)->format('F Y'),
            'total_players' => $totalPlayers,
            'total_trainings' => $totalTrainings,
            'total_matches' => $totalMatches,
            'attendance_rate' => $attendanceRate,
        ];

        $this->notificationService->sendMonthlySummary($groupData);

        $this->info("Monthly reports sent successfully for {$month}");
    }

    private function generatePlayerReport(Player $player, string $month)
    {
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $attendances = Attendance::where('player_id', $player->id)
            ->whereBetween('session_date', [$startDate, $endDate])
            ->get();

        $trainings = $attendances->where('session_type', 'training')->count();
        $matches = $attendances->where('session_type', 'match')->count();
        $totalHours = $attendances->sum('total_duration_minutes') / 60; // Convert to hours

        return [
            'month' => Carbon::createFromFormat('Y-m', $month)->format('F Y'),
            'trainings' => $trainings,
            'matches' => $matches,
            'total_hours' => round($totalHours, 1),
        ];
    }

    private function generateFinancialReport(Player $player, string $month)
    {
        $billing = MonthlyBilling::where('player_id', $player->id)
            ->where('month_year', $month)
            ->first();

        if (!$billing) {
            // Create billing if it doesn't exist
            $billing = $player->createMonthlyBilling($month);
        }

        return [
            'player_name' => $player->full_name,
            'monthly_fee' => $player->getMonthlyFee(),
            'month' => Carbon::createFromFormat('Y-m', $month)->format('F Y'),
            'amount_paid' => $billing->amount_paid,
            'closing_balance' => $billing->closing_balance,
        ];
    }
}
