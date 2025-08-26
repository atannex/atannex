<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesAndRestoresModelIdentifiers;

/**
 * Class UserLoggedIn
 *
 * Event triggered whenever a user successfully logs in.
 *
 * Benefits:
 * - Decouples login activity handling from the authentication controller.
 * - Allows multiple listeners (e.g., log activity, send notification, track analytics).
 * - Optimized serialization for queue performance.
 */
class UserLoggedIn
{
    use Dispatchable;
    use SerializesAndRestoresModelIdentifiers;
    /**
     * The authenticated user instance.
     *
     * @var User
     */
    public $user;

    /**
     * The current HTTP request instance.
     *
     * Used to extract IP, device, or other metadata.
     *
     * @var Request
     */
    public $request;

    /**
     * Create a new event instance.
     *
     * @param User $user The logged-in user.
     * @param Request $request The current HTTP request context.
     */
    public function __construct(User $user, Request $request)
    {
        $this->user = $user;
        $this->request = $request;
    }
}
