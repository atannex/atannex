<?php

namespace App\Http\Controllers;

use App\Models\Others\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscription\Confirmation;

/**
 * Class SubscriptionController
 *
 * Handles subscription-related actions such as email verification.
 */
class SubscriptionController extends Controller
{
    /**
     * Verify a subscription using the provided token.
     *
     * @param string $token The verification token
     * @return RedirectResponse
     */
    public function verify(string $token): RedirectResponse
    {
        $subscription = Subscription::where('verification_token', $token)->firstOrFail();

        if ($subscription->markAsVerified()) {

            Mail::to($subscription->email)->queue(new Confirmation($subscription));

            return redirect('/')
                ->with('success', 'Your subscription has been confirmed successfully! A confirmation email has been queued.');
        }

        return redirect('/')
            ->with('error', 'Unable to verify subscription. Please try again or contact support.');
    }
}
