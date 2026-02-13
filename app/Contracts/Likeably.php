<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Enums\ReactionType;

/**
 * Interface Reactable
 *
 * Contract for any model that supports reactions
 * (like / dislike) via identifier-based tracking.
 */
interface Likeably
{
    /**
     * Determine if the identifier has liked this model.
     */
    public function isLiked(?string $identifier): bool;

    /**
     * Determine if the identifier has disliked this model.
     */
    public function isDisliked(?string $identifier): bool;

    /**
     * Add or update a reaction for the given identifier.
     */
    public function react(?string $identifier, ReactionType $type, ?string $ipAddress = null): void;

    /**
     * Remove any reaction associated with the identifier.
     */
    public function removeReaction(?string $identifier): void;
}
