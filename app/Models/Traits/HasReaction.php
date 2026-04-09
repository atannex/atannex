<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Enums\ReactionType;
use App\Models\Comments\Reaction;
use Illuminate\Auth\AuthenticationException;
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

    /*
    |--------------------------------------------------------------------------
    | State Checks (Safe for guests)
    |--------------------------------------------------------------------------
    */

    public function isLiked(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return $this->reactions()
            ->where('user_id', Auth::id())
            ->where('type', ReactionType::LIKE)
            ->exists();
    }

    public function isDisliked(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return $this->reactions()
            ->where('user_id', Auth::id())
            ->where('type', ReactionType::DISLIKE)
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Core Action — Toggle (Like ↔ Dislike ↔ Null)
    |--------------------------------------------------------------------------
    */

    /**
     * Toggle the user's reaction:
     * - No reaction     → Add the requested type
     * - Same type       → Remove reaction (null)
     * - Different type  → Switch to the new type
     */
    public function toggleReaction(ReactionType $type): void
    {
        $this->ensureAuthenticated();

        $existing = $this->reactions()
            ->where('user_id', Auth::id())
            ->first();

        if (! $existing) {
            // Create new reaction
            $this->reactions()->create([
                'user_id'        => Auth::id(),
                'type'           => $type,
                'reactable_id'   => $this->getKey(),
                'reactable_type' => $this->getMorphClass(),
            ]);
            return;
        }

        if ($existing->type->is($type)) {
            // Click again → remove (null state)
            $existing->delete();
            return;
        }

        // Switch like ↔ dislike
        $existing->update(['type' => $type]);
    }

    /*
    |--------------------------------------------------------------------------
    | Aggregates (Used by CanReact trait)
    |--------------------------------------------------------------------------
    */

    public function reactionCountByType(ReactionType $type): int
    {
        return $this->reactions()
            ->where('type', $type)
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Internal Guard
    |--------------------------------------------------------------------------
    */

    protected function ensureAuthenticated(): void
    {
        if (! Auth::check()) {
            throw new AuthenticationException('You must be logged in to react.');
        }
    }
}
