<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;

    /**
     * Build the password reset email.
     */
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->view('emails.auth.reset-password', [
                'user'       => $notifiable,
                'resetUrl'   => $resetUrl,
                'expiresIn'  => config('auth.passwords.users.expire', 60),
                'ipAddress' => request()->ip(),
                'device'    => request()->userAgent(),
                'requestedAt' => now(),
            ]);
    }
}
