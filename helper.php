<?php

use Illuminate\Support\Facades\Auth;
use App\Models\Users\UserLogs;
use Carbon\Carbon;
use Illuminate\Support\Str;

if (!function_exists('seo_title')) {
    /**
     * Generate an SEO-friendly title for pages.
     *
     * @param string|null $subject The main subject of the page.
     * @param string|null $suffix Optional suffix for the title.
     * @param int $suffixMaxLength Max length of the suffix, default 60.
     * @return string The formatted SEO title.
     */
    function seo_title(?string $subject = null, ?string $suffix = null, int $suffixMaxLength = 60): string
    {
        $defaultSuffix = __('Top Stories, Breaking News & Headlines');
        $suffix = $suffix ? Str::limit($suffix, $suffixMaxLength, '...') : $defaultSuffix;

        $subject = $subject ?? (Auth::check()
            ? Str::title(strtolower(Auth::user()->name))
            : $defaultSuffix
        );

        $appName = config('app.name');

        $parts = [$subject];

        if (strtolower(trim($suffix)) !== strtolower(trim($subject))) {
            $parts[] = $suffix;
        }
        $parts[] = $appName;

        $titleParts = array_map(function ($part) use ($subject, $appName) {
            if ($part === $appName) {
                return $appName;
            }

            return $part === $subject ? Str::title(strtolower($part)) : Str::upper($part);
        }, $parts);

        return implode(' | ', $titleParts);
    }
}

if (!function_exists('addUserActivity')) {
    /**
     * Log a user's activity if it hasn't occurred in the last 24 hours.
     *
     * @param int $userId The ID of the user.
     * @param string $activity The activity description.
     * @return UserLogs|null The created log entry or null if skipped.
     */
    function addUserActivity(int $userId, string $activity)
    {
        $device = request()->header('device-name') ?? request()->userAgent();
        $ip = request()->ip();

        $recentActivity = UserLogs::where('user_id', $userId)
            ->where('ip', $ip)
            ->where('activity', $activity)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->latest()
            ->first();

        if ($recentActivity) {
            return null;
        }

        return UserLogs::create([
            'user_id' => $userId,
            'ip' => $ip,
            'device' => $device,
            'activity' => $activity,
        ]);
    }
}
