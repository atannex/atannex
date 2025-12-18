<?php

namespace Atannex\Concerns\Tracking;

trait TracksGuestUsers
{
    use TracksUsers;

    protected function trackGuest(
        string $eventType,
        array $metadata = []
    ) {
        return $this->trackUser($eventType, null, $metadata);
    }
}
