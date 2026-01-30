@php
$maxNestLevel = 4;
$currentLevel = $level ?? 0;
$shouldNest = $currentLevel < $maxNestLevel; $isFlattened=!$shouldNest; $nestClass=$isFlattened ? 'flattened-reply' : '' ; @endphp <div class="gap-3 post-comment d-flex align-items-start {{ $nestClass }}" data-level="{{ $currentLevel }}">
    <div class="flex-shrink-0 comment-avatar">
        <img src="{{ asset('logo.jpg') }}" alt="{{ config('app.name', 'Site') }}" class="rounded-circle" width="45" height="45">
    </div>

    <div class="comment-content flex-grow-1">
        @if($isFlattened && $comment->parent)
        <div class="mb-2 reply-indicator small text-muted">
            <i class="fas fa-reply"></i>
            Replying to <strong>{{ ucwords(strtolower($comment->parent->author_name)) }}</strong>
        </div>
        @endif

        <div class="p-3 rounded comment-bubble">
            <div class="flex-wrap mb-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 author-name fw-bold">{{ ucwords(strtolower($comment->author_name)) }}</h6>
                <div class="gap-3 d-flex comment-actions small">
                    <a type="button" wire:click="like({{ $comment->id }})" class="meta-link text-success {{ $comment->isLikedBy(auth()->user()) ? 'fw-bold text-primary' : '' }}">
                        <i class="fas fa-thumbs-up"></i>
                        <span>{{ $comment->like_count }}</span>
                    </a>
                    <a type="button" wire:click="dislike({{ $comment->id }})" class="meta-link text-info {{ $comment->isDislikedBy(auth()->user()) ? 'fw-bold text-danger' : '' }}">
                        <i class="fas fa-thumbs-down"></i>
                        <span>{{ $comment->dislike_count }}</span>
                    </a>
                </div>
            </div>

            <p class="mb-0 comment-text">
                {!! nl2br(preg_replace_callback(
                '/^(@[A-Za-z0-9_]+(?:\s[A-Za-z0-9_]+)?)/',
                fn($m) => '<span class="text-danger fst-italic">' . e($m[1]) . '</span>',
                e($comment->comment)
                )) !!}
            </p>
        </div>

        <div class="flex-wrap gap-3 mt-2 d-flex comment-meta small text-muted">
            <span>{{ $comment->created_at->format('d F Y') }}</span>
            <a href="javascript:void(0)" wire:click="$dispatch('reply-to-comment', { commentId: {{ $comment->id }} })" class="meta-link text-primary">
                <i class="fas fa-reply"></i>
            </a>
            @can('delete', $comment)
            <a href="javascript:void(0)" wire:click="$dispatch('delete-comment', { commentId: {{ $comment->id }} })" class="meta-link text-danger">
                <i class="fas fa-trash"></i>
            </a>
            @endcan
        </div>
    </div>
    </div>

    @if($comment->replies->isNotEmpty())
    @php
    $nextLevel = $shouldNest ? $currentLevel + 1 : $maxNestLevel;
    $shownCount = $shownRepliesCount[$comment->id] ?? 0;
    $totalReplies = $comment->replies->count();
    $remainingReplies = $totalReplies - $shownCount;
    @endphp

    <ul class="replies-list fb-replies {{ $isFlattened ? 'no-indent' : '' }}">
        @foreach($comment->replies->take($shownCount) as $reply)
        <li class="fb-reply-item">
            @if($shouldNest)
            <div class="fb-reply-connector"></div>
            @endif
            <x-partials.comment :comment="$reply" :shown-replies-count="$shownRepliesCount" :level="$nextLevel" />
        </li>
        @endforeach

        @if($remainingReplies > 0)
        <li class="fb-replies-action">
            <a href="javascript:void(0)" wire:click="loadMoreReplies({{ $comment->id }})" class="fb-replies-link">
                <i class="fas fa-chevron-down"></i> {{ __('Replies') }} ({{ $remainingReplies }})
            </a>
        </li>
        @elseif($shownCount > 0)
        <li class="fb-replies-action">
            <a href="javascript:void(0)" wire:click="collapseReplies({{ $comment->id }})" class="fb-replies-link">
                <i class="fas fa-chevron-up"></i> {{ __('Hide replies') }}
            </a>
        </li>
        @endif
    </ul>
    @endif
