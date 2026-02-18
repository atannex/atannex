<?php

namespace App\Livewire\Show;

use App\Enums\ReactionType;
use App\Livewire\Concerns\CanRate;
use App\Livewire\Concerns\CanReact;
use App\Models\Posts\Post;
use Livewire\Attributes\On;
use Livewire\Component;

class Info extends Component
{
    use CanRate;
    use CanReact;

    public Post $post;

    public mixed $module;

    public int $totalShares = 0;

    public int $totalViews = 0;

    /*
    |--------------------------------------------------------------------------
    | Lifecycle
    |--------------------------------------------------------------------------
    */

    public function mount(Post $post, $module): void
    {
        $this->post = $post;
        $this->module = $module;

        $this->post->addView();

        $this->syncRatingState();
        $this->syncReactionState();
        $this->syncShareState();
        $this->syncViewState();
    }

    public function render()
    {
        return view('livewire.show.info', [
            'module' => $this->module,
            'totalShares' => $this->totalShares,
            'totalViews' => $this->totalViews,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Reaction Events
    |--------------------------------------------------------------------------
    */

    #[On('post-liked')]
    public function like(): void
    {
        $this->handleReaction(ReactionType::LIKE());
    }

    #[On('post-disliked')]
    public function dislike(): void
    {
        $this->handleReaction(ReactionType::DISLIKE());
    }

    /*
    |--------------------------------------------------------------------------
    | Rating Events
    |--------------------------------------------------------------------------
    */

    #[On('post-rated')]
    public function rate(int $value): void
    {
        $this->handleRate($value);
    }

    /*
    |--------------------------------------------------------------------------
    | Sync Helpers
    |--------------------------------------------------------------------------
    */

    protected function syncShareState(): void
    {
        if (method_exists($this->post, 'totalShareClicks')) {
            $this->totalShares = $this->post->totalShareClicks();
        }
    }

    protected function syncViewState(): void
    {
        if (method_exists($this->post, 'viewCount')) {
            $this->totalViews = $this->post->viewCount();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Trait Contracts
    |--------------------------------------------------------------------------
    */

    protected function getRateableModel(): Post
    {
        return $this->post;
    }

    protected function getRateLimitPrefix(): string
    {
        return 'post-rating';
    }

    protected function getReactableModel(): Post
    {
        return $this->post;
    }

    protected function getReactionPrefix(): string
    {
        return 'post-reaction';
    }
}
