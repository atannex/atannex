<div x-data="{}" class="fb-comments-root">

{{-- ════════════════════════════
     SVG ICON MACROS
     Used inline to avoid repeated markup
════════════════════════════ --}}
@php
    $iconLike   = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/><path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>';
    $iconLiked  = '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/><path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>';
    $iconReply  = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 17 4 12 9 7"/><path d="M20 18v-2a4 4 0 0 0-4-4H4"/></svg>';
    $iconCancel = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
    $iconDelete = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>';
@endphp

{{-- ════════════════════════════
     HEADER
════════════════════════════ --}}
<div class="fb-header">
    <h2>{{ __("💬 Comments") }}</h2>
    @if ($totalCount > 0)
        <span class="fb-header-count">{{ number_format($totalCount) }}</span>
    @endif
</div>

{{-- ════════════════════════════
     COMMENT LIST
════════════════════════════ --}}
<ul class="fb-comment-list">
    @forelse ($comments as $comment)
        <li wire:key="comment-{{ $comment['id'] }}" class="fb-entering">

            {{-- ══ Top-level comment ══ --}}
            <div class="fb-comment-row">
                <img src="{{ asset('storage/' . $comment['avatar']) }}" alt="{{ $comment['name'] }}" class="fb-avatar">
                <div class="fb-bubble-wrap">
                    <div class="fb-bubble">
                        <span class="fb-name">{{ $comment['name'] }}</span>
                        <p class="fb-text">{!! $this->renderBody($comment['body']) !!}</p>
                    </div>

                    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                        @if ($comment['likesCount'] > 0)
                            <div class="fb-like-badge">
                                <span class="like-icon">{{ __("👍") }}</span> {{ $comment['likesCount'] }}
                            </div>
                        @endif

                        <div class="fb-reaction-bar">
                            <span class="fb-time">{{ $comment['time'] }}</span>
                            @auth
                                <span class="fb-sep">·</span>

                                {{-- Like --}}
                                <button wire:click="toggleLike({{ $comment['id'] }})"
                                    class="fb-react-btn {{ $comment['isLiked'] ? 'fb-liked' : '' }}"
                                    title="{{ $comment['isLiked'] ? 'Unlike' : 'Like' }}">
                                    {!! $comment['isLiked'] ? $iconLiked : $iconLike !!}
                                    {{ $comment['isLiked'] ? 'Liked' : 'Like' }}
                                </button>

                                <span class="fb-sep">·</span>

                                {{-- Reply / Cancel (only shows Cancel for THIS comment) --}}
                                @if ($replyingTo === $comment['id'])
                                    <button wire:click="startReply({{ $comment['id'] }}, {{ $comment['id'] }})"
                                        class="fb-react-btn fb-cancel" title="Cancel reply">
                                        {!! $iconCancel !!} Cancel
                                    </button>
                                @else
                                    <button wire:click="startReply({{ $comment['id'] }}, {{ $comment['id'] }})"
                                        class="fb-react-btn" title="Reply">
                                        {!! $iconReply !!} Reply
                                    </button>
                                @endif

                                @if ($comment['isOwner'])
                                    <span class="fb-sep">·</span>
                                    <button wire:click="deleteComment({{ $comment['id'] }})"
                                        wire:confirm="Delete this comment?"
                                        class="fb-react-btn fb-danger" title="Delete">
                                        {!! $iconDelete !!}
                                    </button>
                                @endif
                            @endauth
                        </div>
                    </div>

                    {{-- Validation error for new comment --}}
                    @error('newComment') <span class="fb-error">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- ══ View replies toggle for top-level comment ══ --}}
            @if (count($comment['replies']) > 0)
                <div class="fb-view-replies {{ isset($openReplies[$comment['id']]) ? 'open' : '' }}"
                    wire:click="toggleReplies({{ $comment['id'] }})">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                    {{ isset($openReplies[$comment['id']]) ? 'Hide' : 'View' }}
                    {{ count($comment['replies']) }} {{ Str::plural('reply', count($comment['replies'])) }}
                </div>
            @endif

            {{-- ══ Level-1 replies ══ --}}
            @if (isset($openReplies[$comment['id']]))
                <div class="fb-thread-l1">
                    @foreach ($comment['replies'] as $reply)
                        <div wire:key="reply-{{ $reply['id'] }}">

                            <div class="fb-comment-row fb-entering">
                                <img src="{{ asset('storage/' . $reply['avatar']) }}" alt="{{ $reply['name'] }}" class="fb-avatar sm">
                                <div class="fb-bubble-wrap">
                                    <div class="fb-bubble">
                                        <span class="fb-name">{{ $reply['name'] }}</span>
                                        <p class="fb-text">{!! $this->renderBody($reply['body']) !!}</p>
                                    </div>

                                    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                        @if ($reply['likesCount'] > 0)
                                            <div class="fb-like-badge">
                                                <span class="like-icon">{{ __('👍') }}</span> {{ $reply['likesCount'] }}
                                            </div>
                                        @endif

                                        <div class="fb-reaction-bar">
                                            <span class="fb-time">{{ $reply['time'] }}</span>
                                            @auth
                                                <span class="fb-sep">·</span>

                                                {{-- Like --}}
                                                <button wire:click="toggleLike({{ $reply['id'] }})"
                                                    class="fb-react-btn {{ $reply['isLiked'] ? 'fb-liked' : '' }}"
                                                    title="{{ $reply['isLiked'] ? 'Unlike' : 'Like' }}">
                                                    {!! $reply['isLiked'] ? $iconLiked : $iconLike !!}
                                                    {{ $reply['isLiked'] ? 'Liked' : 'Like' }}
                                                </button>

                                                <span class="fb-sep">·</span>

                                                {{-- Reply / Cancel only for THIS reply --}}
                                                @if ($replyingTo === $reply['id'])
                                                    <button
                                                        wire:click="startReply({{ $reply['id'] }}, {{ $reply['rootId'] }}, '{{ addslashes($reply['name']) }}')"
                                                        class="fb-react-btn fb-cancel" title="Cancel reply">
                                                        {!! $iconCancel !!} Cancel
                                                    </button>
                                                @else
                                                    <button
                                                        wire:click="startReply({{ $reply['id'] }}, {{ $reply['rootId'] }}, '{{ addslashes($reply['name']) }}')"
                                                        class="fb-react-btn" title="Reply">
                                                        {!! $iconReply !!} Reply
                                                    </button>
                                                @endif

                                                @if ($reply['isOwner'])
                                                    <span class="fb-sep">·</span>
                                                    <button wire:click="deleteComment({{ $reply['id'] }})"
                                                        wire:confirm="Delete this reply?"
                                                        class="fb-react-btn fb-danger" title="Delete">
                                                        {!! $iconDelete !!}
                                                    </button>
                                                @endif
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- View sub-replies toggle for THIS reply --}}
                            @if (count($reply['subReplies']) > 0)
                                <div class="fb-view-replies {{ isset($openReplies[$reply['id']]) ? 'open' : '' }}"
                                    style="margin-left:40px;"
                                    wire:click="toggleReplies({{ $reply['id'] }})">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"/>
                                    </svg>
                                    {{ isset($openReplies[$reply['id']]) ? 'Hide' : 'View' }}
                                    {{ count($reply['subReplies']) }} {{ Str::plural('reply', count($reply['subReplies'])) }}
                                </div>
                            @endif

                            {{-- Level-2 sub-replies --}}
                            @if (isset($openReplies[$reply['id']]))
                                <div class="fb-thread-l2">
                                    @foreach ($reply['subReplies'] as $sub)
                                        <div wire:key="sub-{{ $sub['id'] }}" class="fb-comment-row fb-entering">
                                            <img src="{{ asset('storage/' . $sub['avatar']) }}" alt="{{ $sub['name'] }}" class="fb-avatar sm">
                                            <div class="fb-bubble-wrap">
                                                <div class="fb-bubble">
                                                    <span class="fb-name">{{ $sub['name'] }}</span>
                                                    <p class="fb-text">{!! $this->renderBody($sub['body']) !!}</p>
                                                </div>

                                                <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                                    @if ($sub['likesCount'] > 0)
                                                        <div class="fb-like-badge">
                                                            <span class="like-icon">{{ __('👍') }}</span> {{ $sub['likesCount'] }}
                                                        </div>
                                                    @endif

                                                    <div class="fb-reaction-bar">
                                                        <span class="fb-time">{{ $sub['time'] }}</span>
                                                        @auth
                                                            <span class="fb-sep">·</span>

                                                            {{-- Like --}}
                                                            <button wire:click="toggleLike({{ $sub['id'] }})"
                                                                class="fb-react-btn {{ $sub['isLiked'] ? 'fb-liked' : '' }}"
                                                                title="{{ $sub['isLiked'] ? 'Unlike' : 'Like' }}">
                                                                {!! $sub['isLiked'] ? $iconLiked : $iconLike !!}
                                                                {{ $sub['isLiked'] ? 'Liked' : 'Like' }}
                                                            </button>

                                                            <span class="fb-sep">·</span>

                                                            {{-- Reply / Cancel — targets the parent reply so sub goes into same l2 group --}}
                                                            @if ($replyingTo === $reply['id'] && $replyMention === $sub['name'])
                                                                <button
                                                                    wire:click="startReply({{ $reply['id'] }}, {{ $sub['rootId'] }}, '{{ addslashes($sub['name']) }}')"
                                                                    class="fb-react-btn fb-cancel" title="Cancel reply">
                                                                    {!! $iconCancel !!} Cancel
                                                                </button>
                                                            @else
                                                                <button
                                                                    wire:click="startReply({{ $reply['id'] }}, {{ $sub['rootId'] }}, '{{ addslashes($sub['name']) }}')"
                                                                    class="fb-react-btn" title="Reply">
                                                                    {!! $iconReply !!} Reply
                                                                </button>
                                                            @endif

                                                            @if ($sub['isOwner'])
                                                                <span class="fb-sep">·</span>
                                                                <button wire:click="deleteComment({{ $sub['id'] }})"
                                                                    wire:confirm="Delete this reply?"
                                                                    class="fb-react-btn fb-danger" title="Delete">
                                                                    {!! $iconDelete !!}
                                                                </button>
                                                            @endif
                                                        @endauth
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Composer for replying to THIS reply --}}
                            @if ($replyingTo === $reply['id'])
                                @auth
                                    <div
                                        x-data="{ text: @js($replyText) }"
                                        x-init="$nextTick(() => { $refs.ri.focus(); const l=$refs.ri.value.length; $refs.ri.setSelectionRange(l,l); })"
                                        class="fb-composer-reply"
                                        style="margin-left:40px;"
                                    >
                                        <img src="{{ asset('storage/' . auth()->user()->image) ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=2d88ff&color=fff&bold=true' }}"
                                            alt="You" class="fb-avatar sm">
                                        <div class="fb-input-box">
                                            <textarea x-ref="ri" wire:model.live="replyText" x-model="text"
                                                placeholder="Write a reply…" rows="1"
                                                x-on:input="$el.style.height='auto';$el.style.height=$el.scrollHeight+'px'"
                                                x-on:keydown.enter.prevent="if(text.trim()){$wire.postReply();text='';}">
                                            </textarea>
                                            <div class="fb-input-actions">
                                                <button class="fb-emoji-btn" type="button" tabindex="-1">{{ __('😊') }}</button>
                                                <button type="button" class="fb-send-btn" :class="{visible:text.trim().length>0}"
                                                    x-on:click="$wire.postReply();text=''">
                                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @error('replyText') <span class="fb-error" style="margin-left:40px;">{{ $message }}</span> @enderror
                                @endauth
                            @endif

                        </div>
                    @endforeach
                </div>
            @endif

            {{-- ══ Composer for replying to the top-level comment ══ --}}
            @if ($replyingTo === $comment['id'])
                @auth
                    <div
                        x-data="{ text: @js($replyText) }"
                        x-init="$nextTick(() => { $refs.ri.focus(); const l=$refs.ri.value.length; $refs.ri.setSelectionRange(l,l); })"
                        class="fb-composer-reply indent-l1"
                    >
                        <img src="{{ asset('storage/' . auth()->user()->image) ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=2d88ff&color=fff&bold=true' }}"
                            alt="You" class="fb-avatar sm">
                        <div class="fb-input-box">
                            <textarea x-ref="ri" wire:model.live="replyText" x-model="text"
                                placeholder="Write a reply…" rows="1"
                                x-on:input="$el.style.height='auto';$el.style.height=$el.scrollHeight+'px'"
                                x-on:keydown.enter.prevent="if(text.trim()){$wire.postReply();text='';}">
                            </textarea>
                            <div class="fb-input-actions">
                                <button class="fb-emoji-btn" type="button" tabindex="-1">😊</button>
                                <button type="button" class="fb-send-btn" :class="{visible:text.trim().length>0}"
                                    x-on:click="$wire.postReply();text=''">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('replyText') <span class="fb-error" style="margin-left:46px;">{{ $message }}</span> @enderror
                @endauth
            @endif

        </li>
    @empty
        <li>
            <div class="fb-empty">
                <div class="fb-empty-icon">{{ __("💭") }}</div>
                <strong>{{ __("No comments yet") }}</strong>
                <span>{{ __("Be the first to share your thoughts!") }}</span>
            </div>
        </li>
    @endforelse
</ul>

{{-- Load more --}}
@if ($hasMore)
    <div class="fb-load-more">
        <button wire:click="loadMore" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="loadMore">{{ __("View more comments") }}</span>
            <span wire:loading wire:target="loadMore">{{ __("Loading…") }}</span>
        </button>
    </div>
@endif

{{-- Main composer --}}
@auth
    <div x-data="{ text: '' }" class="fb-composer">
        <img src="{{ asset('storage/' . auth()->user()->image) ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=2d88ff&color=fff&bold=true' }}"
            alt="{{ auth()->user()->name }}" class="fb-avatar">
        <div class="fb-input-box">
            <textarea wire:model.live="newComment" x-model="text"
                placeholder="Write a comment…" rows="1"
                x-on:input="$el.style.height='auto';$el.style.height=$el.scrollHeight+'px'"
                x-on:keydown.enter.prevent="if(text.trim()){$wire.postComment();text='';}">
            </textarea>
            <div class="fb-input-actions">
                <button class="fb-emoji-btn" type="button" tabindex="-1">{{ __("😊") }}</button>
                <button type="button" class="fb-send-btn" :class="{visible:text.trim().length>0}"
                    x-on:click="$wire.postComment();text=''">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @error('newComment') <div class="fb-error" style="padding:4px 20px 8px;background:var(--fb-surface2);">{{ $message }}</div> @enderror
@else
    <div class="fb-guest-prompt">
        <span>{{ __("to join the conversation.") }}</span>
    </div>
@endauth

</div>
