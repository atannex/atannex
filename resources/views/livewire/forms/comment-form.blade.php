<div>
    <div class="th-comments-wrap">
        <h2 class="blog-inner-title h3">
            Comments ({{ $comments->total() }})
        </h2>

        <ul class="comment-list">
            @forelse ($comments as $comment)
            <li class="th-comment-item">


                <div class="th-post-comment">
                    <div class="comment-avater">
                        <img src="{{ asset('assets/img/comment.jpg') }}" alt="Comment Author">
                    </div>

                    <div class="comment-content">
                        <span class="commented-on">
                            <i class="fas fa-calendar-alt"></i>
                            {{ $comment->created_at->format('d F, Y') }}
                        </span>

                        <h3 class="name">
                            {{ $comment->author_name }}
                        </h3>

                        <p class="text">
                            {{ $comment->comment }}
                        </p>

                        <div class="reply_and_edit">
                            <a href="javascript:void(0)" wire:click="$dispatch('reply-to-comment', { commentId: {{ $comment->id }} })" class="reply-btn">
                                <i class="fas fa-reply"></i> {{ __("Reply") }}
                            </a>

                            @can('delete', $comment)
                            <a href="javascript:void(0)" wire:click="$dispatch('delete-comment', { commentId: {{ $comment->id }} })" class="reply-btn text-danger">
                                Delete
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                @php
                $shown = $shownRepliesCount[$comment->id] ?? 0;
                $totalReplies = $comment->replies->count();
                @endphp

                @if ($totalReplies > 0)
                <ul class="mb-4 children">
                    @foreach ($comment->replies->take($shown) as $reply)
                    <li class="th-comment-item">
                        <div class="th-post-comment">
                            <div class="comment-avater">
                                <img src="{{ asset('assets/img/comment.jpg') }}" alt="Reply Author">
                            </div>

                            <div class="comment-content">
                                <span class="commented-on">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $reply->created_at->format('d F, Y') }}
                                </span>

                                <h3 class="name">
                                    {{ $reply->author_name }}
                                </h3>

                                <p class="text">
                                    {{ $reply->comment }}
                                </p>

                                <div class="reply_and_edit">
                                    <a href="javascript:void(0)" wire:click="$dispatch('reply-to-comment', { commentId: {{ $reply->id }} })" class="reply-btn">
                                        <i class="fas fa-reply"></i> {{ __('Reply') }}
                                    </a>

                                    @can('delete', $reply)
                                    <a href="javascript:void(0)" wire:click="$dispatch('delete-comment', { commentId: {{ $reply->id }} })" class="reply-btn text-danger">
                                        Delete
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach

                    @if ($shown < $totalReplies) <li class="mt-2">
                        <a href="javascript:void(0)" wire:click="loadMoreReplies({{ $comment->id }})" class="reply-btn">
                            {{ __(' Load more replies') }}
                        </a>
            </li>
            @elseif ($shown > 0)
            <li class="mt-2">
                <a href="javascript:void(0)" wire:click="collapseReplies({{ $comment->id }})" class="reply-btn text-muted">
                    {{ __("Collapse replies") }}
                </a>
            </li>
            @endif
        </ul>
        @endif
        </li>
        @empty
        <li class="text-muted">
            <strong>
                {{ __('No comments yet. Be the first to comment.') }}</strong>
        </li>
        @endforelse
        </ul>

        @if ($comments->hasMorePages())
        <div class="mt-4">
            <button wire:click="loadMoreComments" class="th-btn">
                {{ __("Load more comments") }}
            </button>
        </div>
        @endif
    </div>

    <div class="mt-5 th-comment-form">
        <div class="form-title">
            <h3 class="mb-2 blog-inner-title">
                {{ $replyingTo ? 'Reply to ' . $replyingTo['username'] : __(' Leave a Comment ') }}
            </h3>

            <p class="form-text">
                {{ __("Your email address will not be published.") }}
            </p>
        </div>

        <form wire:submit="submit">
            <div class="row">
                @guest
                <div class="col-md-6 form-group">
                    <input type="text" wire:model.defer="guest_name" placeholder="Your Name*" class="form-control">
                    <i class="far fa-user"></i>
                </div>

                <div class="col-md-6 form-group">
                    <input type="email" wire:model.defer="guest_email" placeholder="Your Email*" class="form-control">
                    <i class="far fa-envelope"></i>
                </div>
                @endguest

                <input type="text" wire:model.defer="website" class="d-none" tabindex="-1" autocomplete="off">

                <div class="col-12 form-group">
                    <textarea wire:model="comment" placeholder="Write a Comment*" class="form-control" rows="5"></textarea>
                    <i class="far fa-pencil"></i>

                    @error('comment')
                    <small class="mt-1 text-danger d-block">{{ $message }}</small>
                    @enderror
                </div>

                <div class="flex-wrap gap-2 mb-0 col-12 form-group d-flex">
                    <button type="submit" class="th-btn">
                        {{ __(" Post Comment") }}
                    </button>

                    @if ($replyingTo)
                    <button type="button" wire:click="cancelReply" class="th-btn th-btn-sm th-btn-outline ms-2">
                        {{ __("Cancel Reply") }}
                    </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
