<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Models\WebsitePlayer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncWebsitePlayers extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'players:sync-website
                            {--dry-run : Show what would be synced without making changes}
                            {--force : Force sync even for already synced players}';

    /**
     * The console command description.
     */
    protected $description = 'Sync approved players from main players table to website_players table, preventing duplicates';

    /**
     * Track duplicates by full name to detect same-name players
     */
    private array $nameDuplicates = [];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info('=== PLAYER SYNC COMMAND ===');
        $this->info('Date: ' . now()->toDateTimeString());
        $this->info('Dry Run: ' . ($dryRun ? 'YES' : 'NO'));
        $this->info('');

        try {
            // Step 1: Analyze current state
            $this->analyzeCurrentState();

            // Step 2: Check for name duplicates
            $this->detectNameDuplicates();

            // Step 3: Sync players from main table
            $syncResult = $this->syncPlayersFromMainTable($dryRun, $force);

            // Step 4: Handle existing website players without player_id
            $this->handleOrphanedWebsitePlayers($dryRun);

            // Step 5: Final summary
            $this->displaySummary($dryRun);

            if ($dryRun) {
                $this->warn('DRY RUN - No changes were made');
                $this->info('Run with --force to actually sync players');
            } else {
                Log::info('Player sync completed', $syncResult);
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Sync failed: ' . $e->getMessage());
            Log::error('Player sync failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }

    /**
     * Analyze current state of both tables
     */
    private function analyzeCurrentState(): void
    {
        $this->info('--- Current State Analysis ---');

        // Main players table
        $totalPlayers = Player::count();
        $fullyApproved = Player::where('approval_type', 'full')->count();
        $tempApproved = Player::where('approval_type', 'temporary')->count();
        $pending = Player::where('approval_type', 'none')->count();

        // Website players table
        $totalWebsitePlayers = WebsitePlayer::count();
        $withPlayerId = WebsitePlayer::whereNotNull('player_id')->count();
        $orphaned = WebsitePlayer::whereNull('player_id')->count();

        $this->info("Main Players Table:");
        $this->info("  Total: {$totalPlayers}");
        $this->info("  Fully Approved: {$fullyApproved}");
        $this->info("  Temporary Approved: {$tempApproved}");
        $this->info("  Pending/None: {$pending}");

        $this->info("\nWebsite Players Table:");
        $this->info("  Total: {$totalWebsitePlayers}");
        $this->info("  With Player ID: {$withPlayerId}");
        $this->info("  Orphaned (no player_id): {$orphaned}");
        $this->info('');
    }

    /**
     * Detect players with the same name to handle duplicates properly
     */
    private function detectNameDuplicates(): void
    {
        $this->info('--- Detecting Name Duplicates ---');

        // Get all players grouped by full name
        $duplicates = Player::select('first_name', 'last_name', DB::raw('COUNT(*) as count'))
            ->groupBy('first_name', 'last_name')
            ->having('count', '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('No duplicate names found in main players table.');
        } else {
            $this->warn("Found {$duplicates->count()} name groups with duplicates:");
            foreach ($duplicates as $dup) {
                $players = Player::where('first_name', $dup->first_name)
                    ->where('last_name', $dup->last_name)
                    ->get();

                $this->line("  '{$dup->first_name} {$dup->last_name}' appears {$dup->count} times:");
                foreach ($players as $player) {
                    $status = $player->isFullyApproved() ? 'FULL' : ($player->isApproved() ? 'TEMP' : 'NONE');
                    $this->line("    - ID {$player->id}: {$status} approval, Position: {$player->position}");
                }
            }
        }
        $this->info('');
    }

    /**
     * Sync approved players from main table to website
     */
    private function syncPlayersFromMainTable(bool $dryRun, bool $force): array
    {
        $this->info('--- Syncing Players from Main Table ---');

        // Get all approved players (both full and temporary)
        $approvedPlayers = Player::whereIn('approval_type', ['full', 'temporary'])->get();

        $synced = 0;
        $skipped = 0;
        $updated = 0;
        $errors = [];

        foreach ($approvedPlayers as $player) {
            try {
                // Check if website player already exists via player_id
                $existingWebsitePlayer = WebsitePlayer::where('player_id', $player->id)->first();

                if ($existingWebsitePlayer) {
                    // Update existing
                    if ($force) {
                        if (!$dryRun) {
                            $existingWebsitePlayer->update([
                                'first_name' => $player->first_name,
                                'last_name' => $player->last_name,
                                'category' => $player->category,
                                'position' => $player->position,
                                'age' => $player->age,
                                'jersey_number' => $player->jersey_number,
                                'image_path' => $player->image_path,
                                'bio' => $player->bio,
                            ]);
                        }
                        $updated++;
                        $this->line("  UPDATED: {$player->full_name} (ID: {$player->id})");
                    } else {
                        $skipped++;
                        $this->line("  SKIPPED: {$player->full_name} (already synced, use --force to update)");
                    }
                } else {
                    // Check for duplicate by name (to prevent same-name conflicts)
                    $nameMatch = WebsitePlayer::where('first_name', $player->first_name)
                        ->where('last_name', $player->last_name)
                        ->whereNull('player_id') // Only check orphaned records
                        ->first();

                    if ($nameMatch) {
                        // There's an orphaned player with same name - need to handle this
                        $this->warn("  DUPLICATE NAME FOUND: {$player->full_name}");
                        $this->warn("    Existing orphaned website player ID: {$nameMatch->id}");
                        $this->warn("    To merge, manually update website_player ID {$nameMatch->id} with player_id = {$player->id}");

                        if (!$dryRun) {
                            // Link the orphaned record to this player
                            $nameMatch->update(['player_id' => $player->id]);
                            $this->info("    MERGED: Linked orphaned record to player ID {$player->id}");
                        }
                        $synced++;
                    } else {
                        // Create new
                        if (!$dryRun) {
                            WebsitePlayer::create([
                                'first_name' => $player->first_name,
                                'last_name' => $player->last_name,
                                'category' => $player->category,
                                'position' => $player->position,
                                'age' => $player->age,
                                'jersey_number' => $player->jersey_number,
                                'image_path' => $player->image_path,
                                'bio' => $player->bio,
                                'player_id' => $player->id,
                            ]);
                        }
                        $synced++;
                        $this->line("  SYNCED: {$player->full_name} (ID: {$player->id})");
                    }
                }
            } catch (\Exception $e) {
                $errors[] = "Failed to sync {$player->full_name}: " . $e->getMessage();
                $this->error("  ERROR: {$player->full_name} - " . $e->getMessage());
            }
        }

        $this->info("\nSync Results:");
        $this->info("  Synced: {$synced}");
        $this->info("  Updated: {$updated}");
        $this->info("  Skipped: {$skipped}");
        $this->info("  Errors: " . count($errors));

        return [
            'synced' => $synced,
            'updated' => $updated,
            'skipped' => $skipped,
            'errors' => $errors,
            'total_approved' => $approvedPlayers->count()
        ];
    }

    /**
     * Handle orphaned website players (those without player_id)
     */
    private function handleOrphanedWebsitePlayers(bool $dryRun): void
    {
        $this->info('--- Handling Orphaned Website Players ---');

        $orphaned = WebsitePlayer::whereNull('player_id')->get();

        if ($orphaned->isEmpty()) {
            $this->info('No orphaned website players found.');
            return;
        }

        $this->warn("Found {$orphaned->count()} orphaned website players (no player_id):");

        foreach ($orphaned as $player) {
            $this->line("  - ID {$player->id}: {$player->first_name} {$player->last_name}");
            $this->line("    Position: {$player->position}, Category: {$player->category}");
            $this->line("    These players were uploaded directly to website and need manual linking");
        }

        $this->info("\nTo link these to main players, update the website_players table:");
        $this->info("  UPDATE website_uploaded_players SET player_id = <player_id> WHERE id = <website_player_id>;");

        if (!$dryRun) {
            Log::info('Orphaned website players need manual review', [
                'count' => $orphaned->count(),
                'ids' => $orphaned->pluck('id')->toArray()
            ]);
        }
    }

    /**
     * Display final summary
     */
    private function displaySummary(bool $dryRun): void
    {
        $this->info('');
        $this->info('--- Final Summary ---');

        $totalWebsite = WebsitePlayer::count();
        $withPlayerId = WebsitePlayer::whereNotNull('player_id')->count();
        $orphaned = WebsitePlayer::whereNull('player_id')->count();

        $this->info("Website Players Table After Sync:");
        $this->info("  Total: {$totalWebsite}");
        $this->info("  Linked to Main Players: {$withPlayerId}");
        $this->info("  Orphaned: {$orphaned}");

        // Show sample of synced players
        $this->info("\nSample Synced Players:");
        $syncedPlayers = WebsitePlayer::whereNotNull('player_id')
            ->with('player')
            ->take(5)
            ->get();

        foreach ($syncedPlayers as $wp) {
            $approval = $wp->player && $wp->player->isFullyApproved() ? 'FULL' :
                       ($wp->player && $wp->player->isApproved() ? 'TEMP' : 'N/A');
            $this->line("  - {$wp->first_name} {$wp->last_name} ({$wp->position}) - Approval: {$approval}");
        }
    }
}
