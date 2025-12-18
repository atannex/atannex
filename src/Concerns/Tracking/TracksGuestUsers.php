<?php

namespace Atannex\Concerns\Tracking;

use App\Models\Controls\Session;
use App\Models\Users\UserActivity;

trait TracksGuestUsers
{
    protected function trackGuest(
        string $eventType,
        array $metadata
    ): UserActivity {
        $sessionId = session()->getId();

        $session = Session::updateOrCreate(
            ['id' => $sessionId],
            [
                'user_id'       => null,
                'ip_address'    => request()->ip(),
                'user_agent'    => request()->userAgent(),
                'last_activity' => now()->timestamp,
            ]
        );

        return UserActivity::updateOrCreate(
            [
                'session_id' => $session->id,
                'event_type' => $eventType,
                'user_id'    => null,
            ],
            [
                'metadata' => $this->buildMetadata($metadata, true),
                'geo'      => $this->resolveGeo($session->ip_address),
            ]
        );
    }
}
