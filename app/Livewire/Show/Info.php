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

    public function mount($post)
    {
        $this->post = $post;
        $this->refreshCounts();
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

    protected function refreshCounts()
    {
        $this->likeCount    = $this->post->likes()->count();
        $this->dislikeCount = $this->post->dislikes()->count();
    }
}
