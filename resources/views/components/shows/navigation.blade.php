@php
$previousPost = $navigation['previous'];
$nextPost = $navigation['next'];
@endphp

@if($previousPost || $nextPost)
<div class="blog-navigation d-flex justify-content-between align-items-center">

    <div class="nav-btn prev d-flex align-items-center {{ $previousPost ? '' : 'invisible' }}">
        @if($previousPost)
        <div class="img me-3">
            <img src="{{ asset('storage/' . $previousPost->image) }}" alt="{{ $previousPost->title }}" class="img-fluid rounded-circle profile-img">
        </div>
        <div class="media-body">
            <h5 class="mb-2 title">
                <a href="{{ route('page.index', ['slug' => $previousPost->slug_path]) }}" class="hover-line text-decoration-none">
                    {{ Str::limit($previousPost->title, 60) }}
                </a>
            </h5>
            <a href="{{ route('page.index', ['slug' => $previousPost->slug_path]) }}" class="nav-text text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i> {{ __('Previous') }}
            </a>
        </div>
        @endif
    </div>

    @if($previousPost && $nextPost)
    <div class="mx-3 divider" style="width: 1px; height: 80px; background: #e5e5e5;"></div>
    @endif

    <div class="nav-btn next d-flex align-items-center {{ $nextPost ? '' : 'invisible' }}">
        @if($nextPost)
        <div class="media-body text-end">
            <h5 class="mb-2 title">
                <a href="{{ route('page.index', ['slug' => $nextPost->slug_path]) }}" class="hover-line text-decoration-none">
                    {{ Str::limit($nextPost->title, 60) }}
                </a>
            </h5>
            <a href="{{ route('page.index', ['slug' => $nextPost->slug_path]) }}" class="nav-text text-decoration-none">
                {{ __('Next') }} <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <div class="ms-3">
            <img src="{{ asset('storage/' . $nextPost->image) }}" alt="{{ $nextPost->title }}" class="img-fluid rounded-circle profile-img">
        </div>

        @endif
    </div>

</div>
@endif
