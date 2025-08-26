<?php

namespace App\Jobs;

use App\Models\UserActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Class LogUserActivityJob
 *
 * Queueable job to persist or update user activity data asynchronously.
 *
 * Benefits of queueing:
 * - Keeps authentication and user flow responsive (non-blocking).
 * - Offloads potentially expensive DB writes to background processing.
 * - Ensures resilience with retry/failed job handling.
 */
class LogUserActivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Activity attributes passed into the job.
     * Should only contain fields defined in UserActivity::$fillable.
     *
     * @var array<string, mixed>
     */
    protected array $attributes;

    /**
     * Type of activity being logged (e.g., login, logout, seen).
     * Used mainly for logging/debugging purposes.
     *
     * @var string
     */
    protected string $type;

    /**
     * Create a new job instance.
     *
     * @param array<string, mixed> $attributes  Key-value pairs of user activity data.
     * @param string $type  Activity type identifier (login, logout, etc.).
     */
    public function __construct(array $attributes, string $type)
    {
        $this->attributes = $attributes;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * Responsible for persisting user activity data safely:
     * - Validates `user_id` before attempting persistence.
     * - Filters attributes to prevent unwanted mass-assignment.
     * - Uses `updateOrCreate` to either insert a new record or update the existing one.
     *
     * @return void
     */
    public function handle(): void
    {
        // Ensure we have a valid user reference.
        $userId = $this->attributes['user_id'] ?? null;
        if (!$userId) {
            Log::warning("Attempted to track {$this->type} activity without user_id");
            return;
        }

        try {
            // Get only fillable attributes defined in UserActivity to avoid mass-assignment vulnerabilities.
            $fillable = (new UserActivity())->getFillable();
            $updateData = [];

            foreach ($this->attributes as $key => $value) {
                if (in_array($key, $fillable, true)) {
                    $updateData[$key] = $value;
                }
            }

            // Only attempt DB update if we have valid data to persist.
            if (!empty($updateData)) {
                UserActivity::updateOrCreate(
                    ['user_id' => $userId], // Find existing record
                    $updateData             // Update fields or insert new record
                );
            }
        } catch (\Exception $e) {
            // Catch any exception to prevent job failure loop
            Log::error("Failed to update {$this->type} activity for user {$userId}: {$e->getMessage()}");
        }
    }
}
