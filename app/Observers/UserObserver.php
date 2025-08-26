<?php

namespace App\Observers;

use App\Models\User;
use App\Events\UserStatusChanged;
use Illuminate\Support\Facades\Request;

class UserObserver
{
    public function loggedIn(User $user): void
    {
        $activity = $user->activity()->firstOrCreate([]);
        $activity->recordLogin(Request::ip(), Request::header('User-Agent'));
        broadcast(new UserStatusChanged($user));
    }

    public function loggedOut(User $user): void
    {
        $activity = $user->activity()->firstOrCreate([]);
        $activity->recordLogout();
        broadcast(new UserStatusChanged($user));
    }
}

