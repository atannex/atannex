<?php

namespace App\Jobs;

use App\Models\UserActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class LogUserActivityJob
 *
 * This job is responsible for logging or updating user activity asynchronously.
 * It implements the ShouldQueue interface, allowing it to be dispatched to a queue
 * for background processing, reducing request response time.
 *
 * Usage:
 *   LogUserActivityJob::dispatch($attributes, $type);
 *
 * Attributes:
 *   - array $attributes: Key-value pairs representing user activity data.
 *   - string $type: The type/category of the activity.
 *
 * Behavior:
 *   - If a UserActivity record already exists for the given user_id, it will be updated.
 *   - Otherwise, a new record will be created.
 *
 * This class uses the following Laravel traits:
 *   - Dispatchable: Allows dispatching the job.
 *   - InteractsWithQueue: Provides access to job lifecycle events.
 *   - Queueable: Enables queue-specific configuration (connection, delay, etc.).
 */
class LogUserActivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $attributes;
    protected $type;

    public function __construct(array $attributes, string $type)
    {
        $this->attributes = $attributes;
        $this->type = $type;
    }

    public function handle()
    {
        UserActivity::updateOrCreate(
            ['user_id' => $this->attributes['user_id']],
            $this->attributes
        );
    }
}
