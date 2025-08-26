<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesAndRestoresModelIdentifiers;

/**
 * Class UserLoggedOut
 *
 * Event triggered when a user logs out of the system.
 *
 * Why use an event here?
 * - Decouples authentication flow from logging logic.
 * - Enables multiple listeners (audit logs, notifications, analytics).
 * - Keeps user experience fast by delegating heavy work asynchronously if needed.
 */
class UserLoggedOut
{
    use Dispatchable, SerializesAndRestoresModelIdentifiers;

    /**
     * The authenticated user instance.
     *
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param User $user The logged-out user instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
