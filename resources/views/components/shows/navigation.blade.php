@php
$previousPost = $navigation['previous'];
$nextPost = $navigation['next'];
@endphp

@if($previousPost || $nextPost)
<div class="blog-navigation">

    <div class="nav-btn prev">
        @if($previousPost)
        <div class="img">
            <img src="{{ asset('storage/' . $previousPost->image) }}" alt="{{ $previousPost->title }}" class="avatar avatar--lg avatar--status">
        </div>
        <div class="media-body">
            <h5 class="title">
                <a href="{{ route('posts.show', ['slug' => $previousPost->slug_path]) }}" class="hover-line">
                    {{ Str::limit($previousPost->title, 60) }}
                </a>
            </h5>
            <a href="{{ route('posts.show', ['slug' => $previousPost->slug_path]) }}" class="nav-text">
                <i class="fas fa-arrow-left me-2"></i> {{ __('Previous') }}
            </a>
        </div>
        @endif
    </div>

    @if($previousPost || $nextPost)
    <div class="divider"></div>
    @endif

    <div class="nav-btn next">
        @if($nextPost)
        <div class="media-body">
            <h5 class="title">
                <a href="{{ route('posts.show', ['slug' => $nextPost->slug_path]) }}" class="hover-line">
                    {{ Str::limit($nextPost->title, 60) }}
                </a>
            </h5>
            <a href="{{ route('posts.show', ['slug' => $nextPost->slug_path]) }}" class="nav-text">
                {{ __('Next') }} <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <div class="img">
            <img src="{{ asset('storage/' . $nextPost->image) }}" alt="{{ $nextPost->title }}" class="avatar avatar--lg avatar--status">
        </div>
        @endif
    </div>

</div>
@endif
