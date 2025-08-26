<?php

namespace App\Jobs;

use App\Models\UserActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LogUserActivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $attributes;
    protected string $type;

    /**
     * Create a new job instance.
     *
     * @param array<string, mixed> $attributes
     * @param string $type
     */
    public function __construct(array $attributes, string $type)
    {
        $this->attributes = $attributes;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $userId = $this->attributes['user_id'] ?? null;
        if (!$userId) {
            Log::warning("Attempted to track {$this->type} activity without user_id");
            return;
        }

        try {
            $fillable = (new UserActivity())->getFillable();
            $updateData = [];

            foreach ($this->attributes as $key => $value) {
                if (in_array($key, $fillable, true)) {
                    $updateData[$key] = $value;
                }
            }

            if (!empty($updateData)) {
                UserActivity::updateOrCreate(
                    ['user_id' => $userId],
                    $updateData
                );
            }
        } catch (\Exception $e) {
            Log::error("Failed to update {$this->type} activity for user {$userId}: {$e->getMessage()}");
        }
    }
}
