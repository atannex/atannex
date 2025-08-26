<?php

namespace App\Jobs;

use App\Models\UserActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Class LogUserActivityJob
 *
 * Asynchronously logs or updates user activity in the user_activities table.
 * Implements ShouldQueue for background processing to reduce request response time.
 *
 * Usage:
 *   LogUserActivityJob::dispatch($attributes, $type);
 *
 * Attributes:
 *   - array $attributes: Key-value pairs representing user activity data (e.g., user_id, last_login_at, geo).
 *   - string $type: The type/category of the activity (e.g., login, logout, last_seen).
 *
 * Behavior:
 *   - Updates an existing UserActivity record for the given user_id or creates a new one.
 *   - Includes error handling and logging for observability.
 *   - Validates required attributes before processing.
 *
 * Configuration:
 *   - Uses configurable queue from config/activity.php.
 *   - Limits retries to 3 attempts and sets a 30-second timeout.
 */
class LogUserActivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     *
     * @var int
     */
    public $timeout = 30;

    protected $attributes;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @param array<string, mixed> $attributes Activity data to store.
     * @param string $type Activity type (e.g., login, logout, last_seen).
     */
    public function __construct(array $attributes, string $type)
    {
        $this->attributes = $attributes;
        $this->type = $type;
        $this->onQueue(config('activity.queue', 'default'));
    }

    /**
     * Execute the job.
     *
     * Validates attributes and performs updateOrCreate on the UserActivity model.
     * Logs errors for observability without impacting the queue.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Validate required attributes
            if (!isset($this->attributes['user_id']) || !is_int($this->attributes['user_id'])) {
                throw new \InvalidArgumentException("Missing or invalid user_id for {$this->type} activity");
            }

            // Perform update or create
            UserActivity::updateOrCreate(
                ['user_id' => $this->attributes['user_id']],
                $this->attributes
            );

            // Log success for debugging (optional, can be removed in production)
            Log::info("Successfully logged {$this->type} activity for user {$this->attributes['user_id']}");
        } catch (\Throwable $e) {
            Log::error("Failed to log {$this->type} activity for user " . ($this->attributes['user_id'] ?? 'unknown') . ": {$e->getMessage()}");
            $this->fail($e); // Mark job as failed after logging
        }
    }

    /**
     * Handle a job failure.
     *
     * Logs the failure and optionally performs a fallback action (e.g., log to file).
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        Log::critical("LogUserActivityJob ({$this->type}) failed for user " . ($this->attributes['user_id'] ?? 'unknown') . " after {$this->tries} attempts: {$exception->getMessage()}");
        // Optional: Implement fallback (e.g., write to a file or send to a dead-letter queue)
    }
}
