<?php

namespace App\Livewire\Concerns;

use App\Enums\ReactionType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

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
        // Redirect guest users to login (NO manual intended override)
        if (! Auth::check()) {
            $this->redirectRoute('login');
            return;
        }

        // Rate limit check
        if (! $this->canReact()) {
            return;
        }

        // Toggle reaction
        $this->getReactableModel()->toggleReaction($type);

        // Refresh UI
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
        if (! Auth::check()) {
            return false;
        }

        $key = $this->reactLimitKey();

        if (RateLimiter::tooManyAttempts($key, 1)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('reaction', "Please wait {$seconds} second(s) before reacting again.");
            return false;
        }

        return RateLimiter::attempt(
            $key,
            1,
            fn () => true,
            $this->reactCooldown
        );
    }

    protected function reactLimitKey(): string
    {
        return sprintf(
            'reaction:%s:%s:%s',
            $this->getReactionPrefix(),
            $this->getReactableModel()->getKey(),
            Auth::id()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    protected function getReactionPrefix(): string
    {
        return 'reaction';
    }

    /*
    |--------------------------------------------------------------------------
    | Contract
    |--------------------------------------------------------------------------
    */

    abstract protected function getReactableModel();
}
