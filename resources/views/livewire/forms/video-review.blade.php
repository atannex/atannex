<div class="reviews">

    <div class="mb-4 reviews__text">
        <strong>
            {{ __('Average Rating:') }}
        </strong>
        {{ number_format($video->averageRating(), 1) }}/5
        <span>
            ({{ $video->reviewCount() }} {{ __('reviews') }})
        </span>
    </div>

    @if(session()->has('success'))
    <div class="mt-4 alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form wire:submit.prevent="{{ $editingReviewId ? 'updateReview' : 'submitReview' }}" class="sign__form sign__form--comments">

        <div class="sign__group">
            <label class="sign__label">{{ __('Rating') }}</label>
            <div class="sign__stars" role="radiogroup">
                <input type="hidden" wire:model="rating">
                @for ($i = 1; $i <= 5; $i++) <button type="button" wire:click="$set('rating', {{ $i }})" class="star-button {{ $i <= $rating ? 'filled' : 'empty' }}" aria-label="{{ $i }} {{ __('stars') }}" aria-pressed="{{ $i <= $rating ? 'true' : 'false' }}">
                    <i class="ti ti-star-filled"></i>
                    </button>
                    @endfor
            </div>
            @if($rating)
            <span class="sign__rating-text">{{ $rating }}</span>
            @endif
            @error('rating') <span class="sign__error">{{ $message }}</span> @enderror
        </div>

        @guest
        <div class="sign__group">
            <input type="text" wire:model.defer="reviewer_name" class="sign__input" placeholder="{{ __('Your name (optional)') }}">
            @error('reviewer_name') <span class="sign__error">{{ $message }}</span> @enderror
        </div>
        @endguest

        <div class="sign__group">
            <textarea wire:model.defer="content" class="sign__textarea" placeholder="{{ __('Write your review…') }}"></textarea>
            @error('content') <span class="sign__error">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="sign__btn sign__btn--small">
            {{ $editingReviewId ? __('Update Review') : __('Send Review') }}
        </button>

    </form>

    <ul class="mt-4 reviews__list">
        @forelse ($reviews as $review)
        <li class="reviews__item">
            <div class="reviews__autor">
                <img class="reviews__avatar" src="{{ asset('video/img/user.svg') }}" alt="{{ config('app.name') }}">
                <span class="reviews__name">
                    {{ $review->reviewer_name ?? $review->user->name ?? __('Anonymous') }}
                </span>
                <span class="reviews__time">
                    {{ $review->created_at->diffForHumans() }}
                </span>
                <span class="reviews__rating reviews__rating--{{ $review->reviewer_rating >= 4 ? 'green' : ($review->reviewer_rating >= 3 ? 'yellow' : 'red') }}">
                    {{ $review->reviewer_rating }}/5
                </span>
            </div>

            <p class="reviews__text">
                {{ $review->content }}
            </p>

            <div class="comments__actions">
                <div class="comments__rate">
                    <button type="button">
                        <i class="ti ti-thumb-up"></i>
                        12
                    </button>
                    <button type="button">
                        7
                        <i class="ti ti-thumb-down"></i>
                    </button>
                </div>

                @can('update', $review)
                <button type="button" wire:click="editReview({{ $review->id }})">
                    <i class="ti ti-receipt"></i> {{ __('Edit') }}
                </button>
                @endcan

                @can('delete', $review)
                <button type="button" wire:click="deleteReview({{ $review->id }})">
                    <i class="ti ti-trash"></i> {{ __('Delete') }}
                </button>
                @endcan
            </div>
        </li>
        @empty
        <li class="mb-4 reviews__text">
            {{ __('No reviews yet. Be the first to review this video!') }}
        </li>
        @endforelse
    </ul>

    @include('partials.video-pagination')
</div>
