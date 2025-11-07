<aside class="sidebar-area">
    <div class="widget widget_tag_cloud">

        @livewire('search.post')

    </div>

    <div class="widget widget_categories">
        <h3 class="widget_title">
            {{ __('Categories') }}
        </h3>
        <ul>
            @foreach ($relatedCategories as $category)
            @php($data = displayData($category))
            <li>
                <a href="{{ route('page.index', $category->slug_path) }}" @if($data['bgSrc']) data-bg-src="{{ $data['bgSrc'] }}" @endif>
                    {{ $data['label'] }}
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

                        @include('partials.date')

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
