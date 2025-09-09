<?php

namespace App\Jobs\ProcessUsers;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\Auth\Auth\RegisteredMail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RegisteredJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('SendWelcomeEmailJob executed for: ' . $this->user->email);
        Mail::to($this->user->email)->send(new RegisteredMail($this->user));
    }
}
