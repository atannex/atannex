<?php

namespace App\Mail;

use App\Models\Posts\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionConfirmation extends Mailable
{
    use Queueable;
    use SerializesModels;
    public $subscription;

    public function __construct(Subscriber $subscription)
    {
        $this->subscription = $subscription;
    }

    public function build()
    {
        $confirmUrl = route('subscription.confirm', ['token' => $this->subscription->token]);

        $htmlContent = <<<HTML
            <p>Hi,</p>
            <p>Thanks for subscribing! Please confirm your subscription by clicking the link below:</p>
            <p><a href="{$confirmUrl}">Confirm Subscription</a></p>
            <p>If you did not request this subscription, you can ignore this email.</p>
            <p>Regards,<br>Your Website Team</p>
        HTML;

        return $this->subject('Confirm Your Subscription')
                    ->html($htmlContent);
    }
}
