<aside class="sidebar-area">
    <div class="widget widget_tag_cloud">

        @livewire('search.post')

    </div>
    <div class="widget widget_categories">
        <h3 class="widget_title">{{ __("Categories") }}</h3>
        <ul>
            @foreach ($relatedCategories as $category)
            <li>
                <a  href="{{ route('page.index', $category->slug_path) }}"
                    >
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
                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ config('app.name') }}">
                </div>
                <div class="media-body">
                    <h4 class="post-title">
                        <a class="hover-line" href="#">
                            {{ \Illuminate\Support\Str::limit($post->title, 60) }}
                        </a>
                    </h4>
                    <div class="recent-post-meta">
                        <a href="#">
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

    {{-- <div class="widget widget_tag_cloud">
        <h3 class="widget_title">{{ __("Popular Tags") }}</h3>
        <div class="tagcloud">
            @forelse ($popularTags as $tag)
            @php
            $post = $tag->posts->first();
            $categoryPath = $post->category->slug_path;
            @endphp

            @if ($post && $categoryPath)
            <a href="{{ url($categoryPath . '/' . $tag->slug) }}">
                {{ $tag->name }}
            </a>
            @endif
            @empty
            <p>{{ __("No popular tags available.") }}</p>
            @endforelse
        </div>
    </div> --}}

</aside>
