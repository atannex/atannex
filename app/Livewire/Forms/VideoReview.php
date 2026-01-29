<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Posts\Video;
use App\Models\Posts\Review;
use Illuminate\Support\Facades\Auth;
use App\Enums\Flag;

class VideoReview extends Component
{
    use WithPagination;

    public ?int $videoId = null;

    public ?string $reviewer_name = null;
    public string $content = '';
    public ?int $rating = null;

    protected $rules = [
        'reviewer_name' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s\-\']+$/',
        'content'       => 'required|string|min:10|max:2000',
        'rating'        => 'required|integer|min:1|max:10',
    ];

    protected $messages = [
        'content.min' => 'The review must be at least 10 characters long.',
        'rating.required' => 'Please select a rating.',
        'rating.min' => 'Rating must be between 1 and 10.',
    ];

    public function mount(int $videoId): void
    {
        $this->videoId = $videoId;
    }

    public function submitReview(): void
    {
        $this->validate();

        Review::create([
            'reviewable_type' => Video::class,
            'reviewable_id'   => $this->videoId,
            'user_id'         => Auth::id(),
            'reviewer_name'   => $this->reviewer_name,
            'content'         => $this->content,
            'reviewer_rating' => $this->rating,
            'flag'            => Flag::PENDING_REVIEW,
            'ip_address'      => request()->ip(),
        ]);

        $this->reset(['reviewer_name', 'content', 'rating']);

        $this->resetPage();

        session()->flash('success', 'Thank you! Your review has been submitted and is pending approval.');
    }

    public function render()
    {
        $video = Video::findOrFail($this->videoId);

        $reviews = $video->approvedReviews()
            ->with('user')
            ->latest()
            ->paginate(3);

        return view('livewire.forms.video-review', [
            'video'   => $video,
            'reviews' => $reviews,
        ]);
    }
}
