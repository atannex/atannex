<?php

namespace App\Models\Controls;

use Illuminate\Database\Eloquent\Model;

/**
 * Class JobBatch
 *
 * Represents a batch of jobs grouped together.
 *
 * @property string $id
 * @property string $name
 * @property int $total_jobs
 * @property int $pending_jobs
 * @property int $failed_jobs
 * @property string $failed_job_ids JSON-encoded array of failed job IDs
 * @property string|null $options JSON-encoded options or null
 * @property int|null $cancelled_at Unix timestamp or null
 * @property int $created_at Unix timestamp
 * @property int|null $finished_at Unix timestamp or null
 */
class JobBatch extends Model
{
    protected $table = 'job_batches';

    protected $primaryKey = 'id';
    public $incrementing = false; // non-incrementing string primary key
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'total_jobs',
        'pending_jobs',
        'failed_jobs',
        'failed_job_ids',
        'options',
        'cancelled_at',
        'created_at',
        'finished_at',
    ];

    protected $casts = [
        'total_jobs' => 'integer',
        'pending_jobs' => 'integer',
        'failed_jobs' => 'integer',
        'failed_job_ids' => 'array', // auto json encode/decode
        'options' => 'array',
        'cancelled_at' => 'integer',
        'created_at' => 'integer',
        'finished_at' => 'integer',
    ];

    /**
     * Check if the batch is cancelled
     */
    public function isCancelled(): bool
    {
        return !is_null($this->cancelled_at);
    }

    /**
     * Check if the batch is finished
     */
    public function isFinished(): bool
    {
        return !is_null($this->finished_at);
    }
}
