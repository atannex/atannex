<?php

namespace Atannex\Traits;

use Exception;
use Throwable;
use Illuminate\Http\Request;
use App\Jobs\LogUserActivity;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;
use GeoIp2\Database\Reader;

/**
 * Trait TracksUserActivity
 *
 * Provides reusable methods for tracking user activity (login, logout, last seen).
 *
 * Features:
 * - Uses queued jobs (LogUserActivityJob) for non-blocking writes.
 * - Captures comprehensive activity metadata (timestamps, IP, device, geolocation).
 * - Includes robust error handling with logging for observability.
 * - Throttles updates to prevent excessive database writes.
 * - Configurable queue for job dispatching.
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
     * - Login IP address (validated)
     * - Device details (type, browser, platform)
     * - Geolocation data (country, city)
     * - Last seen timestamp
     *
     * @param Request $request The current HTTP request (used for IP and device detection).
     * @return void
     */
    public function logLogin(Request $request): void
    {
        $ip = $request->ip();
        if ($ip && !filter_var($ip, FILTER_VALIDATE_IP)) {
            Log::warning('Invalid IP address: ' . $ip);
            $ip = null;
        }

        $geo = null;
        if (!in_array($ip, ['127.0.0.1', '::1'])) {
            try {
                $reader = new Reader(storage_path('app/GeoLite2-City.mmdb'));
                $record = $reader->city($ip);
                $geo = [
                    'country' => $record->country->name,
                    'city' => $record->city->name,
                ];
            } catch (Exception $e) {
                Log::warning(sprintf('Failed to detect GeoIP for IP %s: %s', $ip, $e->getMessage()));
            }
        } else {
            $geo = ['ip' => $ip, 'note' => 'Localhost, GeoIP skipped'];
        }


        $this->queueActivity([
            'user_id'        => $this->id,
            'last_login_at'  => now()->toDateTimeString(),
            'last_login_ip'  => $ip,
            'device'         => $this->getDeviceFromRequest($request),
            'geo'            => $geo ? json_encode($geo) : null,
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
     * Throttles to avoid duplicate logout entries within a short time frame.
     *
     * @param int $threshold Minimum seconds between logout updates (default: 60)
     * @return void
     */
    public function logLogout(int $threshold = 60): void
    {
        try {
            $activity = $this->activity()->first();
            if ($activity && $activity->last_logout_at && now()->diffInSeconds($activity->last_logout_at) < $threshold) {
                return;
            }

            $this->queueActivity([
                'user_id'        => $this->id,
                'last_logout_at' => now()->toDateTimeString(),
                'last_seen_at'   => now()->toDateTimeString(),
            ], 'logout');
        } catch (Throwable $throwable) {
            Log::error(sprintf('Failed to log logout for user %s: %s', $this->id, $throwable->getMessage()));
        }
    }

    /**
     * Update the last seen timestamp for a user.
     *
     * Throttled to prevent excessive DB writes:
     * - Only queues an update if the previous 'last_seen_at' is older than $threshold seconds.
     *
     * @param int $threshold Minimum seconds between updates (default: 60)
     * @return void
     */
    public function updateLastSeenTimestamp(int $threshold = 60): void
    {
        try {
            $activity = $this->activity()->firstOrCreate(['user_id' => $this->id]);

            if ($activity->last_seen_at && now()->diffInSeconds($activity->last_seen_at) < $threshold) {
                return;
            }

            $this->queueActivity([
                'user_id'      => $this->id,
                'last_seen_at' => now()->toDateTimeString(),
            ], 'last_seen');
        } catch (Throwable $throwable) {
            Log::error(sprintf('Failed to update last seen for user %s: %s', $this->id, $throwable->getMessage()));
        }
    }

    /**
     * Dispatch activity logging job to the queue.
     *
     * Benefits of queueing:
     * - Keeps request/response cycle fast and non-blocking.
     * - Prevents failures in logging from impacting user experience.
     * - Uses configurable queue from config/activity.php.
     *
     * @param array<string, mixed> $attributes Attributes to persist in UserActivity.
     * @param string $type Type of activity (e.g., login, logout, last_seen).
     * @return void
     */
    protected function queueActivity(array $attributes, string $type): void
    {
        try {
            LogUserActivity::dispatch($attributes, $type)
                ->onQueue(config('activity.queue', 'default'));
        } catch (Exception $exception) {
            Log::error(sprintf('Failed to dispatch %s activity for user %s: %s', $type, $attributes['user_id'], $exception->getMessage()));
        }
    }

    /**
     * Detect the device type from the request's User-Agent.
     *
     * Uses Jenssegers\Agent to classify devices and capture additional metadata:
     * - Type: Mobile, Tablet, Desktop, or Unknown
     * - Browser
     * - Platform
     *
     * @param Request $request
     * @return string|null JSON-encoded device info or null if User-Agent is missing.
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
            $deviceInfo = [
                'type' => $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : ($agent->isDesktop() ? 'Desktop' : 'Unknown')),
                'browser' => $agent->browser() ?: 'Unknown',
                'platform' => $agent->platform() ?: 'Unknown',
            ];
            return json_encode($deviceInfo);
        } catch (Exception $exception) {
            Log::warning('Failed to detect device: ' . $exception->getMessage());
            return json_encode(['type' => 'Unknown', 'browser' => 'Unknown', 'platform' => 'Unknown']);
        }
    }
}
