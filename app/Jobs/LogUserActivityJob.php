<?php

namespace App\Jobs;

use App\Models\UserActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogUserActivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $tries = 3;
    public $timeout = 30;

    protected $attributes;
    protected $type;

    public function __construct(array $attributes, string $type)
    {
        $this->attributes = $attributes;
        $this->type = $type;
        $this->onQueue(config('activity.queue', 'default'));
    }

    public function handle()
    {
        try {
            if (!isset($this->attributes['user_id']) || !is_int($this->attributes['user_id'])) {
                throw new \InvalidArgumentException("Missing or invalid user_id for {$this->type} activity");
            }

            $activity = UserActivity::firstOrNew(['user_id' => $this->attributes['user_id']]);

            switch ($this->type) {
                case 'login':
                    $activity->last_login_at = $this->attributes['last_login_at'] ?? $activity->last_login_at;
                    $activity->last_login_ip = $this->attributes['last_login_ip'] ?? $activity->last_login_ip;
                    $activity->device = $this->attributes['device'] ?? $activity->device;
                    $activity->geo = $this->attributes['geo'] ?? $activity->geo;
                    $activity->last_seen_at = $this->attributes['last_seen_at'] ?? $activity->last_seen_at;
                    break;

                case 'logout':
                    $activity->last_logout_at = $this->attributes['last_logout_at'] ?? $activity->last_logout_at;
                    $activity->last_seen_at = $this->attributes['last_seen_at'] ?? $activity->last_seen_at;
                    break;

                case 'last_seen':
                    $activity->last_seen_at = $this->attributes['last_seen_at'] ?? $activity->last_seen_at;
                    break;
            }

            $activity->save();

            Log::debug("Successfully logged {$this->type} activity for user {$this->attributes['user_id']}");
        } catch (\Throwable $e) {
            Log::error("Failed to log {$this->type} activity for user " . ($this->attributes['user_id'] ?? 'unknown') . ": {$e->getMessage()}");
            $this->fail($e);
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::critical("LogUserActivityJob ({$this->type}) failed for user " . ($this->attributes['user_id'] ?? 'unknown') . " after {$this->tries} attempts: {$exception->getMessage()}");
    }
}
