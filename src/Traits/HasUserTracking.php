<?php

namespace Atannex\Traits;

use App\Models\User;
use GeoIp2\Database\Reader;
use App\Models\Controls\Session;
use App\Models\Users\UserActivity;
use Illuminate\Support\Facades\Auth;

trait HasUserTracking
{
    public function trackActivity(string $eventType, array $metadata = []): UserActivity
    {
        $user = Auth::user();

        $session = Session::updateOrCreate(
            ['id' => session()->getId(), 'user_id' => $user->id],
            [
                'ip_address'     => request()->ip(),
                'user_agent'     => request()->userAgent(),
                'last_activity'  => now()->timestamp,
            ]
        );

        $location = $this->getGeoLocation($session->ip_address);

        $activity = UserActivity::firstOrCreate(
            [
                'session_id' => $session->id,
                'event_type' => $eventType,
                'user_id'    => $user->id,
            ],
            [
                'metadata' => [
                    'click_count'      => 0,
                    'first_event'      => $metadata,
                    'first_clicked_at' => now(),
                ],
                'geo' => $location,
            ]
        );

        $meta = $activity->metadata;
        $meta['click_count']++;
        $meta['last_event'] = $metadata;
        $meta['last_clicked_at'] = now();

        $activity->update([
            'metadata' => $meta,
            'geo'      => $location,
        ]);

        return $activity;
    }

    public function activeSessions(int $timeoutSeconds = 300)
    {
        return $this->sessions()
            ->where('last_activity', '>=', now()->subSeconds($timeoutSeconds)->timestamp)
            ->get();
    }

    public function isOnline(int $timeoutSeconds = 300): bool
    {
        return $this->activeSessions($timeoutSeconds)->isNotEmpty();
    }

    public static function countActiveUsers(int $timeoutSeconds = 300): int
    {
        return User::whereHas('sessions', function ($query) use ($timeoutSeconds) {
            $query->where('last_activity', '>=', now()->subSeconds($timeoutSeconds)->timestamp);
        })->count();
    }

    protected function getGeoLocation(string $ip): string
    {
        if (app()->environment('local')) {
            return 'Local Machine, Development';
        }

        if ($ip === '127.0.0.1' || $ip === '::1') {
            return 'Local Machine, Internal Network';
        }

        $reader = new Reader(storage_path('app/GeoLite2/GeoLite2-City.mmdb'));
        $record = $reader->city($ip);

        return trim($record->city->name . ', ' . $record->country->name);
    }
}
