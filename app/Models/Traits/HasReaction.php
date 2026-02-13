<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Enums\ReactionType;
use App\Models\Comments\Reaction;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

trait HasReaction
{
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function likes(): MorphMany
    {
        return $this->reactions()->where('type', ReactionType::LIKE());
    }

    public function dislikes(): MorphMany
    {
        return $this->reactions()->where('type', ReactionType::DISLIKE());
    }

    protected function currentVisitorReaction(): MorphMany
    {
        return $this->reactions()
            ->where('visitor_key', $this->resolveVisitorKey());
    }

    /*
    |--------------------------------------------------------------------------
    | State Checks
    |--------------------------------------------------------------------------
    */

    public function hasReacted(): bool
    {
        return $this->currentVisitorReaction()->exists();
    }

    public function currentReaction(): ?ReactionType
    {
        return $this->currentVisitorReaction()->value('type');
    }

    public function isLiked(): bool
    {
        return $this->isReactedAs(ReactionType::LIKE());
    }

    public function isDisliked(): bool
    {
        return $this->isReactedAs(ReactionType::DISLIKE());
    }

    public function isReactedAs(ReactionType $type): bool
    {
        return $this->currentVisitorReaction()
            ->where('type', $type)
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Core Actions
    |--------------------------------------------------------------------------
    */

    public function react(ReactionType $type): void
    {
        $this->reactions()->updateOrCreate(
            [
                'visitor_key' => $this->resolveVisitorKey(),
            ],
            [
                'user_id'     => Auth::id(),
                'session_id'  => request()->session()->getId(),
                'ip_address'  => request()->ip(),
                'type'        => $type,
            ]
        );
    }

    public function toggleReaction(ReactionType $type): void
    {
        $current = $this->currentVisitorReaction()->first();

        if (! $current) {
            $this->react($type);
            return;
        }

        if ($current->type === $type) {
            $this->removeReaction();
            return;
        }

        $this->react($type);
    }

    public function removeReaction(): void
    {
        $this->currentVisitorReaction()->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Aggregates
    |--------------------------------------------------------------------------
    */

    public function reactionCount(): int
    {
        return $this->reactions()->count();
    }

    public function reactionCountByType(ReactionType $type): int
    {
        return $this->reactions()
            ->where('type', $type)
            ->count();
    }
}
