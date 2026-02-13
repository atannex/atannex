<?php

namespace App\Livewire\Concerns;

use App\Enums\ReactionType;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Request;

trait CanReact
{
    public int $likeCount = 0;
    public int $dislikeCount = 0;

    public bool $liked = false;
    public bool $disliked = false;

    protected int $reactCooldown = 10;

    /*
    |--------------------------------------------------------------------------
    | Public API
    |--------------------------------------------------------------------------
    */

    public function handleReaction(ReactionType $type): void
    {
        if (! $this->canReact()) {
            return;
        }

        $this->getReactableModel()->toggleReaction($type);

        $this->syncReactionState();
    }

    /*
    |--------------------------------------------------------------------------
    | State Sync
    |--------------------------------------------------------------------------
    */

    protected function syncReactionState(): void
    {
        $model = $this->getReactableModel();

        $this->likeCount    = $model->reactionCountByType(ReactionType::LIKE());
        $this->dislikeCount = $model->reactionCountByType(ReactionType::DISLIKE());

        $this->liked    = $model->isLiked();
        $this->disliked = $model->isDisliked();
    }

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */

    protected function canReact(): bool
    {
        return RateLimiter::attempt(
            $this->reactLimitKey(),
            1,
            fn() => true,
            $this->reactCooldown
        );
    }

    protected function reactLimitKey(): string
    {
        return sprintf(
            '%s:%s:%s',
            $this->getReactionPrefix(),
            $this->getReactableModel()->getKey(),
            Request::ip()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Abstract Contract
    |--------------------------------------------------------------------------
    */

    abstract protected function getReactableModel();

    protected function getReactionPrefix(): string
    {
        return 'reaction';
    }
}
