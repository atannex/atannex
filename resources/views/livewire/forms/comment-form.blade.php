<div class="comments-section">
    @auth
    <div class="comment-form">
        <h3 class="form-title">
            @if($replyingTo)
            Reply to <span class="text-danger">{{ ucwords(strtolower($replyingTo['username'])) }}</span>
            @else
            {{ __('Leave a Comment') }}
            @endif
        </h3>

        <form wire:submit.prevent="submit">
            <div class="form-group">
                <textarea wire:model.defer="comment" placeholder="{{ __('Write a comment...') }}" class="form-textarea @error('comment') border-red-500 @enderror" rows="4" required></textarea>

                @error('comment')
                <small class="text-red-500 error-message">{{ $message }}</small>
                @enderror
            </div>

            <div class="mt-2 form-actions">
                <button type="submit" class="submit-btn">
                    {{ $replyingTo ? __('Post Reply') : __('Post Comment') }}
                </button>

                @if($replyingTo)
                <button type="button" wire:click="cancelReply" class="cancel-btn">
                    {{ __('Cancel') }}
                </button>
                @endif
            </div>
        </form>
    </div>

    @if($comments->total() > 0)
    <div class="mt-4 comments-wrap">
        <h2 class="comments-title">
            {{ __('Comments') }} ({{ $totalComments }})
        </h2>

        <ul class="comment-list">
            @foreach($comments as $comment)
            <li class="comment-item">
                <x-partials.comment :comment="$comment" :shown-replies-count="$shownRepliesCount" :level="0" />
            </li>
            @endforeach
        </ul>

        @if($comments->hasMorePages())
        <div class="mt-3 text-center load-more-comments">
            <button wire:click="loadMoreComments" class="load-btn">
                <i class="fas fa-chevron-down"></i> {{ __('Load More Comments') }}
            </button>
        </div>
        @endif
    </div>
    @endif
    @endauth
</div>
