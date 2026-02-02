<div class="gap-3 post-comment d-flex align-items-start
        {{ (($level ?? 0) >= 3) ? 'flattened-reply' : '' }}" data-level="{{ $level ?? 0 }}">

    {{-- Avatar --}}
    <div class="flex-shrink-0 comment-avatar">
        <img src="{{ asset('logo.jpg') }}" alt="{{ config('app.name', 'Site') }}" class="rounded-circle" width="45" height="45">
    </div>

    <div class="comment-content flex-grow-1">

        {{-- Reply indicator (flattened only) --}}
        @if (($level ?? 0) >= 3 && $comment->parent)
        <div class="mb-2 reply-indicator small text-muted">
            <i class="fas fa-reply"></i>
            Replying to
            <strong>
                {{ ucwords(strtolower($comment->parent->author_name)) }}
            </strong>
        </div>
        @endif

        {{-- Comment bubble --}}
        <div class="p-3 rounded comment-bubble">

            <div class="flex-wrap mb-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold author-name">
                    {{ ucwords(strtolower($comment->author_name)) }}
                </h6>

                {{-- Actions --}}
                <div class="gap-3 d-flex comment-actions small">

                    {{-- Like --}}
                    <a href="javascript:void(0)" wire:click="toggleLike({{ $comment->id }})" wire:loading.attr="disabled" class="meta-link text-success
                       {{ $comment->isLikedBy(auth()->user()) ? 'fw-bold text-primary' : '' }}">
                        <i class="fas fa-thumbs-up"></i>
                        <span>{{ $comment->like_count ?? 0 }}</span>
                    </a>

                    {{-- Dislike --}}
                    <a href="javascript:void(0)" wire:click="toggleDislike({{ $comment->id }})" wire:loading.attr="disabled" class="meta-link text-info
                       {{ $comment->isDislikedBy(auth()->user()) ? 'fw-bold text-danger' : '' }}">
                        <i class="fas fa-thumbs-down"></i>
                        <span>{{ $comment->dislike_count ?? 0 }}</span>
                    </a>

                </div>
            </div>

            {{-- Comment text --}}
            <p class="mb-0 comment-text">
                {!! nl2br(
                preg_replace_callback(
                '/^(@[A-Za-z0-9_]+(?:\s[A-Za-z0-9_]+)?)/',
                fn ($m) =>
                '<span class="text-danger fst-italic">' . e($m[1]) . '</span>',
                e($comment->comment)
                )
                ) !!}
            </p>
        </div>

        {{-- Meta --}}
        <div class="flex-wrap gap-3 mt-2 d-flex comment-meta small text-muted">
            <span>{{ $comment->created_at->format('d F Y') }}</span>

            <a wire:click="$dispatch('reply-to-comment', { commentId: {{ $comment->id }} })" class="meta-link text-primary">
                <i class="fas fa-reply"></i>
            </a>

            @can('delete', $comment)
            <a wire:click="$dispatch('delete-comment', { commentId: {{ $comment->id }} })" class="meta-link text-danger">
                <i class="fas fa-trash"></i>
            </a>
            @endcan
        </div>

    </div>
</div>

{{-- ===================== REPLIES ===================== --}}
@if ($comment->replies->isNotEmpty())

<ul class="replies-list fb-replies
    {{ (($level ?? 0) >= 3) ? 'no-indent' : '' }}">

    {{-- Render replies --}}
    @foreach ($comment->replies->take($shownRepliesCount[$comment->id] ?? 0) as $reply)

    <li class="fb-reply-item">

        @if (($level ?? 0) < 3) <div class="fb-reply-connector">
            </div>
            @endif

            <x-partials.comment :comment="$reply" :shown-replies-count="$shownRepliesCount" :level="(($level ?? 0) < 3) ? ($level + 1) : 3" />
    </li>

    @endforeach

    {{-- Load More --}}
    @if (
    $comment->replies->count()
    - ($shownRepliesCount[$comment->id] ?? 0) > 0
    )
    <li class="fb-replies-action">
        <a wire:click="loadMoreReplies({{ $comment->id }})" class="fb-replies-link">
            <i class="fas fa-chevron-down"></i>
            {{ __("Load More") }}
            ({{ $comment->replies->count() - ($shownRepliesCount[$comment->id] ?? 0) }})
        </a>
    </li>
    @endif

    {{-- Hide Replies (parent only) --}}
    @if (
    ($level ?? 0) === 0
    && ($shownRepliesCount[$comment->id] ?? 0) > 0
    )
    <li class="fb-replies-action">
        <a wire:click="collapseReplies({{ $comment->id }})" class="fb-replies-link">
            <i class="fas fa-chevron-up"></i>
            {{ __('Hide replies') }}
        </a>
    </li>
    @endif

</ul>
@endif
