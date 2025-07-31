<h3 class="widget_title">{{ __("Recent Posts") }}</h3>

<div class="recent-post-wrap">
    @foreach ($global['recentPosts'] as $post)
    <div class="recent-post">
        <div class="media-img">
            <a href="#" aria-label="{{ $post->title }}">
                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
            </a>
        </div>

        <div class="media-body">
            <h4 class="post-title">
                <a class="hover-line" href="#">
                    {{ $post->title }}
                </a>
            </h4>

            <div class="recent-post-meta">
                <a href="#" aria-label="Posts on {{ $post->created_at->format('d M, Y') }}">
                    <i class="fal fa-calendar-days"></i> {{ $post->created_at->format('d M, Y') }}
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
