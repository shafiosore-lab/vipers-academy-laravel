<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class NewUserNotificationService
{
    /**
     * Send login credentials to newly created staff/partner user
     */
    public function sendLoginCredentials(User $user, string $temporaryPassword): bool
    {
        try {
            $resetUrl = URL::temporarySignedRoute(
                'password.reset',
                now()->addHours(24),
                ['token' => hash('sha256', $temporaryPassword)]
            );

            $userType = ucfirst($user->user_type);
            $role = $user->roles->first();
            $roleName = $role ? ucfirst(str_replace('_', ' ', $role->name)) : 'User';

            Mail::send(
                'emails.new-user-credentials',
                [
                    'user' => $user,
                    'temporaryPassword' => $temporaryPassword,
                    'resetUrl' => $resetUrl,
                    'userType' => $userType,
                    'roleName' => $roleName,
                ],
                function ($message) use ($user, $userType) {
                    $message->to($user->email, $user->name)
                        ->subject("Your {$userType} Account Credentials - WebViper Academy");
                }
            );

            \Log::info("Login credentials sent to user: {$user->email}");
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send login credentials to {$user->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate a secure temporary password
     */
    public function generateTemporaryPassword(): string
    {
        return Str::random(16);
    }

    /**
     * Create and send credentials for a new user
     */
    public function createAndNotifyUser(User $user): bool
    {
        $temporaryPassword = $this->generateTemporaryPassword();

        // Update user with hashed password
        $user->update([
            'password' => bcrypt($temporaryPassword),
        ]);

        // Send email notification
        return $this->sendLoginCredentials($user, $temporaryPassword);
    }
}
