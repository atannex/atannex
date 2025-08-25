<?php

namespace App\Livewire\Show;

use Livewire\Component;
use App\Models\Posts\Post;

class Info extends Component
{
    public $post;
    public $isLiked;
    public $likesCount;
    public $likedAt;
    public $viewsCount;
    public $viewedAt;
    public $isRated;
    public $ratingCount;
    public $averageRating;
    public $userRating;
    public $ratedAt;
    public $isShared;
    public $sharesCount;
    public $sharedAt;
    public $ratingClicks = 0;

    /**
     * Mount the component with the given Post instance.
     */
    public function mount(Post $post)
    {
        $this->post = $post;
        $this->post->recordView();
        $this->updateInteractionData();
        $this->ratingClicks = $this->userRating ?? 0;
    }

    /**
     * Handle the like action.
     */
    public function like()
    {
        if ($this->post->like()) {
            $this->updateInteractionData();
        }
    }

    /**
     * Handle the unlike action.
     */
    public function unlike()
    {
        if ($this->post->unlike()) {
            $this->updateInteractionData();
        }
    }

    /**
     * Handle the rate action based on click count.
     */
    public function toggleRate()
    {
        $this->ratingClicks++;

        if ($this->ratingClicks > 5) {
            $this->post->unrate();
            $this->ratingClicks = 0;
        } else {
            $this->post->rate($this->ratingClicks);
        }

        $this->updateInteractionData();
    }

    /**
     * Handle the share action.
     */
    public function share()
    {
        if ($this->post->share()) {
            $this->updateInteractionData();
        }
    }

    /**
     * Handle the unshare action.
     */
    public function unshare()
    {
        if ($this->post->unshare()) {
            $this->updateInteractionData();
        }
    }

    /**
     * Update all interaction data.
     */
    protected function updateInteractionData()
    {
        $this->isLiked = $this->post->isLikedByUser();
        $this->likesCount = $this->post->likesCount();
        $this->likedAt = $this->post->likedAt();

        $this->viewsCount = $this->post->viewsCount();
        $this->viewedAt = $this->post->viewedAt();

        $this->isRated = $this->post->isRatedByUser();
        $this->ratingCount = $this->post->ratingCount();
        $this->averageRating = $this->post->averageRating() ?? 0;
        $this->userRating = $this->post->userRating();
        $this->ratedAt = $this->post->ratedAt();
        $this->ratingClicks = $this->userRating ?? 0;

        // $this->isShared = $this->post->isSharedByUser();
        // $this->sharesCount = $this->post->sharesCount();
        // $this->sharedAt = $this->post->sharedAt();
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.show.info');
    }
}
