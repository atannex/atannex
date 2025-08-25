<?php

namespace App\Models\Controls;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cache
 *
 * Represents a cached item stored in the database.
 *
 * @property string $key
 * @property string $value
 * @property int $expiration Unix timestamp when cache expires
 */
class Cache extends Model
{
    // The table associated with the model
    protected $table = 'cache';

    // Primary key is not auto-incrementing and is a string
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    // Disable timestamps since the migration doesn't have created_at or updated_at
    public $timestamps = false;

    // Mass assignable attributes
    protected $fillable = [
        'key',
        'value',
        'expiration',
    ];

    /**
     * Check if the cached item has expired
     */
    public function isExpired(): bool
    {
        return $this->expiration < time();
    }

    /**
     * Scope to get only non-expired cache items
     */
    public function scopeValid($query)
    {
        return $query->where('expiration', '>', time());
    }
}
