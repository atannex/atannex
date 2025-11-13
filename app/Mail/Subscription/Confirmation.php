<?php

namespace App\Mail\Subscription;

use App\Models\Others\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Confirmation extends Mailable
{
    use Queueable;
    use SerializesModels;

    public Subscription $subscription;

    /**
     * Create a new message instance.
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Subscription Confirmed!')
            ->view('emails.subscription.confirmation')
            ->with([
                'name' => $this->subscription->name,
                'email' => $this->subscription->email,
            ]);
    }
}
