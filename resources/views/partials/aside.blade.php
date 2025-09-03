<aside class="sidebar-area">
    <div class="widget widget_tag_cloud">

        @livewire('search.post')

    </div>
    <div class="widget widget_categories">
        <h3 class="widget_title">{{ __("Categories") }}</h3>
        <ul>
            @foreach ($relatedCategories as $category)
            @php
            $firstPost = $category->posts->first();
            @endphp
            <li>
                <a data-bg-src="{{ $firstPost ? asset('storage/' . $firstPost->image) : '' }}" href="{{ route('page.index', $category->slug_path) }}">
                    {{ $category->name }}
                </a>
            </li>
            @endforeach

        </ul>
    </div>
    <div class="widget">
        <h3 class="widget_title">{{ __("Recent Posts") }}</h3>
        <div class="recent-post-wrap">
            @forelse ($recentPosts as $post)
            <div class="recent-post">
                <div class="media-img">

                    @include('partials.image')
                </div>
                <div class="media-body">
                    <h4 class="post-title">

                        @include('partials.title')

                    </h4>
                    <div class="recent-post-meta">
                        <a href="{{ route('page.index', ['slug' => $post->slug_path ]) }}">
                            <i class="fal fa-calendar-days"></i>
                            {{ $post->published_at->format('d M, Y') }}
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-muted">{{ __("No recent posts available.") }}</p>
            @endforelse
        </div>
    </div>
    <div class="widget widget_tag_cloud">

          @include('components.partials.tags')

        </div>
    </div>

</aside>
