<?php

namespace Atannex\Traits;

use Illuminate\Http\Request;
use App\Jobs\LogUserActivityJob;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;

/**
 * Trait TracksUserActivity
 *
 * Optimized user activity tracking with async processing and enhanced error handling.
 */
trait TracksUserActivity
{
    /**
     * Log a user's login activity asynchronously.
     *
     * @param Request $request
     * @return void
     */
    public function logLogin(Request $request): void
    {
        $this->queueActivity([
            'user_id' => $this->id,
            'last_login_at' => now()->toDateTimeString(),
            'last_login_ip' => $request->ip(),
            'device' => $this->getDeviceFromRequest($request),
            'last_seen_at' => now()->toDateTimeString(),
        ], 'login');
    }

    /**
     * Log a user's logout activity asynchronously.
     *
     * @return void
     */
    public function logLogout(): void
    {
        $this->queueActivity([
            'user_id' => $this->id,
            'last_logout_at' => now()->toDateTimeString(),
            'last_seen_at' => now()->toDateTimeString(),
        ], 'logout');
    }

    /**
     * Update the last seen timestamp for activity tracking.
     *
     * @return void
     */
    public function updateLastSeenTimestamp(): void
    {
        $this->queueActivity([
            'user_id' => $this->id,
            'last_seen_at' => now()->toDateTimeString(),
        ], 'last_seen');
    }

    /**
     * Dispatch activity job to queue.
     *
     * @param array<string, mixed> $attributes
     * @param string $type
     * @return void
     */
    protected function queueActivity(array $attributes, string $type): void
    {
        try {
            LogUserActivityJob::dispatch($attributes, $type);
        } catch (\Exception $e) {
            Log::error("Failed to dispatch {$type} activity for user {$attributes['user_id']}: {$e->getMessage()}");
        }
    }

    /**
     * Detect the device type from the request.
     *
     * @param Request $request
     * @return string|null
     */
    protected function getDeviceFromRequest(Request $request): ?string
    {
        $userAgent = $request->userAgent();
        if (empty($userAgent)) {
            return null;
        }

        try {
            $agent = new Agent();
            $agent->setUserAgent($userAgent);

            if ($agent->isMobile()) return 'Mobile';
            if ($agent->isTablet()) return 'Tablet';
            if ($agent->isDesktop()) return 'Desktop';

            return 'Unknown';
        } catch (\Exception $e) {
            Log::warning("Failed to detect device: {$e->getMessage()}");
            return 'Unknown';
        }
    }
}
