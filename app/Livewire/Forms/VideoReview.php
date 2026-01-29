<?php

namespace App\Livewire\Forms;

use App\Enums\Flag;
use Livewire\Attributes\Rule;
use App\Models\Posts\Review;
use App\Models\Posts\Video;
use Atannex\Services\SpamDetector;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class VideoReview extends Component
{
    use WithPagination;

    #[Locked]
    public int $videoId;

    #[Rule(['nullable', 'string', 'max:100', "regex:/^[a-zA-Z0-9\s\-']+$/"])]
    public ?string $reviewer_name = null;

    #[Rule(['required', 'string', 'min:10', 'max:2000'])]
    public string $content = '';

    #[Rule(['required', 'integer', 'min:1', 'max:5'])]
    public ?int $rating = null;

    public ?int $editingReviewId = null;

    protected function messages(): array
    {
        return [
            'reviewer_name.regex' => 'The name may only contain letters, numbers, spaces, hyphens and apostrophes.',
            'content.required'    => 'Please write your review.',
            'content.min'         => 'The review must be at least 10 characters long.',
            'content.max'         => 'The review may not exceed 2000 characters.',
            'rating.required'     => 'Please select a rating.',
            'rating.min'          => 'Rating must be between 1 and 5.',
            'rating.max'          => 'Rating must be between 1 and 5.',
        ];
    }

    public function mount(int $videoId): void
    {
        $this->videoId = $videoId;
    }

    public function submitReview(): void
    {
        Gate::authorize('create', Review::class);

        $this->validate();

        $user = Auth::user();
        $userId = $user?->id;
        $ip = request()?->ip() ?? 'unknown';

        if ($this->isRateLimited($userId, $ip)) {
            $this->addError('content', 'You are submitting reviews too quickly. Please try again later.');
            return;
        }

        if ($this->isSpamDetected()) {
            $this->addError('content', 'Your review was blocked by our spam protection.');
            return;
        }

        if ($this->hasExistingReview($userId)) {
            $this->addError('content', 'You have already submitted a review for this video.');
            return;
        }

        $this->createReview($userId, $ip);

        $this->resetForm();
        session()->flash('success', 'Thank you! Your review has been submitted and is pending approval.');
    }

    public function editReview(int $reviewId): void
    {
        $review = Review::findOrFail($reviewId);

        Gate::authorize('update', $review);

        if (! $review->canEdit()) {
            $this->addError('global', 'Editing is no longer allowed for this review.');
            return;
        }

        $this->editingReviewId = $review->id;
        $this->content = $review->content;
        $this->rating = $review->reviewer_rating;
        $this->reviewer_name = $review->reviewer_name;
    }

    public function updateReview(): void
    {
        $review = Review::findOrFail($this->editingReviewId);

        Gate::authorize('update', $review);

        if (! $review->canEdit()) {
            $this->addError('global', 'Editing window has expired.');
            return;
        }

        $this->validate();

        $review->update([
            'content'         => $this->content,
            'reviewer_rating' => $this->rating,
            'flag'            => Flag::PENDING_REVIEW,
        ]);

        $this->resetForm();
        session()->flash('success', 'Your review has been updated and is pending approval.');
    }

    public function deleteReview(int $reviewId): void
    {
        $review = Review::findOrFail($reviewId);

        Gate::authorize('delete', $review);

        $review->delete();

        $this->resetPage();
        session()->flash('success', 'Your review has been deleted.');
    }

    protected function resetForm(): void
    {
        $this->reset([
            'reviewer_name',
            'content',
            'rating',
            'editingReviewId',
        ]);

        $this->resetPage();
    }

    private function isRateLimited(?int $userId, string $ip): bool
    {
        $fingerprint = substr(sha1(request()->userAgent() . '|' . $ip), 0, 32);
        $rateKey = "video-review:{$this->videoId}:" . ($userId ?? 'guest') . ":{$fingerprint}";

        if (RateLimiter::tooManyAttempts($rateKey, 2)) {
            return true;
        }

        RateLimiter::hit($rateKey, 600); // 10 minutes

        return false;
    }

    private function isSpamDetected(): bool
    {
        $spamDetector = app(SpamDetector::class);
        $attempts = RateLimiter::attempts("video-review:{$this->videoId}:*");

        $score = $spamDetector->score($this->content, $attempts);

        return $spamDetector->isSpam($score);
    }

    private function hasExistingReview(?int $userId): bool
    {
        if (! $userId) {
            return false;
        }

        return Review::withTrashed()
            ->where('reviewable_type', Video::class)
            ->where('reviewable_id', $this->videoId)
            ->where('user_id', $userId)
            ->exists();
    }

    private function createReview(?int $userId, string $ip): void
    {
        Review::create([
            'reviewable_type' => Video::class,
            'reviewable_id'   => $this->videoId,
            'user_id'         => $userId,
            'reviewer_name'   => $this->reviewer_name,
            'content'         => $this->content,
            'reviewer_rating' => $this->rating,
            'flag'            => Flag::PENDING_REVIEW,
            'ip_address'      => $ip,
        ]);
    }

    public function render(): View
    {
        $video = Video::query()
            ->withCount('approvedReviews')
            ->findOrFail($this->videoId);

        $reviews = $video->approvedReviews()
            ->with('user')
            ->latest()
            ->paginate(3);

        return view('livewire.forms.video-review', compact('video', 'reviews'));
    }
}
