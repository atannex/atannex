<?php

namespace Atannex\Traits;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Controls\Session;
use App\Models\User;
use App\Models\Users\UserActivity;
use Illuminate\Support\Facades\Auth;
use GeoIp2\Database\Reader;

trait HasUserTracking
{
    /**
     * Track user activity.
     *
     * @param string $eventType
     * @param array $metadata
     * @return UserActivity|null
     */
    public function trackActivity(string $eventType, array $metadata = []): ?UserActivity
    {
        $user = Auth::user() ?? $this;
        if (!$user) {
            return null;
        }

        $sessionId = session()->getId();
        $session = Session::updateOrCreate(
            ['id' => $sessionId, 'user_id' => $user->id],
            [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'last_activity' => now()->timestamp,
            ]
        );

        // Get user location via GeoIP
        $location = $this->getGeoLocation($session->ip_address);

        return UserActivity::create([
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'event_type' => $eventType,
            'metadata' => $metadata,
            'geo' => $location,
        ]);
    }

    /**
     * Get active sessions for the user.
     *
     * @param int $timeoutSeconds
     * @return Collection
     */
    public function activeSessions(int $timeoutSeconds = 300)
    {
        return $this->sessions()
            ->where('last_activity', '>=', now()->subSeconds($timeoutSeconds)->timestamp)
            ->get();
    }

    /**
     * Check if the user is online.
     *
     * @param int $timeoutSeconds
     * @return bool
     */
    public function isOnline(int $timeoutSeconds = 300): bool
    {
        return $this->activeSessions($timeoutSeconds)->isNotEmpty();
    }

    /**
     * Count all active users.
     *
     * @param int $timeoutSeconds
     * @return int
     */
    public static function countActiveUsers(int $timeoutSeconds = 300): int
    {
        return User::whereHas('sessions', function ($query) use ($timeoutSeconds) {
            $query->where('last_activity', '>=', now()->subSeconds($timeoutSeconds)->timestamp);
        })->count();
    }

    /**
     * Get GeoIP location from IP address.
     *
     * @param string $ip
     * @return string
     */
    protected function getGeoLocation(string $ip): string
    {
        $location = 'Unknown Location';
        try {
            $reader = new Reader(storage_path('app/GeoLite2/GeoLite2-City.mmdb'));
            $record = $reader->city($ip);
            $city = $record->city->name ?? '';
            $country = $record->country->name ?? '';
            $location = trim(sprintf('%s, %s', $city, $country)) ?: 'Unknown Location';
            $reader->close();
        } catch (Exception) {
        }

        return $location;
    }
}
