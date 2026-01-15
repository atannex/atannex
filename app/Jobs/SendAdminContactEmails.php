<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\ContactMessageNotification;
use App\Models\Others\Contact;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAdminContactEmails implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public function __construct(
        public readonly Contact $contact
    ) {}

    public function handle(): void
    {
        User::role([
            'Super Administrator',
            'Admin',
            'Content Editor',
            'Community Moderator',
        ])
            ->select('id', 'email')
            ->chunkById(50, function ($users) {
                foreach ($users as $user) {
                    Mail::to($user->email)
                        ->queue(new ContactMessageNotification($this->contact));
                }
            });
    }
}
