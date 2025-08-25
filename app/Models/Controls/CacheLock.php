<?php

namespace App\Models\Controls;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CacheLock
 *
 * Represents a lock on a cache key to handle concurrency.
 *
 * @property string $key
 * @property string $owner Owner of the lock
 * @property int $expiration Unix timestamp when lock expires
 */
class CacheLock extends Model
{
    protected $table = 'cache_locks';

    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'key',
        'owner',
        'expiration',
    ];

    /**
     * Check if the lock is expired
     */
    public function isExpired(): bool
    {
        return $this->expiration < time();
    }

    /**
     * Scope to get active (non-expired) locks
     */
    public function scopeActive($query)
    {
        return $query->where('expiration', '>', time());
    }
}
