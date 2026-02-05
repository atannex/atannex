<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Comments\Likeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $uuid
 * @property string|null $email
 * @property string|null $name
 * @property string|null $session_id
 * @property string $ip_address
 * @property string $user_agent
 * @property string|null $device_type
 * @property string|null $platform
 * @property string|null $browser
 * @property string|null $locale
 * @property string|null $timezone
 * @property string|null $referrer
 * @property string|null $landing_url
 * @property string $fingerprint
 * @property Carbon|null $first_seen_at
 * @property Carbon|null $last_activity_at
 * @property int $visit_count
 * @property Status $status
 * @property int $trust_score
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class Guest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'email',
        'name',
        'session_id',
        'ip_address',
        'user_agent',
        'device_type',
        'platform',
        'browser',
        'locale',
        'timezone',
        'referrer',
        'landing_url',
        'fingerprint',
        'first_seen_at',
        'last_activity_at',
        'visit_count',
        'status',
        'trust_score',
    ];

    protected $casts = [
        'uuid'             => 'string',
        'email'            => 'string',
        'name'             => 'string',
        'session_id'       => 'string',
        'ip_address'       => 'string',
        'user_agent'       => 'string',
        'device_type'      => 'string',
        'platform'         => 'string',
        'browser'          => 'string',
        'locale'           => 'string',
        'timezone'         => 'string',
        'referrer'         => 'string',
        'landing_url'      => 'string',
        'fingerprint'      => 'string',
        'first_seen_at'    => 'datetime',
        'last_activity_at' => 'datetime',
        'visit_count'      => 'integer',
        'status'           => Status::class,
        'trust_score'      => 'integer',
        'deleted_at'       => 'datetime',
    ];

    protected $dates = [
        'first_seen_at',
        'last_activity_at',
        'deleted_at',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', Status::ACTIVE);
    }

    public function scopePending($query)
    {
        return $query->where('status', Status::PENDING);
    }

    public function scopeSuspicious($query)
    {
        return $query->where('trust_score', '<', 30);
    }

    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('last_activity_at', '>=', now()->subDays($days));
    }

    public function guest(): HasOne
    {
        return $this->hasOne(Likeable::class);
    }
}
