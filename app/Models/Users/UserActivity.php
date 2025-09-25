<?php

namespace App\Models\Users;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use App\Models\User;

/**
 * Class UserActivity
 *
 * Represents a user's activity record, including login/logout times,
 * IP address, device information, and geolocation.
 *
 * This model is useful for auditing, security tracking, and
 * enhancing user engagement analytics.
 */
class UserActivity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_activities';

    /**
     * Indicates that this model does not use default timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'last_login_at',
        'last_logout_at',
        'last_seen_at',
        'last_login_ip',
        'device',
        'geo',
    ];

    /**
     * Attribute casting rules.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_login_at' => 'datetime:Y-m-d H:i:s',
        'last_logout_at' => 'datetime:Y-m-d H:i:s',
        'last_seen_at'  => 'datetime:Y-m-d H:i:s',
        'geo' => 'array',
    ];

    /**
     * Define relationship to the User model.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Boot the model and register event listeners / global scopes.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        /**
         * Global Scope: Select essential columns for performance.
         */
        static::addGlobalScope('selectEssentialColumns', function ($builder) {
            $builder->select($builder->qualifyColumns([
                'id', // Added to support firstOrCreate
                'user_id',
                'last_login_at',
                'last_logout_at',
                'last_seen_at',
                'last_login_ip',
                'device',
                'geo',
            ]));
        });

        /**
         * Model Event: Validate user_id before saving.
         */
        static::saving(function (self $model) {
            if (!$model->user_id || !is_int($model->user_id)) {
                Log::warning('Invalid user_id for UserActivity: ' . $model->user_id);
                throw new InvalidArgumentException("UserActivity requires a valid user_id");
            }
        });
    }
}
