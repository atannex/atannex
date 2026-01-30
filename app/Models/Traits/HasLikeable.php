<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\User;
use App\Enums\ReactionType;
use App\Models\Comments\Likeable;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasLikeable
{
    /* ----------------------------- Checks ----------------------------- */

    /**
     * Check if the given user liked this model.
     */
    public function isLikedBy(User $user): bool
    {
        return $this->hasReaction($user, ReactionType::LIKE);
    }

    /**
     * Check if the given user disliked this model.
     */
    public function isDislikedBy(User $user): bool
    {
        return $this->hasReaction($user, ReactionType::DISLIKE);
    }

    /* ----------------------------- Actions ----------------------------- */

    /**
     * React with a like.
     */
    public function like(User $user): void
    {
        $this->setReaction($user, ReactionType::LIKE);
    }

    /**
     * React with a dislike.
     */
    public function dislike(User $user): void
    {
        $this->setReaction($user, ReactionType::DISLIKE);
    }

    /**
     * Remove any reaction from the given user.
     */
    public function removeReaction(User $user): void
    {
        $this->reactions()
            ->where('user_id', $user->id)
            ->delete();

        $this->syncReactionCounters();
    }

    /* ----------------------------- Relations ----------------------------- */

    /**
     * Polymorphic relation to Likeable model.
     */
    public function reactions(): MorphMany
    {
        return $this->morphMany(Likeable::class, 'likeable');
    }

    /**
     * Only like reactions.
     */
    public function likes(): MorphMany
    {
        return $this->reactionsByType(ReactionType::LIKE);
    }

    /**
     * Only dislike reactions.
     */
    public function dislikes(): MorphMany
    {
        return $this->reactionsByType(ReactionType::DISLIKE);
    }

    /**
     * Filter reactions by type.
     */
    protected function reactionsByType(ReactionType|string $type): MorphMany
    {
        $value = $type instanceof ReactionType ? $type->value : $type;

        return $this->reactions()->where('type', $value);
    }

    /* ----------------------------- Helpers ----------------------------- */

    /**
     * Check if the given user has reacted with a specific type.
     */
    protected function hasReaction(User $user, ReactionType|string $type): bool
    {
        return $this->reactionsByType($type)
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Set or update a reaction for the given user.
     */
    protected function setReaction(User $user, ReactionType|string $type): void
    {
        $value = $type instanceof ReactionType ? $type->value : $type;

        $this->reactions()->updateOrCreate(
            ['user_id' => $user->id],
            ['type' => $value]
        );

        $this->syncReactionCounters();
    }

    /**
     * Synchronize the like_count and dislike_count fields if they exist.
     */
    protected function syncReactionCounters(): void
    {
        if (! $this->hasReactionCounters()) {
            return;
        }

        $this->forceFill([
            'like_count'    => $this->likes()->count(),
            'dislike_count' => $this->dislikes()->count(),
        ])->save();
    }

    /**
     * Check if the model has like_count and dislike_count fields in casts.
     */
    protected function hasReactionCounters(): bool
    {
        return
            property_exists($this, 'casts') &&
            array_key_exists('like_count', $this->casts) &&
            array_key_exists('dislike_count', $this->casts);
    }
}
