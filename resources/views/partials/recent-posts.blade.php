<div class="widget">
    <h3 class="widget_title">
        {{ __("Recent Posts") }}
    </h3>

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

                    @include('partials.title', ['post' => $post])

                </h4>

                @include('partials.date', ['post' => $post])
            </div>
        </div>
        @endforeach
    </div>
</div>
