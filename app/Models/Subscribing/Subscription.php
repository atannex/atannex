<?php

namespace App\Models\Subscribing;

use App\Models\User;
use App\Enums\UnSubscribeReason;
use App\Models\Traits\CanSubscribe;
use App\Models\Traits\CanUnsubscribe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Subscription extends Model
{
    use SoftDeletes;
    use CanSubscribe;
    use CanUnsubscribe;
    use HasFactory;

    /**
     * Explicit table name.
     */
    protected $table = 'subscriptions';

    /**
     * Mass assignable attributes.
     * State & security fields are internally controlled.
     */
    protected $fillable = [
        'email',
        'user_id',
    ];

    /**
     * Sensitive attributes hidden from serialization.
     */
    protected $hidden = [
        'verification_token',
        'unsubscribe_token',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'is_verified' => 'boolean',
        'is_unsubscribed' => 'boolean',

        'verified_at' => 'datetime',
        'verification_expires_at' => 'datetime',
        'unsubscribed_at' => 'datetime',

        'unsubscribe_reason' => UnSubscribeReason::class,

        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Model Lifecycle
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (self $subscription) {
            $subscription->verification_token = self::generateVerificationToken();
            $subscription->verification_expires_at = now()->addHours(
                config('subscriptions.verification_expiry_hours', 24)
            );

            $subscription->unsubscribe_token = self::generateUnsubscribeToken();

            $subscription->is_verified = false;
            $subscription->is_unsubscribed = false;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Subscription owner (optional).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
