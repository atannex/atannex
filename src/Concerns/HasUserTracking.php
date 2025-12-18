<?php

namespace Atannex\Concerns;

use App\Models\Users\UserActivity;
use Illuminate\Support\Facades\Auth;
use Atannex\Concerns\Tracking\TracksGuestUsers;
use Atannex\Concerns\Tracking\TracksAuthenticatedUsers;

trait HasUserTracking
{
    use TracksGuestUsers;
    use TracksAuthenticatedUsers;

    public function trackActivity(
        string $eventType,
        array $metadata = []
    ): UserActivity {
        $user = Auth::user();

        return $user
            ? $this->trackAuth($user, $eventType, $metadata)
            : $this->trackGuest($eventType, $metadata);
    }

    protected function buildMetadata(
        array $metadata,
        bool $isGuest
    ): array {
        return array_merge(
            [
                'click_count' => 1,
                'is_guest'    => $isGuest,
                'tracked_at'  => now()->toDateTimeString(),
            ],
            $metadata
        );
    }

    protected function resolveGeo(string $ip): string
    {
        if (
            app()->environment('local') ||
            in_array($ip, ['127.0.0.1', '::1', '0.0.0.0'], true)
        ) {
            return 'Local';
        }

        try {
            $reader = new \GeoIp2\Database\Reader(
                storage_path('app/GeoLite2/GeoLite2-City.mmdb')
            );

            $record = $reader->city($ip);

            return trim(
                ($record->city->name ?? '') . ', ' .
                    ($record->country->name ?? '')
            );
        } catch (\Throwable) {
            return 'Unknown';
        }
    }
}
