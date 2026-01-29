<div class="reviews">

    <div class="reviews__text">
        <strong>
            {{ __('Average Rating:') }}
        </strong>
        {{ number_format($video->averageRating(), 1) }}/10
        <span>
            ({{ $video->reviewCount() }} reviews)
        </span>
    </div>

    @if(session()->has('message'))
    <div class="mt-4 alert alert-success">
        {{ session('message') }}
    </div>
    @endif

    <ul class="mt-4 reviews__list">
        @forelse($reviews as $review)
        <li class="reviews__item">
            <div class="reviews__autor">
                <img class="reviews__avatar" src="{{ asset('video/img/user.svg') }}" alt="{{ config('app.name') }}">
                <span class="reviews__name">
                    {{ $review->reviewer_name ?? $review->user->name ?? 'Anonymous' }}
                </span>
                <span class="reviews__time">
                    {{ $review->created_at->diffForHumans() }}
                </span>
                <span class="reviews__rating reviews__rating--{{ $review->reviewer_rating >= 7 ? 'green' : ($review->reviewer_rating >= 4 ? 'yellow' : 'red') }}">
                    {{ $review->reviewer_rating }}
                </span>
            </div>
            <p class="reviews__text">{{ $review->content }}</p>
        </li>
        @empty
        <li class="mb-4 reviews__text">
            {{ __('No reviews yet. Be the first to review this video!') }}
        </li>
        @endforelse
    </ul>

    @include('partials.video-pagination')

    <form wire:submit.prevent="submitReview" class="sign__form sign__form--comments">

        <div class="sign__group">
            <label class="sign__label" for="rating-input">{{ __('Rating') }}</label>

            <div class="sign__stars" role="radiogroup" aria-label="{{ __('Rating') }}">
                <input type="hidden" id="rating-input" wire:model="rating">

                @for ($i = 1; $i <= 10; $i++) <button type="button" wire:click.prevent="$set('rating', {{ $i }})" class="star-button {{ $i <= $rating ? 'filled' : 'empty' }}" aria-label="{{ $i }} {{ $i > 1 ? __('stars') : __('star') }}" aria-pressed="{{ $i <= $rating ? 'true' : 'false' }}" data-rating="{{ $i }}">
                    <i class="ti ti-star-filled"></i>
                    </button>
                    @endfor
            </div>

            @if($rating)
            <span class="sign__rating-text">{{ $rating }} /10</span>
            @endif

            @error('rating')
            <span class="sign__error" role="alert">{{ $message }}</span>
            @enderror
        </div>
        @guest
        <div class="sign__group">
            <input type="text" wire:model.defer="reviewer_name" type="name" class="sign__input" placeholder="Reviewer name">
            @error('reviewer_name') <span class="error">{{ $message }}</span> @enderror
        </div>
        @endguest
        <div class="sign__group">
            <textarea wire:model.defer="content" class="sign__textarea" placeholder="Add review"></textarea>
            @error('content') <span class="error">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="sign__btn sign__btn--small">{{ __('Send') }}</button>
    </form>
</div>
