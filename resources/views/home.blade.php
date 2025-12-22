@extends('components.layouts.guest')

@section('og:title', seo_title())

@section('guest')

@if($recentPosts->isNotEmpty())
<div class="mb-4 th-hero-wrapper hero-1" id="hero">

    <div class="hero-slider-1 th-carousel" data-fade="true" data-slide-show="1" data-md-slide-show="1" data-adaptive-height="true">

        @foreach($recentPosts as $post)
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
                @foreach($recentPosts as $index => $post)
                <div class="tab-btn {{ $index === 0 ? 'active' : '' }}">

                    <a href="{{ route('page.index', ['slug' => $post->slug_path ]) }}">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ config('app.name') }}" class="img-fluid home-hero">

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
                <h2 class="sec-title has-line">
                    {{ __("Editor Picks") }}
                </h2>
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
                        @if($region->posts->count() > 0)
                        <button data-filter=".cat{{ $index+1 }}" class="tab-btn" type="button">
                            {{ $region->name }}
                        </button>
                        @endif
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

@if($todayPosts->isNotEmpty())
<section class="space">
    <div class="container">
        <h2 class="sec-title has-line">
            {{ $heroTitle }}
        </h2>
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

<section class="space-bottom">
    <div class="container">
        <div class="row">
            <div class="col-xl-8">

                @if($popularPosts->isNotEmpty())
                <h2 class="sec-title has-line">
                    {{ __("Popular News") }}
                </h2>

                <div class="mb-4">
                    <div class="dark-theme img-overlay2 space-40">
                        <div class="blog-style3">
                            <div class="blog-img">

                                @include('partials.image', ['post' => $popularPosts[0]])

                            </div>
                            <div class="blog-content">

                                @include('partials.category', ['post' => $popularPosts[0]])

                                <h3 class="box-title-40">

                                    @include('partials.title', ['post' => $popularPosts[0]])

                                </h3>
                                <div class="blog-meta">

                                    @include('partials.author', ['post' => $popularPosts[0]])

                                    @include('partials.date', ['post' => $popularPosts[0]])

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gy-4">
                    @foreach($popularPosts->skip(1) as $blog)
                    <div class="col-md-6">
                        <div class="blog-style2">
                            <div class="blog-img img-big">

                                @include('partials.image', ['post' => $blog])

                            </div>
                            <div class="blog-content">

                                @include('partials.category', ['post' => $blog])

                                <h3 class="box-title-20">

                                    @include('partials.title', ['post' => $blog])

                                </h3>
                                <div class="blog-meta">

                                    @include('partials.date', ['post' => $blog])

                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                @if($featuredPosts->isNotEmpty())
                <h2 class="mt-5 sec-title has-line">
                    {{ __("Featured News") }}
                </h2>
                <div class="mbn-24">
                    @foreach($featuredPosts as $blog)
                    <div class="mb-4">
                        <div class="blog-style4">
                            <div class="blog-img w-270">

                                @include('partials.image', ['post' => $blog])

                            </div>
                            <div class="blog-content">

                                @include('partials.category', ['post' => $blog])

                                <h3 class="box-title-22">

                                    @include('partials.title', ['post' => $blog])

                                </h3>
                                <div class="blog-meta">

                                    @include('partials.author', ['post' => $blog])

                                    @include('partials.date', ['post' => $blog])

                                </div>
                                <a href="{{ route('page.index', ['slug' => $blog->slug_path]) }}" class="th-btn style2">
                                    {{ __("Read More") }}
                                    <i class="fas fa-arrow-up-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            @if($mostReadPosts->isNotEmpty())
            <div class="mb-10 col-xl-4 mt-35 mt-xl-0 sidebar-wrap">
                <div class="sidebar-area">
                    <div class="widget">
                        <h2 class="sec-title fs-20 has-line">
                            {{ __("Most Read") }}
                        </h2>
                        <div class="row gy-4">
                            @foreach($mostReadPosts as $blog)
                            <div class="col-xl-12 col-md-6">
                                <div class="blog-style2">
                                    <div class="blog-img img-big">

                                        @include('partials.image', ['post' => $blog])

                                    </div>
                                    <div class="blog-content">

                                        @include('partials.category', ['post' => $blog])

                                        <h3 class="box-title-20">

                                            @include('partials.title', ['post' => $blog])

                                        </h3>
                                        <div class="blog-meta">

                                            @include('partials.date', ['post' => $blog])

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</section>

@endsection
