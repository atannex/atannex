<?php

namespace Atannex\Traits;

use Illuminate\Http\Request;
use App\Jobs\LogUserActivityJob;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;

/**
 * Trait TracksUserActivity
 *
 * Provides reusable methods for tracking user activity (login, logout, last seen).
 *
 * Features:
 * - Uses queued jobs (LogUserActivityJob) for non-blocking writes.
 * - Captures essential activity metadata (timestamps, IP, device).
 * - Includes robust error handling with logging for observability.
 *
 * Intended usage:
 * - Applied to User model or any authenticatable entity that requires activity tracking.
 */
trait TracksUserActivity
{
    /**
     * Log a user's login activity asynchronously.
     *
     * Captures:
     * - User ID
     * - Login timestamp
     * - Login IP address
     * - Device type (detected via user agent)
     * - Last seen timestamp
     *
     * @param Request $request  The current HTTP request (used for IP and device detection).
     * @return void
     */
    public function logLogin(Request $request): void
    {
        $this->queueActivity([
            'user_id'        => $this->id,
            'last_login_at'  => now()->toDateTimeString(),
            'last_login_ip'  => $request->ip(),
            'device'         => $this->getDeviceFromRequest($request),
            'last_seen_at'   => now()->toDateTimeString(),
        ], 'login');
    }

    /**
     * Log a user's logout activity asynchronously.
     *
     * Captures:
     * - User ID
     * - Logout timestamp
     * - Last seen timestamp
     *
     * @return void
     */
    public function logLogout(): void
    {
        $this->queueActivity([
            'user_id'       => $this->id,
            'last_logout_at'=> now()->toDateTimeString(),
            'last_seen_at'  => now()->toDateTimeString(),
        ], 'logout');
    }

    /**
     * Update the last seen timestamp for a user.
     *
     * Typically called:
     * - On each request made by the authenticated user.
     * - To track active sessions and user engagement.
     *
     * @return void
     */
    public function updateLastSeenTimestamp(): void
    {
        $this->queueActivity([
            'user_id'      => $this->id,
            'last_seen_at' => now()->toDateTimeString(),
        ], 'last_seen');
    }

    /**
     * Dispatch activity logging job to the queue.
     *
     * Benefits of queueing:
     * - Keeps request/response cycle fast and non-blocking.
     * - Prevents failures in logging from impacting user experience.
     *
     * @param array<string, mixed> $attributes  Attributes to persist in UserActivity.
     * @param string $type  Type of activity (e.g., login, logout, last_seen).
     * @return void
     */
    protected function queueActivity(array $attributes, string $type): void
    {
        try {
            LogUserActivityJob::dispatch($attributes, $type);
        } catch (\Exception $e) {
            // Fail gracefully: log the exception without impacting user flow
            Log::error("Failed to dispatch {$type} activity for user {$attributes['user_id']}: {$e->getMessage()}");
        }
    }

    /**
     * Detect the device type from the request's User-Agent.
     *
     * Uses Jenssegers\Agent to classify devices:
     * - Mobile
     * - Tablet
     * - Desktop
     * - Unknown (fallback if detection fails or User-Agent is empty)
     *
     * @param Request $request
     * @return string|null  Device type or null if User-Agent is missing.
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
            // Graceful degradation: log warning and fallback to 'Unknown'
            Log::warning("Failed to detect device: {$e->getMessage()}");
            return 'Unknown';
        }
    }
}
