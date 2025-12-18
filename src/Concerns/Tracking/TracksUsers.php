<?php

namespace Atannex\Concerns\Tracking;

use App\Models\Controls\Session;
use App\Models\Users\UserActivity;
use Illuminate\Contracts\Auth\Authenticatable;

trait TracksUsers
{
    protected function trackUser(
        string $eventType,
        ?Authenticatable $user = null,
        array $metadata = []
    ): UserActivity {
        $request   = request();
        $sessionId = session()->getId();
        $userId    = $user?->getAuthIdentifier();

        $session = Session::updateOrCreate(
            ['id' => $sessionId],
            [
                'user_id'       => $userId,
                'ip_address'    => $request->ip(),
                'user_agent'    => $request->userAgent(),
                'last_activity' => now()->timestamp,
            ]
        );

        $activity = UserActivity::firstOrCreate(
            [
                'session_id' => $session->id,
                'event_type' => $eventType,
                'user_id'    => $userId,
            ],
            [
                'metadata' => array_merge([
                    'is_guest'         => $userId === null,
                    'click_count'      => 0,
                    'first_tracked_at' => now()->toDateTimeString(),
                    'visits'           => [],
                ], $metadata),
                'geo' => $this->resolveGeo($session->ip_address),
            ]
        );

        $currentMetadata = $activity->metadata ?? [];

        $currentMetadata['click_count'] =
            ($currentMetadata['click_count'] ?? 0) + 1;

        $currentMetadata['last_tracked_at'] =
            now()->toDateTimeString();

        $currentMetadata['visits'][] = [
            'url'        => $request->fullUrl(),
            'route'      => optional($request->route())->getName(),
            'method'     => $request->method(),
            'referer'    => $request->headers->get('referer'),
            'visited_at' => now()->toDateTimeString(),
        ];

        $activity->update([
            'metadata' => $currentMetadata,
        ]);

        return $activity;
    }
}
