<?php

use App\Models\UserLogs;
use Carbon\Carbon;

function addUserActivity($userId, $activity)
{
    $device = request()->header('device-name') ?? request()->userAgent();
    $ip = request()->ip();

    $userActivity = UserLogs::where('ip', $ip)
        ->where('activity', $activity)
        ->latest()
        ->first();

    if (
        $userActivity &&
        $userActivity->created_at->gt(Carbon::now()->subDay()) &&
        $activity == $userActivity->activity
    ) {
        return;
    } else {
        return UserLogs::create([
            'user_id' => $userId,
            'ip' => $ip,
            'device' => $device,
            'activity' => $activity,
        ]);
    }
}


