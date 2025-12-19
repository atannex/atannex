<?php

declare(strict_types=1);

namespace App\Livewire\Show;

use App\Models\Posts\Post;
use Atannex\Interactions\Components\CanLike;
use Atannex\Interactions\Components\CanRate;
use Atannex\Interactions\Components\CanShare;
use Atannex\Interactions\Components\CanView;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Livewire Component: Post Interaction Card
 *
 * Renders a post with real-time interaction counters:
 * - Likes (toggle + count)
 * - Ratings (1–5 stars + average)
 * - Shares (event-based total)
 * - Views (unique per visitor)
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
     * Mount the component.
     *
     * Records a unique view and initializes interaction state.
     */
    public function mount(Post $post): void
    {
        $this->post = $post;

        $this->recordView();
        $this->refreshInteractionState();
    }

    /**
     * Refresh all interaction counters from the database.
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
     */
    public function hydrate(): void
    {
        $this->refreshInteractionState();
    }

    /**
     * Listen for real-time interaction updates via Laravel Echo.
     */
    #[On('echo:interactions,InteractionUpdated')]
    public function refreshOnBroadcast(): void
    {
        $this->refreshInteractionState();
    }

    /**
     * Render the component view.
     */
    public function render(): View
    {
        return view('livewire.show.info');
    }
}
