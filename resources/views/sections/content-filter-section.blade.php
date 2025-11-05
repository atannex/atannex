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
                        @foreach ($tab['entities'] as $index => $region)
                        <button type="button" data-filter=".filter-{{ $region['id'] }}" class="tab-btn {{ $index === 0 ? 'active' : '' }}" aria-label="{{ __('Filter by :region', ['region' => $region['name']]) }}">
                            {{ $region['name'] }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="filter-active-cat1">
            @foreach ($tab['entities'] as $index => $region)
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

                            @include('partials.image', [ 'post' => $featuredPost, 'class'=> 'content-filter-section' ])

                            @include('partials.category', [ 'post' => $featuredPost ])

                        </div>
                        <h3 class="box-title-30">

                            @include('partials.title', [ 'post' => $featuredPost ])

                        </h3>
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

                                    @include('partials.image',['class'=> 'small-image-carousel'])

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
