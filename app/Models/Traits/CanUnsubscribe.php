<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use App\Enums\UnSubscribeReason;
use Illuminate\Database\Eloquent\Builder;
use App\Events\UnSubscribe\UnsubscriptionCreated;

trait CanUnsubscribe
{
    /* -----------------------------------------------------------------
     |  Token Generation
     | -----------------------------------------------------------------
     */

    public static function generateUnsubscribeToken(): string
    {
        return hash('sha256', Str::random(64));
    }

    /* -----------------------------------------------------------------
     |  Unsubscribe Logic
     | -----------------------------------------------------------------
     */

    /**
     * Unsubscribe the entity.
     *
     * @param string|null $reason
     * @param string|null $ip
     */
    public function unsubscribe(
        ?UnSubscribeReason $reason = null,
        ?string $ip = null
    ): bool {
        if ($this->isUnsubscribed()) {
            return true;
        }

        $saved = $this->forceFill([
            'is_unsubscribed' => true,
            'unsubscribed_at' => now(),
            'unsubscribed_ip' => $ip,
            'unsubscribe_reason' => $reason,
            'unsubscribe_token' => null,
        ])->save();

        // if ($saved) {
        //     event(new UnsubscriptionCreated($this));
        // }

        return $saved;
    }

    public function resubscribe(): bool
    {
        return $this->forceFill([
            'is_unsubscribed' => false,
            'unsubscribed_at' => null,
            'unsubscribed_ip' => null,
            'unsubscribe_reason' => null,
        ])->save();
    }

    public function isUnsubscribed(): bool
    {
        return (bool) $this->is_unsubscribed;
    }

    /* -----------------------------------------------------------------
     |  Query Scopes
     | -----------------------------------------------------------------
     */

    public function scopeUnsubscribed(Builder $query): Builder
    {
        return $query->where('is_unsubscribed', true);
    }

    public function scopeSubscribed(Builder $query): Builder
    {
        return $query->where('is_unsubscribed', false);
    }
}
