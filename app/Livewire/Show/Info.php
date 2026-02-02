<?php

namespace App\Livewire\Show;

use Livewire\Component;
use App\Models\Posts\Post;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class Info extends Component
{
    public Post $post;

    public $likeCount;
    public $dislikeCount;
    public $rating;
    public $averageRating;

    public function mount($post)
    {
        $this->post = $post;
        $this->refreshCounts();
        $this->refreshRating();
    }

    public function render()
    {
        return view('livewire.show.info');
    }

    #[On('post-liked')]
    public function toggleLike()
    {
        $this->post->isLikedBy(Auth::user())
            ? $this->post->removeReaction(Auth::user())
            : $this->post->like(Auth::user());

        $this->refreshCounts();
    }

    #[On('post-disliked')]
    public function toggleDislike()
    {
        $this->post->isDislikedBy(Auth::user())
            ? $this->post->removeReaction(Auth::user())
            : $this->post->dislike(Auth::user());

        $this->refreshCounts();
    }

    #[On('post-rated')]
    public function ratePost($rating)
    {
        $rating === 0
            ? $this->post->removeRating(Auth::id())
            : $this->post->addRating($rating, null, Auth::id());

        $this->refreshRating();
    }

    protected function refreshCounts()
    {
        $this->likeCount    = $this->post->likes()->count();
        $this->dislikeCount = $this->post->dislikes()->count();
    }

    protected function refreshRating()
    {
        $this->rating        = $this->post->ratingByUser(Auth::id())?->rating ?? 0;
        $this->averageRating = $this->post->averageRating() ?: 0;
    }
}
