<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use App\Mail\SubscriptionConfirmation;
use App\Models\Posts\Subscriber as PostsSubscriber;

class Subscriber extends Component
{
    public string $email = '';

    public bool $agree = false;

    protected array $rules = [
        'email' => 'required|email|unique:subscribers,email',
        'agree' => 'accepted',
    ];

    protected array $messages = [
        'agree.accepted' => 'You must accept the Terms & Policy to subscribe.',
    ];

    public function submit(): void
    {
        $this->validate();

        $rateLimitKey = 'subscribe-attempts:' . request()->ip();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $this->addError('email', 'Too many subscription attempts. Please try again later.');
            return;
        }

        RateLimiter::hit($rateLimitKey, 60);

        $subscriber = PostsSubscriber::create([
            'email'     => $this->email,
            'confirmed' => false,
        ]);

        Mail::to($this->email)->send(new SubscriptionConfirmation($subscriber));

        $this->reset('email', 'agree');

        session()->flash(
            'message',
            'Thank you for subscribing! Please check your email to confirm your subscription.'
        );
    }

    public function render()
    {
        return view('livewire.forms.subscriber');
    }
}
