<?php

namespace Atannex\Concerns\Tracking;

use Illuminate\Contracts\Auth\Authenticatable;

trait TracksAuthenticatedUsers
{
    use TracksUsers;

    protected function trackAuth(
        Authenticatable $user,
        string $eventType,
        array $metadata = []
    ) {
        return $this->trackUser($eventType, $user, $metadata);
    }
}
