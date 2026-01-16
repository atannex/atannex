<div>
    <div class="comment-form">
        <h3 class="form-title">
            @if($replyingTo)
            Reply to <span class="text-danger">{{ ucwords(strtolower($replyingTo['username'])) }}</span>
            @else
            {{ __('Leave a Comment') }}
            @endif
        </h3>


        <form wire:submit.prevent="submit">
            @guest
            <div class="form-row">
                <div class="form-group">
                    <input type="text" wire:model.defer="guest_name" placeholder="Your Name*" class="form-input">
                </div>
                <div class="form-group">
                    <input type="email" wire:model.defer="guest_email" placeholder="Your Email*" class="form-input">
                </div>
            </div>
            @endguest

            <input type="text" wire:model.defer="website" class="d-none" tabindex="-1" autocomplete="off">

            <div class="form-group">
                <textarea wire:model="comment" placeholder="Write a comment..." class="form-textarea"></textarea>
                @error('comment')
                <small class="error-message">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-btn">{{ __('Post Comment') }}</button>
                @if($replyingTo)
                <button type="button" wire:click="cancelReply" class="cancel-btn">{{ __('Cancel Reply') }}</button>
                @endif
            </div>
        </form>
    </div>

    <div class="comments-wrap">
        <h2 class="comments-title">{{ __('Comments') }} ({{ $comments->total() }})</h2>

        <ul class="comment-list">
            @forelse ($comments as $comment)
            <li class="comment-item">
                <x-partials.comment :comment="$comment" :shown-replies-count="$shownRepliesCount" />
            </li>
            @empty
            <li class="no-comments">{{ __('No comments yet. Be the first to comment.') }}</li>
            @endforelse
        </ul>

        @if ($comments->hasMorePages())
        <div class="load-more-comments">
            <button wire:click="loadMoreComments" class="load-btn">
                <i class="fas fa-chevron-down"></i> {{ __('Load More') }}
            </button>
        </div>
        @endif
    </div>
</div>
