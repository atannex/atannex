<?php

namespace Atannex\Concerns\Tracking;

use App\Models\Controls\Session;
use App\Models\Users\UserActivity;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

trait TracksAuthenticatedUsers
{
    protected function trackAuth(
        Authenticatable $user,
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
            ['id' => $sessionId ?? uniqid('auth_', true)],
            [
                'user_id'       => $user->getAuthIdentifier(),
                'ip_address'    => $request?->ip(),
                'user_agent'    => $request?->userAgent(),
                'last_activity' => now()->timestamp,
            ]
        );

        return UserActivity::updateOrCreate(
            [
                'session_id' => $session->id,
                'event_type' => $eventType,
                'user_id'    => $user->getAuthIdentifier(),
            ],
            [
                'metadata' => $this->buildMetadata($metadata, false),
                'geo'      => $this->resolveGeo(
                    $session->ip_address ?? '0.0.0.0'
                ),
            ]
        );
    }
}
