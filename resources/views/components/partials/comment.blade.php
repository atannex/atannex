@props(['comment', 'shownRepliesCount'])

<div class="post-comment">
    <div class="comment-avatar">
        <img src="{{ asset('logo.jpg') }}" alt="{{ config('app.name') }}">
    </div>

    <div class="comment-content">
        <div class="comment-bubble">
            <h3 class="author-name">{{ ucwords(strtolower($comment->author_name)) }}</h3>

            <p class="comment-text">
                {!! nl2br(
                preg_replace_callback(
                '/^(@[A-Za-z]+(?:\s[A-Za-z]+)?)/',
                function ($matches) {
                return '<span class="text-danger"><i>' . e($matches[1]) . '</i></span>';
                },
                e($comment->comment)
                )
                ) !!}
            </p>
        </div>

        <div class="comment-meta">
            <span class="comment-date">{{ $comment->created_at->format('d F, Y') }}</span>

            <a href="javascript:void(0)" wire:click="$dispatch('reply-to-comment', { commentId: {{ $comment->id }} })" class="meta-link">
                <i class="fas fa-reply"></i> {{ __('Reply') }}
            </a>

            @auth
            <a href="javascript:void(0)" wire:click="$dispatch('like-comment', { commentId: {{ $comment->id }} })" class="meta-link {{ $comment->isLikedBy(auth()->user()) ? 'text-blue-500' : '' }}">
                <i class="fas fa-thumbs-up"></i> {{ $comment->like_count }}
            </a>
            <a href="javascript:void(0)" wire:click="$dispatch('dislike-comment', { commentId: {{ $comment->id }} })" class="meta-link {{ $comment->isDislikedBy(auth()->user()) ? 'text-red-500' : '' }}">
                <i class="fas fa-thumbs-down"></i> {{ $comment->dislike_count }}
            </a>
            @endauth

            @can('delete', $comment)
            <a href="javascript:void(0)" wire:click="$dispatch('delete-comment', { commentId: {{ $comment->id }} })" class="meta-link">
                <i class="fas fa-trash"></i> {{ __('Delete') }}
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

@if($totalReplies > 0)
<ul class="replies-list">
    @foreach($visibleReplies as $reply)
    <li class="comment-item">
        <x-partials.comment :comment="$reply" :shown-replies-count="$shownRepliesCount" />
    </li>
    @endforeach

    @if($shown < $totalReplies) <li class="load-more-replies">
        <a href="javascript:void(0)" wire:click="loadMoreReplies({{ $comment->id }})" class="meta-link">
            <i class="fas fa-chevron-down"></i> {{ $totalReplies }} {{ Str::plural('reply', $totalReplies) }}
        </a>
        </li>
        @elseif($shown > 0)
        <li class="load-more-replies">
            <a href="javascript:void(0)" wire:click="collapseReplies({{ $comment->id }})" class="meta-link">
                <i class="fas fa-chevron-up"></i> {{ __('Hide replies') }}
            </a>
        </li>
        @endif
</ul>
@endif
