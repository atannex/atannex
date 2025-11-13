<?php

namespace App\Livewire\Forms;

use App\Mail\Subscription\Verification;
use App\Models\Others\Subscription as SubscriptionModel;
use App\Rules\Auth\StrongEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class Subscription
 *
 * Handles subscription form functionality including email validation,
 * subscription creation, and verification email sending.
 */
class Subscription extends Component
{
    public string $email = '';

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    public bool $alreadySubscribed = false;

    /**
     * Initialize the component and check for existing subscription.
     */
    public function mount(): void
    {
        if (Auth::check()) {
            $userEmail = Auth::user()->email;
            $this->email = $userEmail;
            $this->alreadySubscribed = SubscriptionModel::where('email', $userEmail)->exists();
        }
    }

    /**
     * Define validation rules for the subscription form.
     *
     * @return array<string, array>
     */
    protected function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:subscriptions,email', new StrongEmail],
        ];
    }

    /**
     * Custom validation error messages.
     *
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'email.required' => 'An email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already subscribed to our newsletter.',
        ];
    }

    /**
     * Reset messages when email input changes.
     */
    public function updatedEmail(): void
    {
        $this->reset(['successMessage', 'errorMessage']);
    }

    /**
     * Process subscription form submission.
     */
    public function subscribe(): void
    {
        $this->validate();

        if (SubscriptionModel::where('email', $this->email)->exists()) {
            $this->errorMessage = 'This email is already subscribed to our newsletter.';

            return;
        }

        $subscription = SubscriptionModel::create([
            'email' => $this->email,
            'is_verified' => false,
            'verification_token' => SubscriptionModel::generateVerificationToken(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Mail::to($this->email)->queue(new Verification($subscription));

        $this->successMessage = 'Please check your email to confirm your subscription.';
        $this->reset('email');
        $this->dispatch('subscription-success');
    }

    /**
     * Render the subscription form view.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.forms.subscription');
    }
}
