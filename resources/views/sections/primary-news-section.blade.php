<section class="space">
    <div class="container">
        @foreach ($section->tabs as $tab)
        <div class="row">
            <div class="col-xl-9">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="sec-title has-line">
                            {{ $tab['title'] }}
                        </h2>
                    </div>
                    <div class="col-auto">
                        <div class="sec-btn">
                            <div class="filter-menu filter-menu-active">
                                <button data-filter="*" class="tab-btn active" type="button">
                                    {{ __('ALL') }}
                                </button>
                                @foreach ($tab['content'] as $region)
                                <button data-filter=".cat-region-{{ $region['id'] }}" class="tab-btn" type="button">
                                    {{ $region['name'] }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-active">
                    @foreach ($tab['content'] as $region)
                    @foreach ($region['posts'] as $post)
                    <div class="border-blog2 filter-item cat-region-{{ $region['id'] }}">
                        <div class="blog-style4">

                            <div class="blog-img">

                                @include('partials.image', ['post' => $post, 'class'=> 'primary-news-section'])

                                @if ($post->region)
                                <a href="{{ route('regions.show', ['path' => $post->region->slug_path]) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
                                    {{ $post->region->name }}
                                </a>
                                @endif

                            </div>

                            <div class="blog-content">
                                @include('partials.category', ['post' => $post])

                                <h3 class="box-title-24">
                                    <a href="{{ route('posts.show', ['slug' => $post->slug_path ]) }}" class="hover-line">
                                        {{ Str::limit($post->title, 110) }}
                                    </a>
                                </h3>

                                <p class="blog-text">
                                    {{ Str::limit($post->description, 120) }}
                                </p>

                                <div class="blog-meta">
                                    @include('partials.author', ['post' => $post])

                                    @include('partials.date', [ 'post' => $post ])

                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endforeach
                </div>

            </div>

            <div class="mb-10 col-xl-3 mt-35 mt-xl-0 sidebar-wrap">
                <div class="sidebar-area">
                    @foreach ($section->widgets as $widget)
                    @include("widgets.{$widget->slug}", ['widget' => $widget])
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
