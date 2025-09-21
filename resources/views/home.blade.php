<x-layouts.guest :title="seo_title()">

    @if($allPosts->isNotEmpty())
    <div class="mb-4 th-hero-wrapper hero-1" id="hero">

        <div class="hero-slider-1 th-carousel" data-fade="true" data-slide-show="1" data-md-slide-show="1" data-adaptive-height="true">

            @foreach($allPosts as $post)
            <div class="th-hero-slide">

                <div class="th-hero-bg" data-overlay="black" data-opacity="6" data-bg-src="{{ asset('storage/' . $post->image) }}">
                </div>


                <div class="container">
                    <div class="blog-bg-style1">


                        @if($post->category)
                        <a href="{{ route('page.index', $post->category->slug_path) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}" data-ani="slideinup" data-ani-delay="0.1s">
                            {{ $post->category->name }}
                        </a>
                        @endif


                        <h3 class="box-title-50" data-ani="slideinup" data-ani-delay="0.3s">

                            @include('partials.title', ['post' => $post])

                        </h3>


                        <div class="blog-meta" data-ani="slideinup" data-ani-delay="0.5s">

                            @include('partials.author', ['post' => $post])

                            @include('partials.date', ['post' => $post])

                        </div>


                        <p class="blog-text" data-ani="slideinup" data-ani-delay="0.7s">
                            {{ Str::limit(strip_tags($post->description), 150) }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="hero-tab-area">
            <div class="container">
                <div class="hero-tab" data-asnavfor=".hero-slider-1">
                    @foreach($allPosts as $index => $post)
                    <div class="tab-btn {{ $index === 0 ? 'active' : '' }}">
                        @include('partials.image', ['post' => $post, 'class' => 'home-hero'])
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($editorPicks->isNotEmpty())
    <div class="space-top">
        <div class="container">

            <div class="row align-items-center">
                <div class="col">
                    <h2 class="sec-title has-line">{{ __("Editor Picks") }}</h2>
                </div>
                <div class="col-auto">
                    <div class="sec-btn">
                        <div class="icon-box">
                            <button data-slick-prev="#blog-slide7" class="slick-arrow default">
                                <i class="far fa-arrow-left"></i>
                            </button>
                            <button data-slick-next="#blog-slide7" class="slick-arrow default">
                                <i class="far fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row th-carousel" id="blog-slide7" data-slide-show="4" data-lg-slide-show="3" data-md-slide-show="2" data-sm-slide-show="2">

                @foreach($editorPicks as $post)
                <div class="col-sm-6 col-lg-4 col-xl-3 dark-theme">
                    <div class="blog-style3">
                        <div class="blog-img">

                            @include('partials.image', ['post' => $post])

                        </div>
                        <div class="blog-content">

                            @include('partials.category', ['post' => $post])

                            <h3 class="box-title-20">

                                @include('partials.title', ['post' => $post])

                            </h3>

                            <div class="blog-meta">

                                @include('partials.author', ['post' => $post])

                                @include('partials.date', ['post' => $post])

                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif


    @if($todayPosts->isNotEmpty())
    <section class="space">
        <div class="container">
            <h2 class="sec-title has-line">{{ $heroTitle }}</h2>
            <div class="row">

                <div class="col-xl-3">
                    <div class="row gy-4">
                        @foreach($sideBlogs as $post)
                        <div class="col-xl-12 col-sm-6">
                            <div class="blog-style1">
                                <div class="blog-img">

                                    @include('partials.image', ['post' => $post])

                                    @include('partials.category', ['post' => $post])

                                </div>

                                <h3 class="box-title-22">

                                    @include('partials.title', ['post' => $post])

                                </h3>

                                <div class="blog-meta">

                                    @include('partials.author', ['post' => $post])

                                    @include('partials.date', ['post' => $post])

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 col-xl-9 mt-xl-0">
                    <div class="dark-theme space-40">
                        <div class="blog-style3">
                            <div class="blog-img">

                                @include('partials.image', ['post' => $featuredBlog])

                            </div>
                            <div class="blog-content">

                                @include('partials.category', ['post' => $featuredBlog])

                                <h3 class="box-title-40">

                                    @include('partials.title', ['post' => $featuredBlog])

                                </h3>

                                <div class="blog-meta">

                                    @include('partials.author', ['post' => $featuredBlog])

                                    @include('partials.date', ['post' => $featuredBlog])

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @endif


    @if($regions->isNotEmpty())
    <section class="space">
        <div class="container">

            <div class="row align-items-center">
                <div class="col">
                    <h2 class="sec-title has-line">
                        {{ __("Updates Per Division") }}
                    </h2>
                </div>
                <div class="col-auto">
                    <div class="sec-btn">
                        <div class="filter-menu filter-menu-active">
                            <button data-filter="*" class="tab-btn active" type="button">
                                {{ __('ALL') }}
                            </button>
                            @foreach($regions as $index => $region)
                            <button data-filter=".cat{{ $index+1 }}" class="tab-btn" type="button">
                                {{ $region->name }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gy-24 filter-active mbn-24">

                @foreach($regions as $index => $region)
                @if($region->allPosts->isNotEmpty())
                @foreach($region->allPosts as $post)

                <div class="col-xl-4 col-md-6 filter-item cat{{ $index+1 }}">
                    <div class="blog-style2">
                        <div class="blog-img img-big">

                            @include('partials.image', ['post' => $post])

                        </div>
                        <div class="blog-content">

                            @foreach ($post->regions as $postRegion)
                            <a href="{{ route('page.index', ['slug' => $postRegion->slug_path]) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
                                {{ $postRegion->name }}
                            </a>
                            @endforeach

                            <h3 class="box-title-20">

                                @include('partials.title', ['post' => $post])

                            </h3>

                            <div class="blog-meta">

                                @include('partials.date', ['post' => $post])

                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

</x-layouts.guest>
