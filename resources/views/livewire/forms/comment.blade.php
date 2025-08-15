<div>
    <div class="th-comments-wrap">
        <h2 class="blog-inner-title h3">
            {{ __('Comments') }} ({{ $comments->total() }})
        </h2>

        @if($comments->count())
        <ul class="comment-list">
            @foreach($comments as $comment)
            @if(!$comment->parent_id)
            @include('livewire.forms._comment-item', ['comment' => $comment, 'shownRepliesCount' => $shownRepliesCount])
            @endif
            @endforeach
        </ul>

        @if($comments->hasMorePages())
        <div class="mt-3 text-center">
            <button wire:click="loadMoreComments" wire:loading.attr="disabled" class="th-btn btn btn-outline-primary">
                <span wire:loading.class="d-none">{{ __('Load More Comments') }}</span>
                <span wire:loading>{{ __('Loading...') }}</span>
            </button>
        </div>
        @endif
        @else
        <p class="text-muted">{{ __('No comments yet. Be the first to comment!') }}</p>
        @endif
    </div>

    @auth
    <div class="mt-5 th-comment-form">
        <div class="form-title">
            <h3 class="mb-2 blog-inner-title">{{ __('Leave a Comment') }}</h3>
            <p class="form-text">
                {{ __('Your email address will not be published. Required fields are marked *') }}
            </p>
        </div>

        <form wire:submit="submit">
            <div class="mb-3 form-group">
                <textarea wire:model.debounce.500ms="comment" placeholder="{{ __('Write a Comment*') }}" class="form-control" rows="4" required></textarea>
                @error('comment')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            @if($replyingTo)
            <div class="mb-3">
                <span class="text-muted">
                    {{ __('Replying to') }} {{ $replyingTo['username'] }}
                    <button type="button" wire:click="cancelReply" class="btn btn-sm btn-link text-danger">
                        {{ __('Cancel') }}
                    </button>
                </span>
            </div>
            @endif

            <div class="form-group">
                <button type="submit" wire:loading.attr="disabled" class="th-btn btn btn-primary">
                    <span wire:loading.class="d-none">
                        {{ $replyingTo ? __('Post Reply') : __('Post Comment') }}
                    </span>
                    <span wire:loading>{{ __('Posting...') }}</span>
                </button>
            </div>
        </form>
    </div>
    @else
    <div class="mt-5 th-comment-form">
        <p class="text-muted">
            {{ __('Please') }}
            <a href="{{ route('login') }}">{{ __('log in') }}</a>
            {{ __('to leave a comment.') }}
        </p>
    </div>
    @endauth
</div>
