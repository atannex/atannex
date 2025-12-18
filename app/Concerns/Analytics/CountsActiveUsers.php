<?php

namespace App\Concerns\Analytics;

use App\Models\Controls\Session;
use App\Models\User;

trait CountsActiveUsers
{
    protected function countActiveUsers(int $timeoutSeconds = 300): int
    {
        return User::whereHas('sessions', function ($query) use ($timeoutSeconds) {
            $query->where(
                'last_activity',
                '>=',
                now()->subSeconds($timeoutSeconds)->timestamp
            );
        })->count();
    }

    protected function countActiveGuests(int $timeoutSeconds = 300): int
    {
        return Session::whereNull('user_id')
            ->where(
                'last_activity',
                '>=',
                now()->subSeconds($timeoutSeconds)->timestamp
            )
            ->count();
    }
}
