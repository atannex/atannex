<?php

namespace App\Mail\Subscription;

use App\Models\Others\Subscription;
use Illuminate\Mail\Mailable;

class Verification extends Mailable
{
    public $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function build()
    {
        return $this->from('no-reply@atannex.com', config('app.name'))
            ->subject('Confirm your subscription')
            ->view('emails.subscription.verification');
    }
}
