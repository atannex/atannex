<div x-data="{}" class="fb-comments-root">

<style>
    .fb-comments-root {
        --fb-bg:        #18191a;
        --fb-surface:   #161616;
        --fb-surface:   #161616;
        --fb-bubble:    #3a3b3c;
        --fb-bubble-h:  #4e4f50;
        --fb-primary:   #2d88ff;
        --fb-primary-h: #4d9fff;
        --fb-text:      #e4e6eb;
        --fb-sub:       #b0b3b8;
        --fb-border:    #3e4042;
        --fb-danger:    #f56565;
        --fb-mention:   #4d9fff;
        --fb-radius:    20px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
        background: var(--fb-bg);
        border-radius: 14px;
        border: 1px solid var(--fb-border);
        overflow: hidden;
        width: 100%;
    }

    /* Header */
    .fb-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px 14px;
        background: var(--fb-surface);
        border-bottom: 1px solid var(--fb-border);
    }
    .fb-header h2 { font-size: 15px; font-weight: 700; color: var(--fb-text); margin: 0; }
    .fb-header-count {
        font-size: 13px; font-weight: 500; color: var(--fb-sub);
        background: var(--fb-bubble); padding: 2px 10px;
        border-radius: 20px; border: 1px solid var(--fb-border);
    }

    /* Comment list */
    .fb-comment-list {
        background: var(--fb-surface);
        padding: 16px 20px 8px;
        display: flex; flex-direction: column; gap: 20px;
        list-style: none; margin: 0;
    }

    /* Comment row */
    .fb-comment-row { display: flex; align-items: flex-start; gap: 10px; }

    /* Avatar */
    .fb-avatar {
        width: 36px; height: 36px;
        border-radius: 50%; object-fit: cover; flex-shrink: 0;
        border: 2px solid var(--fb-border);
    }
    .fb-avatar.sm { width: 30px; height: 30px; }

    /* Bubble */
    .fb-bubble-wrap { display: flex; flex-direction: column; flex: 1; min-width: 0; }
    .fb-bubble {
        background: var(--fb-bubble);
        border-radius: 4px var(--fb-radius) var(--fb-radius) var(--fb-radius);
        padding: 10px 14px; display: inline-block; max-width: 100%;
        transition: background .15s; border: 1px solid transparent;
    }
    .fb-bubble:hover { background: var(--fb-bubble-h); border-color: var(--fb-border); }
    .fb-bubble .fb-name {
        font-size: 13px; font-weight: 700; color: var(--fb-text);
        margin: 0 0 4px; display: block;
    }
    .fb-bubble .fb-name:hover { color: var(--fb-primary); cursor: pointer; }
    .fb-bubble .fb-text {
        font-size: 14px; color: var(--fb-text); line-height: 1.5; margin: 0; word-break: break-word;
    }
    .fb-bubble .fb-text .fb-at { color: var(--fb-mention); font-weight: 600; }

    /* ── Reaction bar ─────────────────────────────── */
    .fb-reaction-bar {
        display: flex; align-items: center; gap: 2px;
        margin-top: 6px; padding-left: 2px; flex-wrap: wrap;
    }
    .fb-reaction-bar .fb-time {
        font-size: 11px; color: var(--fb-sub); padding: 3px 6px; cursor: default;
    }
    .fb-reaction-bar .fb-sep {
        color: var(--fb-border); font-size: 12px; line-height: 1;
        user-select: none; padding: 0 1px;
    }

    /* ── Icon action buttons ──────────────────────── */
    .fb-react-btn {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 11px; font-weight: 700; color: var(--fb-sub);
        background: none; border: none; padding: 4px 6px;
        cursor: pointer; border-radius: 6px; line-height: 1;
        transition: color .15s, background .15s;
    }
    .fb-react-btn svg { flex-shrink: 0; }
    .fb-react-btn:hover        { color: var(--fb-primary); background: #2d88ff10; }
    .fb-react-btn.fb-liked     { color: var(--fb-primary); }
    .fb-react-btn.fb-liked svg { fill: var(--fb-primary); }
    .fb-react-btn.fb-active    { color: var(--fb-primary); background: #2d88ff10; }
    .fb-react-btn.fb-cancel    { color: var(--fb-sub); }
    .fb-react-btn.fb-cancel:hover { color: var(--fb-text); background: #ffffff10; }
    .fb-react-btn.fb-danger:hover { color: var(--fb-danger); background: #f5656510; }
    .fb-react-btn.fb-danger:hover svg { stroke: var(--fb-danger); }

    /* Like badge */
    .fb-like-badge {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 12px; color: var(--fb-sub); margin-top: 6px; padding-left: 2px;
    }
    .fb-like-badge .like-icon {
        width: 17px; height: 17px; background: var(--fb-primary); border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        color: #fff; font-size: 9px; box-shadow: 0 1px 3px #0006;
    }

    /* View replies toggle */
    .fb-view-replies {
        display: inline-flex; align-items: center; gap: 5px;
        margin-top: 6px;
        font-size: 12px; font-weight: 700; color: var(--fb-sub);
        cursor: pointer; user-select: none; padding: 4px 8px;
        border-radius: 6px; transition: color .15s, background .15s;
    }
    .fb-view-replies:hover { color: var(--fb-primary); background: #2d88ff10; }
    .fb-view-replies svg { transition: transform .2s; flex-shrink: 0; }
    .fb-view-replies.open svg { transform: rotate(180deg); }

    /* Thread levels */
    .fb-thread-l1 {
        margin-top: 10px; margin-left: 46px;
        border-left: 2px solid var(--fb-border);
        padding-left: 14px;
        display: flex; flex-direction: column; gap: 12px;
    }
    .fb-thread-l2 {
        margin-top: 6px; margin-left: 40px;
        border-left: 2px solid #2d88ff30;
        padding-left: 12px;
        display: flex; flex-direction: column; gap: 10px;
    }

    /* Reply composer */
    .fb-composer-reply {
        display: flex; align-items: flex-end; gap: 8px; margin-top: 8px;
    }
    .fb-composer-reply.indent-l1 { margin-left: 46px; }

    /* Input box */
    .fb-input-box {
        flex: 1; position: relative; display: flex; align-items: flex-end;
        background: var(--fb-bubble); border-radius: var(--fb-radius);
        padding: 9px 44px 9px 14px;
        border: 1px solid var(--fb-border);
        transition: background .15s, border-color .15s, box-shadow .15s;
    }
    .fb-input-box:focus-within {
        background: var(--fb-bubble-h); border-color: #5a5b5c;
        box-shadow: 0 0 0 3px #2d88ff18;
    }
    .fb-input-box textarea {
        flex: 1; border: none; background: transparent; resize: none;
        outline: none; font-size: 14px; color: var(--fb-text);
        font-family: inherit; line-height: 1.5;
        min-height: 22px; max-height: 120px; overflow-y: auto; padding: 0;
    }
    .fb-input-box textarea::placeholder { color: var(--fb-sub); }
    .fb-input-actions {
        position: absolute; right: 8px; bottom: 6px;
        display: flex; align-items: center; gap: 2px;
    }
    .fb-emoji-btn {
        background: none; border: none; cursor: pointer;
        color: var(--fb-sub); padding: 4px; font-size: 17px; line-height: 1;
        border-radius: 50%; transition: color .15s, background .15s, transform .15s;
    }
    .fb-emoji-btn:hover { color: #f7b928; background: #f7b92818; transform: scale(1.15); }
    .fb-send-btn {
        background: none; border: none; cursor: pointer;
        color: var(--fb-primary); padding: 4px; line-height: 1; border-radius: 50%;
        opacity: 0; pointer-events: none; transform: scale(0.6);
        transition: opacity .15s, transform .2s, background .15s;
    }
    .fb-send-btn.visible { opacity: 1; pointer-events: auto; transform: scale(1); }
    .fb-send-btn:hover { background: #2d88ff18; }

    /* Validation error */
    .fb-error {
        font-size: 11px; color: var(--fb-danger);
        padding: 2px 4px; margin-top: 2px;
    }

    /* Main composer */
    .fb-composer {
        display: flex; align-items: flex-end; gap: 10px;
        padding: 14px 20px 16px;
        background: var(--fb-surface2);
        border-top: 1px solid var(--fb-border);
    }

    /* Load more */
    .fb-load-more {
        display: flex; justify-content: center;
        padding: 6px 20px 4px;
        background: var(--fb-surface);
        border-top: 1px solid var(--fb-border);
    }
    .fb-load-more button {
        font-size: 13px; font-weight: 700; color: var(--fb-sub);
        background: var(--fb-bubble); border: 1px solid var(--fb-border); cursor: pointer;
        padding: 7px 20px; border-radius: 8px;
        transition: background .15s, color .15s, border-color .15s;
    }
    .fb-load-more button:hover { background: var(--fb-bubble-h); color: var(--fb-text); border-color: #5a5b5c; }

    /* Guest prompt */
    .fb-guest-prompt {
        display: flex; align-items: center; justify-content: center; gap: 6px;
        padding: 14px 20px 16px;
        background: var(--fb-surface2); border-top: 1px solid var(--fb-border);
        font-size: 13px; color: var(--fb-sub);
    }
    .fb-guest-prompt a { color: var(--fb-primary); font-weight: 700; text-decoration: none; }
    .fb-guest-prompt a:hover { color: var(--fb-primary-h); text-decoration: underline; }

    /* Empty state */
    .fb-empty {
        display: flex; flex-direction: column; align-items: center; gap: 6px;
        padding: 32px 20px 20px; color: var(--fb-sub); font-size: 14px;
    }
    .fb-empty-icon { font-size: 32px; opacity: .5; margin-bottom: 4px; }
    .fb-empty strong { color: var(--fb-text); font-size: 15px; }

    /* Animations */
    .fb-entering { animation: fbSlideIn .2s ease forwards; }
    @keyframes fbSlideIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Mobile ──────────────────────────────────────── */
    @media (max-width: 480px) {
        .fb-header                { padding: 11px 12px 9px; }
        .fb-header h2             { font-size: 13px; }
        .fb-header-count          { font-size: 11px; padding: 2px 8px; }
        .fb-comment-list          { padding: 10px 10px 6px; gap: 14px; }
        .fb-avatar                { width: 28px; height: 28px; border-width: 1px; }
        .fb-avatar.sm             { width: 24px; height: 24px; }
        .fb-comment-row           { gap: 7px; }
        .fb-bubble                { padding: 7px 10px; border-radius: 4px 14px 14px 14px; }
        .fb-bubble .fb-name       { font-size: 12px; margin-bottom: 2px; }
        .fb-bubble .fb-text       { font-size: 13px; line-height: 1.4; }
        .fb-reaction-bar          { margin-top: 4px; gap: 1px; }
        .fb-reaction-bar .fb-time { font-size: 10px; padding: 2px 4px; }
        .fb-react-btn             { font-size: 10px; padding: 3px 5px; gap: 3px; }
        .fb-react-btn svg         { width: 12px; height: 12px; }
        .fb-like-badge            { font-size: 11px; margin-top: 4px; }
        .fb-like-badge .like-icon { width: 14px; height: 14px; font-size: 8px; }
        .fb-thread-l1             { margin-left: 32px; padding-left: 10px; gap: 10px; margin-top: 8px; }
        .fb-thread-l2             { margin-left: 28px; padding-left: 8px; gap: 8px; }
        .fb-view-replies          { font-size: 11px; padding: 3px 6px; margin-top: 4px; }
        .fb-composer-reply.indent-l1 { margin-left: 32px; }
        .fb-input-box             { padding: 7px 38px 7px 11px; border-radius: 14px; }
        .fb-input-box textarea    { font-size: 13px; min-height: 18px; }
        .fb-emoji-btn             { font-size: 15px; padding: 3px; }
        .fb-composer              { padding: 10px 12px 12px; gap: 7px; }
        .fb-load-more button      { font-size: 12px; padding: 6px 14px; }
    }

    @media (max-width: 360px) {
        .fb-comment-list          { padding: 8px 8px 4px; gap: 12px; }
        .fb-avatar                { width: 26px; height: 26px; }
        .fb-avatar.sm             { width: 22px; height: 22px; }
        .fb-bubble .fb-text       { font-size: 12px; }
        .fb-thread-l1             { margin-left: 26px; padding-left: 8px; }
        .fb-thread-l2             { margin-left: 22px; padding-left: 6px; }
        .fb-composer-reply.indent-l1 { margin-left: 26px; }
    }
</style>

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
    <h2>💬 Comments</h2>
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
                                <span class="like-icon">👍</span> {{ $comment['likesCount'] }}
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
                <div class="fb-empty-icon">💭</div>
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
                <button class="fb-emoji-btn" type="button" tabindex="-1">😊</button>
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
        <span>to join the conversation.</span>
    </div>
@endauth

</div>
