<?php

namespace App\Models\Controls;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class FailedJob
 *
 * Represents a failed queued job.
 *
 * @property int $id
 * @property string $uuid Unique identifier
 * @property string $connection Queue connection name
 * @property string $queue Queue name
 * @property string $payload Job payload JSON
 * @property string $exception Exception details
 * @property Carbon $failed_at Timestamp of failure
 */
class FailedJob extends Model
{
    protected $table = 'failed_jobs';

    protected $fillable = [
        'uuid',
        'connection',
        'queue',
        'payload',
        'exception',
        'failed_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'failed_at' => 'datetime',
    ];
}
