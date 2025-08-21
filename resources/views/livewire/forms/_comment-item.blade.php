<li class="th-comment-item {{ $comment->parent_id ? 'th-comment-reply' : '' }} mb-4">
    <div class="th-post-comment d-flex">
        <div class="comment-avater">
            <img src="{{ $comment->user->image ? asset('storage/' . $comment->user->image) : asset('assets/img/user_comment_img.jpg') }}" alt="{{ $comment->user->name }}" class="rounded-circle" width="40" height="40" loading="lazy">
        </div>

        <div class="comment-content flex-grow-1">
            <div class="comment-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="name d-inline">{{ $comment->user->name }}</h3>
                    <span class="commented-on ms-2 text-muted">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $comment->created_at->diffForHumans() }}
                    </span>
                </div>

                @auth
                @if(auth()->id() === $comment->user_id)
                <div class="gap-2 comment-actions d-flex align-items-center">
                    <a wire:click="$dispatch('edit-comment', { commentId: {{ $comment->id }} })" class="text-muted" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a wire:click="$dispatch('reply-to-comment', { commentId: {{ $comment->id }}, username: '{{ $comment->user->name }}' })" class="text-primary" title="Reply">
                        <i class="fas fa-reply"></i>
                    </a>
                    <a wire:click="$dispatch('delete-comment', { commentId: {{ $comment->id }} })" class="text-danger" title="Delete" onclick="return confirm('{{ __('Are you sure you want to delete this comment?') }}')">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
                @endif
                @endauth
            </div>

            <p class="mt-2 text">
                @if($comment->parent_id && optional($comment->parent->user)->name)
                <span class="text-primary">@ {{ $comment->parent->user->name }}</span>,
                @endif
                {!! $comment->comment !!}
            </p>
        </div>
    </div>

    @if(!$comment->parent_id && $comment->replies->count())
    @php $shown = $shownRepliesCount[$comment->id] ?? 0; @endphp

    @if($shown > 0)
    <ul class="mt-2 children ps-4">
        @foreach($comment->replies->sortBy('created_at')->take($shown) as $reply)
        <li class="mb-4 th-comment-item th-comment-reply">
            <div class="th-post-comment d-flex">
                <div class="comment-avater">
                    <img src="{{ $reply->user->image ? asset('storage/' . $reply->user->image) : asset('assets/img/user_comment_img.jpg') }}" alt="{{ $reply->user->name }}" class="rounded-circle" width="40" height="40" loading="lazy">
                </div>
                <div class="comment-content flex-grow-1">
                    <div class="comment-header d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="name d-inline">{{ $reply->user->name }}</h3>
                            <span class="commented-on ms-2 text-muted">
                                <i class="fas fa-calendar-alt"></i>
                                {{ $reply->created_at->diffForHumans() }}
                            </span>
                        </div>
                        @auth
                        @if(auth()->id() === $reply->user_id)
                        <div class="gap-2 comment-actions d-flex align-items-center">
                            <a wire:click="$dispatch('edit-comment', { commentId: {{ $reply->id }} })" class="text-muted" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a wire:click="$dispatch('reply-to-comment', { commentId: {{ $reply->id }}, username: '{{ $reply->user->name }}' })" class="text-primary" title="Reply">
                                <i class="fas fa-reply"></i>
                            </a>
                            <a wire:click="$dispatch('delete-comment', { commentId: {{ $reply->id }} })" class="text-danger" title="Delete" onclick="return confirm('{{ __('Are you sure you want to delete this comment?') }}')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                        @endif
                        @endauth
                    </div>
                    <p class="mt-2 text">
                        @if($reply->parent_id && optional($reply->parent->user)->name)
                        <span class="text-primary">@ {{ $reply->parent->user->name }}</span>,
                        @endif
                        {!! $reply->comment !!}
                    </p>
                </div>
            </div>
        </li>
        @endforeach

        @if($shown < $comment->replies->count())
            <div class="reply_and_edit">
                <a type="button" wire:click="loadMoreReplies({{ $comment->id }})" class="reply-btn">
                    <i class="fas fa-reply"></i>
                    {{ Str::plural('Load More Reply', $comment->replies->count()) }}
                </a>
            </div>
            @endif
    </ul>

    <div class="reply_and_edit">
        <a type="button" wire:click="collapseReplies({{ $comment->id }})" class="reply-btn">
            <i class="fas fa-reply"></i>
            {{ Str::plural('Hide Reply', $comment->replies->count()) }}
        </a>
    </div>
    @else
    <div class="reply_and_edit">
        <a type="button" wire:click="loadMoreReplies({{ $comment->id }})" class="reply-btn">
            <i class="fas fa-reply"></i>
            {{ $comment->replies->count() }} {{ Str::plural('Reply', $comment->replies->count()) }}
        </a>
    </div>
    @endif
    @endif
</li>
