<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;

class ApproveAllUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'users:approve-all {--send-credentials : Send login credentials to all users}';

    /**
     * The console command description.
     */
    protected $description = 'Approve all pending users and configure them for immediate dashboard access';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Starting user approval process...');
        $this->newLine();

        $users = User::where('approval_status', '!=', 'approved')
            ->orWhere('status', '!=', 'active')
            ->get();

        if ($users->isEmpty()) {
            $this->info('✅ All users are already approved and active!');
            return Command::SUCCESS;
        }

        $this->info("Found {$users->count()} users needing approval/activation.");
        $this->newLine();

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            $oldStatus = $user->status;
            $oldApproval = $user->approval_status;

            // Update user status and approval
            $user->update([
                'status' => 'active',
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id() ?? 1,
            ]);

            // Assign default roles based on user type
            $this->assignDefaultRole($user);

            $this->line("  ✅ {$user->name} ({$user->email}): {$oldStatus}/{$oldApproval} → active/approved");

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('🎉 User approval complete!');
        $this->info('Summary:');
        $this->info("  - Total users processed: {$users->count()}");
        $this->info("  - Admin users: " . User::where('user_type', 'admin')->count());
        $this->info("  - Staff users: " . User::where('user_type', 'staff')->count());
        $this->info("  - Partner users: " . User::where('user_type', 'partner')->count());
        $this->info("  - Player users: " . User::where('user_type', 'player')->count());

        return Command::SUCCESS;
    }

    /**
     * Assign default role based on user type.
     */
    private function assignDefaultRole(User $user): void
    {
        $defaultRoles = [
            'admin' => 'super-admin',
            'staff' => 'coach',
            'partner' => null, // Partners don't need roles, just user_type
            'player' => 'player',
        ];

        $defaultRole = $defaultRoles[$user->user_type] ?? null;

        if ($defaultRole && !$user->hasRole($defaultRole)) {
            $role = Role::where('slug', $defaultRole)->first();
            if ($role) {
                $user->assignRole($role);
            }
        }
    }
}
