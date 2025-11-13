<?php

namespace App\Models\Others;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Class Subscription
 *
 * Represents a subscription entity in the application.
 *
 * @property string $email
 * @property bool $is_verified
 * @property string|null $verification_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Subscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'is_verified',
        'verification_token',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_verified' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate a unique verification token for email verification.
     *
     * @return string A random 32-character verification token
     */
    public static function generateVerificationToken(): string
    {
        return Str::random(32);
    }

    /**
     * Mark the subscription as verified and clear the verification token.
     *
     * @return bool True if the update was successful, false otherwise
     */
    public function markAsVerified(): bool
    {
        return $this->update([
            'is_verified' => true,
            'verification_token' => null,
        ]);
    }

    /**
     * Scope a query to only include verified subscriptions.
     */
    protected function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }
}
