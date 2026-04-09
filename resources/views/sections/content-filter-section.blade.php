<section class="space">
    <div class="container">
        @foreach ($section->tabs as $tabIndex => $tab)
        <div class="mb-4 row align-items-center">
            <div class="col">
                <h2 class="sec-title has-line">{{ $tab['title'] }}</h2>
            </div>
            <div class="col-auto">
                <div class="sec-btn">
                    <div class="filter-menu filter-menu-active1">
                        @foreach ($tab['content'] as $index => $region)
                        <button type="button" data-filter=".filter-{{ $region['id'] }}" class="tab-btn {{ $index === 0 ? 'active' : '' }}" aria-label="{{ __('Filter by :region', ['region' => $region['name']]) }}">
                            {{ $region['name'] }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="filter-active-cat1">
            @foreach ($tab['content'] as $index => $region)
            @php
            $posts = $region->posts;
            $featuredPost = $posts->first();
            $remainingPosts = $posts->skip(1)->take(4);
            @endphp

            @if ($posts->isNotEmpty())
            <div class="row filter-item filter-{{ $region['id'] }} {{ $index === 0 ? 'active-filter' : '' }}">

                @if ($featuredPost)
                <div class="mb-4 col-xl-6 mb-xl-0">
                    <article class="blog-style1 style-big">
                        <div class="blog-img">

                            <a href="{{ route('posts.show', ['slug' => $featuredPost->slug_path ]) }}">
                                <img src="{{ asset('storage/' . $featuredPost->image) }}" alt="{{ config('app.name') }}" class="img-fluid content-filter-section">
                            </a>

                            @include('partials.category', [ 'post' => $featuredPost ])

                        </div>
                        <h3 class="box-title-30">

                            @include('partials.title', [ 'post' => $featuredPost ])

                        </h3>
                        <p class="blog-text">
                            {{ Str::limit($featuredPost->description, 120) }}
                        </p>
                        <div class="blog-meta">

                            @include('partials.author', [ 'post' => $featuredPost ])

                            @include('partials.date', [ 'post' => $featuredPost ])

                        </div>
                    </article>
                </div>
                @endif

                @if ($remainingPosts->count() > 0)
                <div class="col-xl-6">
                    <div class="row gy-4">
                        @foreach ($remainingPosts as $post)
                        <div class="col-xl-6 col-sm-6 border-blog two-column">
                            <article class="blog-style1">
                                <div class="blog-img">

                                    <a href="{{ route('posts.show', ['slug' => $post->slug_path ]) }}">
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ config('app.name') }}" class="img-fluid small-image-carousel">
                                    </a>

                                    @include('partials.category')

                                </div>
                                <h3 class="box-title-22">

                                    @include('partials.title')

                                </h3>
                                <div class="blog-meta">

                                    @include('partials.author')

                                    @include('partials.date')

                                </div>
                            </article>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif
            @endforeach
        </div>
        @endforeach
    </div>
</section>
