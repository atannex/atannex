<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

/**
 * Class UserActivity
 *
 * Represents a user's activity record, including login/logout times,
 * IP address, and device information.
 *
 * This model is useful for auditing, security tracking, and
 * enhancing user engagement analytics.
 */
class UserActivity extends Model
{
    /**
     * The table associated with the model.
     *
     * Explicitly defined to ensure correct mapping,
     * especially if table name deviates from Laravel conventions.
     *
     * @var string
     */
    protected $table = 'user_activities';

    /**
     * Indicates that this model does not use the default
     * created_at and updated_at timestamps.
     *
     * We manage our own timestamp fields (login/logout/seen).
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Attributes that are mass assignable.
     *
     * Helps prevent mass assignment vulnerabilities.
     * Only listed attributes can be set via `create()` or `fill()`.
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
    ];

    /**
     * Attribute casting rules.
     *
     * Ensures datetime fields are returned as Carbon instances,
     * formatted consistently for storage and retrieval.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_login_at' => 'datetime:Y-m-d H:i:s',
        'last_logout_at' => 'datetime:Y-m-d H:i:s',
        'last_seen_at'  => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Define relationship to the User model.
     *
     * Each activity record belongs to exactly one user.
     * Useful for retrieving the user associated with a given activity.
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
     * - Adds a global scope to optimize queries by only selecting
     *   essential columns (performance improvement).
     * - Validates `user_id` before saving to prevent invalid records.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        /**
         * Global Scope: Select only essential columns for performance.
         * Prevents unnecessary data fetching unless explicitly overridden.
         */
        static::addGlobalScope('selectEssentialColumns', function ($builder) {
            $builder->select($builder->qualifyColumns([
                'user_id',
                'last_login_at',
                'last_logout_at',
                'last_seen_at',
                'last_login_ip',
                'device',
            ]));
        });

        /**
         * Model Event: Before saving, validate `user_id`.
         *
         * - Ensures integrity by blocking persistence of records
         *   without a valid user reference.
         * - Logs a warning for debugging / auditing purposes.
         *
         * NOTE: Throws exception instead of silently failing,
         * making the error explicit for upstream handling.
         */
        static::saving(function (self $model) {
            if (!$model->user_id || !is_int($model->user_id)) {
                Log::warning("Invalid user_id for UserActivity: {$model->user_id}");
                throw new \InvalidArgumentException("UserActivity requires a valid user_id");
            }
        });
    }
}
