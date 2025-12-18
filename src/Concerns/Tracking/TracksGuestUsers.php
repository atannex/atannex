<?php

namespace Atannex\Concerns\Tracking;

use App\Models\Controls\Session;
use App\Models\Users\UserActivity;
use Illuminate\Http\Request;

trait TracksGuestUsers
{
    protected function trackGuest(
        string $eventType,
        array $metadata = []
    ): UserActivity {
        $request = app()->bound(Request::class)
            ? app(Request::class)
            : null;

        $sessionId = session()->isStarted()
            ? session()->getId()
            : null;

        $session = Session::updateOrCreate(
            ['id' => $sessionId ?? uniqid('guest_', true)],
            [
                'user_id'       => null,
                'ip_address'    => $request?->ip(),
                'user_agent'    => $request?->userAgent(),
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
                'geo'      => $this->resolveGeo(
                    $session->ip_address ?? '0.0.0.0'
                ),
            ]
        );
    }
}
