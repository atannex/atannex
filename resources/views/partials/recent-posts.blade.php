<div class="widget">
    <h3 class="widget_title">{{ __("Recent Posts") }}</h3>

    <div class="recent-post-wrap">
        @foreach ($global['recentPosts'] as $post)
        <div class="recent-post">
            <div class="media-img">
                <a href="{{ route('page.index', ['slug' => $post->slug_path ]) }}" aria-label="{{ $post->title }}">
                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                </a>
            </div>

            <div class="media-body">
                <h4 class="post-title">
                    <a class="hover-line" href="{{ route('page.index', ['slug' => $post->slug_path ]) }}">
                        {{ $post->title }}
                    </a>
                </h4>

                <div class="recent-post-meta">
                    <a href="{{ route('page.index', $post->published_at->format('Y/m')) }}" aria-label="Posts on {{ $post->created_at->format('d M, Y') }}">
                        <i class="fal fa-calendar-days"></i>
                        {{ $post->created_at->format('d M, Y') }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
