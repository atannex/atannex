<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

trait CanSubscribe
{
    /* -----------------------------------------------------------------
     |  Mutators
     | -----------------------------------------------------------------
     */

    /**
     * Normalize email before persistence.
     */
    public function setEmailAttribute(string $value): void
    {
        $this->attributes['email'] = strtolower(trim($value));
    }

    /* -----------------------------------------------------------------
     |  Token Generation
     | -----------------------------------------------------------------
     */

    public static function generateVerificationToken(): string
    {
        return hash('sha256', Str::random(64));
    }

    /* -----------------------------------------------------------------
     |  Verification
     | -----------------------------------------------------------------
     */

    /**
     * Mark subscription as verified.
     *
     * @param string|null $ip
     */
    public function markAsVerified(?string $ip = null): bool
    {
        $saved = $this->forceFill([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_ip' => $ip,
            'verification_token' => null,
            'verification_expires_at' => null,
        ])->save();

        if ($saved) {
            // event(new SubscriptionConfirmed($this));
        }

        return $saved;
    }

    public function verificationExpired(): bool
    {
        return $this->verification_expires_at !== null
            && now()->greaterThan($this->verification_expires_at);
    }

    /* -----------------------------------------------------------------
     |  Query Scopes
     | -----------------------------------------------------------------
     */

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }

    public function scopeUnverified(Builder $query): Builder
    {
        return $query->where('is_verified', false);
    }
}
