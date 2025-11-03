<?php

declare(strict_types=1);

namespace App\Livewire\Show;

use App\Models\Posts\Post;
use Atannex\Interactions\Components\{
    CanLike,
    CanRate,
    CanShare,
    CanView
};
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\View\View;

/**
 * Livewire Component: Post Interaction Card
 *
 * Renders a post with real-time interaction counters:
 * - Likes (toggle + count)
 * - Ratings (1–5 stars + average)
 * - Shares (display-only total)
 * - Views (auto-recorded)
 *
 * All state is synchronized on mount, hydration, and via Laravel Echo broadcasts.
 *
 * @property-read Post $post The shareable post model instance.
 */
class Info extends Component
{
    use CanLike;
    use CanRate;
    use CanShare;
    use CanView;

    /**
     * The post being displayed and interacted with.
     *
     * Locked to prevent client-side tampering.
     */
    #[Locked]
    public Post $post;

    /**
     * Total number of shares across all users and platforms.
     *
     * Synchronized via `syncShareState()` from the `CanShare` trait.
     */
    public int $sharesCount = 0;

    /**
     * Mount the component with the given post.
     *
     * Records a view and initializes all interaction states.
     *
     * @param Post $post The post model instance.
     */
    public function mount(Post $post): void
    {
        $this->post = $post;

        $this->recordView();
        $this->refreshInteractionState();
    }

    /**
     * Refresh all interaction counters from the database.
     *
     * Called on mount, hydration, and real-time updates.
     */
    protected function refreshInteractionState(): void
    {
        $this->syncLikeState();
        $this->syncRatingState();
        $this->syncShareState();
        $this->syncViewState();
    }

    /**
     * Re-sync interaction state when the component rehydrates.
     *
     * Ensures UI reflects latest data after browser tab restore or network reconnect.
     */
    public function hydrate(): void
    {
        $this->refreshInteractionState();
    }

    /**
     * Listen for real-time interaction updates via Laravel Echo.
     *
     * Refreshes all counters when another user interacts with the same post.
     *
     * @listens echo:interactions,InteractionUpdated
     */
    #[On('echo:interactions,InteractionUpdated')]
    public function refreshOnBroadcast(): void
    {
        $this->refreshInteractionState();
    }

    /**
     * Render the Blade view for this component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.show.info');
    }
}
