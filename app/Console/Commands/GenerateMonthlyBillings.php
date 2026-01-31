<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Models\MonthlyBilling;
use Illuminate\Console\Command;
use Carbon\Carbon;

class GenerateMonthlyBillings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-monthly-billings {--month= : Month in YYYY-MM format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly billing records for all active players';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $monthYear = $this->option('month') ?: now()->format('Y-m');
        $this->info("Generating monthly billings for {$monthYear}");

        // Get all active players
        $players = Player::where('status', 'active')->get();
        $created = 0;
        $skipped = 0;

        foreach ($players as $player) {
            // Check if billing already exists for this month
            $existing = MonthlyBilling::where('player_id', $player->id)
                ->where('month_year', $monthYear)
                ->first();

            if ($existing) {
                $this->warn("Billing already exists for {$player->full_name} for {$monthYear}");
                $skipped++;
                continue;
            }

            // Create monthly billing
            $billing = $player->createMonthlyBilling($monthYear);
            $this->info("Created billing for {$player->full_name}: {$billing->monthly_fee} KES");
            $created++;
        }

        $this->info("Monthly billings generation completed: {$created} created, {$skipped} skipped");
    }
}
