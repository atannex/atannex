<?php

namespace App\Mail\Auth;

use Exception;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Event;
use App\Events\UserWelcomeEmailSent;

class WelcomeEmail extends Mailable
{
    use Queueable;
    use SerializesModels;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string|null $locale
     */
    public function __construct(User $user, ?string $locale = null)
    {
        $this->user = $user;

        // Set the locale using the parent class's locale property
        if ($locale) {
            $this->locale($locale);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try {
            // Retrieve configuration values
            $fromAddress = config('mail.from.address', 'no-reply@enterprise.com');
            $fromName = config('mail.from.name', 'Enterprise Platform');
            $subject = Lang::get('emails.welcome.subject', ['app' => $fromName]);

            // Build the email
            $email = $this->from($fromAddress, $fromName)
                        ->subject($subject)
                        ->markdown('emails.auth.welcome', [
                            'user' => $this->user,
                            'supportUrl' => config('app.support_url', 'https://support.enterprise.com'),
                            'appName' => $fromName,
                        ])
                        ->withSymfonyMessage(function ($message) {
                            // Add custom headers for tracking or compliance
                            $message->getHeaders()->addTextHeader('X-Email-Type', 'Welcome');
                            $message->getHeaders()->addTextHeader('X-User-ID', $this->user->id);
                        });

            // Dispatch an event for analytics or logging
            Event::dispatch(new UserWelcomeEmailSent($this->user));

            return $email;
        } catch (Exception $exception) {
            // Log error for debugging and monitoring
            Log::error('Failed to build WelcomeEmail for user: ' . $this->user->id, [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);

            throw $exception; // Rethrow for Laravel's queue to handle retries
        }
    }

    /**
     * Configure the mail queue.
     *
     * @return $this
     */
    public function configureQueue()
    {
        return $this->onQueue('emails')->onConnection('database');
    }
}
