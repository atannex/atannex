<div>
    <div class="comments-wrap">
        <h2 class="comments-title">
            Comments ({{ $comments->total() }})
        </h2>
    </div>

    <div class="comment-form">
        <h3 class="form-title">
            {{ ucwords(strtolower($replyingTo ? 'Reply to ' . $replyingTo['username'] : __('Leave a Comment'))) }}
        </h3>

        <form wire:submit="submit">
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
                <button type="submit" class="submit-btn">
                    {{ __("Post Comment") }}
                </button>

                @if ($replyingTo)
                <button type="button" wire:click="cancelReply" class="cancel-btn">
                    {{ __("Cancel Reply") }}
                </button>
                @endif
            </div>
        </form>
    </div>

    <div class="comments-wrap">
        <ul class="comment-list">
            @forelse ($comments as $comment)
            <li class="comment-item">
                <div class="post-comment">
                    <div class="comment-avatar">
                        <img src="{{ asset('logo.jpg') }}" alt="Comment Author">
                    </div>

                    <div class="comment-content">
                        <div class="comment-bubble">
                            <h3 class="author-name">
                                {{ ucwords(strtolower($comment->author_name)) }}
                            </h3>

                            <p class="comment-text">
                                {!! nl2br(e($comment->comment)) !!}
                            </p>
                        </div>

                        <div class="comment-meta">
                            <span class="comment-date">
                                {{ $comment->created_at->format('d F, Y') }}
                            </span>

                            <a href="javascript:void(0)" wire:click="$dispatch('reply-to-comment', { commentId: {{ $comment->id }} })" class="meta-link">
                                <i class="fas fa-reply"></i> {{ __("Reply") }}
                            </a>

                            @can('delete', $comment)
                            <a href="javascript:void(0)" wire:click="$dispatch('delete-comment', { commentId: {{ $comment->id }} })" class="meta-link">
                                <i class="fas fa-trash"></i> {{ __("Delete") }}
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                @php
                $shown = $shownRepliesCount[$comment->id] ?? 0;
                $totalReplies = $comment->replies->count();
                $visibleReplies = $comment->replies->take($shown);
                @endphp

                @if ($totalReplies > 0)
                <ul class="replies-list">
                    @foreach ($visibleReplies as $reply)
                    <li class="comment-item">
                        <div class="post-comment">
                            <div class="comment-avatar">
                                <img src="{{ asset('logo.jpg') }}" alt="Reply Author">
                            </div>

                            <div class="comment-content">
                                <div class="comment-bubble">
                                    <h3 class="author-name">
                                        {{ ucwords(strtolower($reply->author_name)) }}
                                    </h3>

                                    <p class="comment-text">
                                        {!! nl2br(e($reply->comment)) !!}
                                    </p>
                                </div>

                                <div class="comment-meta">
                                    <span class="comment-date">
                                        {{ $reply->created_at->format('d F, Y') }}
                                    </span>

                                    <a href="javascript:void(0)" wire:click="$dispatch('reply-to-comment', { commentId: {{ $reply->id }} })" class="meta-link">
                                        <i class="fas fa-reply"></i> {{ __('Reply') }}
                                    </a>

                                    @can('delete', $reply)
                                    <a href="javascript:void(0)" wire:click="$dispatch('delete-comment', { commentId: {{ $reply->id }} })" class="meta-link">
                                        <i class="fas fa-trash"></i> {{ __("Delete") }}
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach

                    @if ($shown < $totalReplies) <li class="load-more-replies">
                        <a href="javascript:void(0)" wire:click="loadMoreReplies({{ $comment->id }})" class="meta-link">
                            <i class="fas fa-chevron-down"></i>
                            {{ $totalReplies }} {{ Str::plural('reply', $totalReplies) }}
                        </a>
            </li>
            @elseif ($shown > 0)
            <li class="load-more-replies">
                <a href="javascript:void(0)" wire:click="collapseReplies({{ $comment->id }})" class="meta-link">
                    <i class="fas fa-chevron-up"></i>
                    {{ Str::plural('Hide reply', $totalReplies) }}

                </a>
            </li>
            @endif
        </ul>
        @endif
        </li>
        @empty
        <li class="no-comments">
            {{ __('No comments yet. Be the first to comment.') }}
        </li>
        @endforelse
        </ul>

        @if ($comments->hasMorePages())
        <div class="load-more-comments">
            <button wire:click="loadMoreComments" class="load-btn">
                <i class="fas fa-chevron-down"></i> {{ __("Load More") }}
            </button>
        </div>
        @endif
    </div>
</div>
