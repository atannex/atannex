<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Posts\Subscriber;

class SubscriptionController extends Controller
{
    public function confirm(string $token): RedirectResponse
    {
        $subscriber = Subscriber::where('token', $token)->first();

        if (!$subscriber) {
            return redirect()
                ->route('home')
                ->with('error', 'Invalid or expired confirmation token.');
        }

        if ($subscriber->confirmed) {
            return redirect()
                ->route('home')
                ->with('message', 'Your subscription has already been confirmed.');
        }

        $subscriber->update(['confirmed' => true]);

        return redirect()
            ->route('home')
            ->with('message', 'Subscription confirmed! Thank you for joining us.');
    }
}
