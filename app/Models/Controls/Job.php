<?php

namespace App\Models\Controls;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Job
 *
 * Represents a queued job in the system.
 *
 * @property int $id
 * @property string $queue
 * @property string $payload
 * @property int $attempts
 * @property int|null $reserved_at Unix timestamp or null
 * @property int $available_at Unix timestamp
 * @property int $created_at Unix timestamp
 */
class Job extends Model
{
    protected $table = 'jobs';

    public $timestamps = false; // no Laravel timestamps columns

    protected $fillable = [
        'queue',
        'payload',
        'attempts',
        'reserved_at',
        'available_at',
        'created_at',
    ];

    protected $casts = [
        'attempts' => 'integer',
        'reserved_at' => 'integer',
        'available_at' => 'integer',
        'created_at' => 'integer',
    ];

    /**
     * Scope to get jobs that are available to be processed now.
     */
    public function scopeAvailable($query)
    {
        return $query->where('available_at', '<=', time());
    }

    /**
     * Scope to get reserved jobs (currently processing).
     */
    public function scopeReserved($query)
    {
        return $query->whereNotNull('reserved_at');
    }
}
