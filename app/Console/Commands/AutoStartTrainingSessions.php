<?php

namespace App\Console\Commands;

use App\Models\TrainingSession;
use Illuminate\Console\Command;

class AutoStartTrainingSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-start-training-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically start training sessions when their scheduled time is reached';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for training sessions to auto-start...');

        // Find scheduled sessions that have reached their start time
        $sessionsToStart = TrainingSession::where('status', 'scheduled')
            ->where('scheduled_start_time', '<=', now())
            ->get();

        if ($sessionsToStart->isEmpty()) {
            $this->info('No sessions to start.');
            return;
        }

        $count = 0;
        foreach ($sessionsToStart as $session) {
            try {
                // Find an admin user to set as started_by (assuming user ID 1 is admin)
                $adminUser = \App\Models\User::where('role', 'admin')->first();
                $userId = $adminUser ? $adminUser->id : 1;

                $session->start($userId);
                $this->info("Started session: {$session->team_category} {$session->session_type}");
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to start session {$session->id}: {$e->getMessage()}");
            }
        }

        $this->info("Successfully started {$count} training sessions.");
    }
}
