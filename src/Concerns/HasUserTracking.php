<?php

namespace Atannex\Concerns;

use GeoIp2\Database\Reader;
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
        return Auth::check()
            ? $this->trackAuth(Auth::user(), $eventType, $metadata)
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
        if (app()->environment('local') || in_array($ip, ['127.0.0.1', '::1'])) {
            return 'Local';
        }

        try {
            $reader = new Reader(
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
