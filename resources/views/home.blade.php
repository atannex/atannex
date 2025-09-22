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

    <section class="space-bottom">
        <div class="container">
            <div class="row">
                <div class="col-xl-8">
                    <h2 class="sec-title has-line">Popular News</h2>
                    <div class="mb-4">
                        <div class="dark-theme img-overlay2 space-40">
                            <div class="blog-style3">
                                <div class="blog-img">
                                    <img src="assets/img/blog/blog_5_15.jpg" alt="blog image" />
                                </div>
                                <div class="blog-content">
                                    <a data-theme-color="#6234AC" href="blog.html" class="category">Technology</a>
                                    <h3 class="box-title-40">
                                        <a class="hover-line" href="blog-details.html">Tech Unleash possibilities, shape a brighter
                                            future.</a>
                                    </h3>
                                    <div class="blog-meta">
                                        <a href="author.html"><i class="far fa-user"></i>By - Tnews</a>
                                        <a href="blog.html"><i class="fal fa-calendar-days"></i>15 Mar, 2023</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="blog-style2">
                                <div class="blog-img img-big">
                                    <img src="assets/img/blog/blog_3_3_7.jpg" alt="blog image" />
                                </div>
                                <div class="blog-content">
                                    <a data-theme-color="#6234AC" href="blog.html" class="category">Robotic</a>
                                    <h3 class="box-title-20">
                                        <a class="hover-line" href="blog-details.html">Smarter living, gadgets make your world.</a>
                                    </h3>
                                    <div class="blog-meta">
                                        <a href="blog.html"><i class="fal fa-calendar-days"></i>27 Mar, 2023</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="blog-style2">
                                <div class="blog-img img-big">
                                    <img src="assets/img/blog/blog_3_3_8.jpg" alt="blog image" />
                                </div>
                                <div class="blog-content">
                                    <a data-theme-color="#6234AC" href="blog.html" class="category">Tech</a>
                                    <h3 class="box-title-20">
                                        <a class="hover-line" href="blog-details.html">From dreams to reality, tech pioneers</a>
                                    </h3>
                                    <div class="blog-meta">
                                        <a href="blog.html"><i class="fal fa-calendar-days"></i>16 Mar, 2023</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="blog-style2">
                                <div class="blog-img img-big">
                                    <img src="assets/img/blog/blog_3_3_9.jpg" alt="blog image" />
                                </div>
                                <div class="blog-content">
                                    <a data-theme-color="#6234AC" href="blog.html" class="category">Gadget</a>
                                    <h3 class="box-title-20">
                                        <a class="hover-line" href="blog-details.html">Technology drives the digital revolution</a>
                                    </h3>
                                    <div class="blog-meta">
                                        <a href="blog.html"><i class="fal fa-calendar-days"></i>27 Mar, 2023</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="blog-style2">
                                <div class="blog-img img-big">
                                    <img src="assets/img/blog/blog_3_3_10.jpg" alt="blog image" />
                                </div>
                                <div class="blog-content">
                                    <a data-theme-color="#6234AC" href="blog.html" class="category">VR Glass</a>
                                    <h3 class="box-title-20">
                                        <a class="hover-line" href="blog-details.html">Where possibility meet boundless feelings</a>
                                    </h3>
                                    <div class="blog-meta">
                                        <a href="blog.html"><i class="fal fa-calendar-days"></i>19 Mar, 2023</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="sec-title has-line">Featured News</h2>
                    <div class="mbn-24">
                        <div class="mb-4">
                            <div class="blog-style4">
                                <div class="blog-img w-270">
                                    <img src="assets/img/blog/blog_6_3_1.jpg" alt="blog image" />
                                </div>
                                <div class="blog-content">
                                    <a data-theme-color="#6234AC" href="blog.html" class="category">Gadget</a>
                                    <h3 class="box-title-22">
                                        <a class="hover-line" href="blog-details.html">Tech brilliance, forging a path to a smarter
                                            connected
                                            universe.</a>
                                    </h3>
                                    <div class="blog-meta">
                                        <a href="author.html"><i class="far fa-user"></i>By - Tnews</a>
                                        <a href="blog.html"><i class="fal fa-calendar-days"></i>11 Mar, 2023</a>
                                    </div>
                                    <a href="blog-details.html" class="th-btn style2">Read More<i class="fas fa-arrow-up-right ms-2"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="blog-style4">
                                <div class="blog-img w-270">
                                    <img src="assets/img/blog/blog_6_3_2.jpg" alt="blog image" />
                                </div>
                                <div class="blog-content">
                                    <a data-theme-color="#6234AC" href="blog.html" class="category">Technology</a>
                                    <h3 class="box-title-22">
                                        <a class="hover-line" href="blog-details.html">where possibilities blossom, and lives thrive with
                                            technology.</a>
                                    </h3>
                                    <div class="blog-meta">
                                        <a href="author.html"><i class="far fa-user"></i>By - Tnews</a>
                                        <a href="blog.html"><i class="fal fa-calendar-days"></i>27 Mar, 2023</a>
                                    </div>
                                    <a href="blog-details.html" class="th-btn style2">Read More<i class="fas fa-arrow-up-right ms-2"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="blog-style4">
                                <div class="blog-img w-270">
                                    <img src="assets/img/blog/blog_6_3_3.jpg" alt="blog image" />
                                </div>
                                <div class="blog-content">
                                    <a data-theme-color="#6234AC" href="blog.html" class="category">Robotic</a>
                                    <h3 class="box-title-22">
                                        <a class="hover-line" href="blog-details.html">Robotics empowers progress, reshaping industries with
                                            ingenuity.</a>
                                    </h3>
                                    <div class="blog-meta">
                                        <a href="author.html"><i class="far fa-user"></i>By - Tnews</a>
                                        <a href="blog.html"><i class="fal fa-calendar-days"></i>20 Mar, 2023</a>
                                    </div>
                                    <a href="blog-details.html" class="th-btn style2">Read More<i class="fas fa-arrow-up-right ms-2"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="blog-style4">
                                <div class="blog-img w-270">
                                    <img src="assets/img/blog/blog_6_3_4.jpg" alt="blog image" />
                                </div>
                                <div class="blog-content">
                                    <a data-theme-color="#6234AC" href="blog.html" class="category">Desk</a>
                                    <h3 class="box-title-22">
                                        <a class="hover-line" href="blog-details.html">where gadgets enhance your life effortlessly and
                                            beautifully.</a>
                                    </h3>
                                    <div class="blog-meta">
                                        <a href="author.html"><i class="far fa-user"></i>By - Tnews</a>
                                        <a href="blog.html"><i class="fal fa-calendar-days"></i>17 Mar, 2023</a>
                                    </div>
                                    <a href="blog-details.html" class="th-btn style2">Read More<i class="fas fa-arrow-up-right ms-2"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="blog-style4">
                                <div class="blog-img w-270">
                                    <img src="assets/img/blog/blog_6_3_5.jpg" alt="blog image" />
                                </div>
                                <div class="blog-content">
                                    <a data-theme-color="#6234AC" href="blog.html" class="category">VR Glass</a>
                                    <h3 class="box-title-22">
                                        <a class="hover-line" href="blog-details.html">Elevate life, redefine human potential with virtual
                                            reality.</a>
                                    </h3>
                                    <div class="blog-meta">
                                        <a href="author.html"><i class="far fa-user"></i>By - Tnews</a>
                                        <a href="blog.html"><i class="fal fa-calendar-days"></i>12 Mar, 2023</a>
                                    </div>
                                    <a href="blog-details.html" class="th-btn style2">Read More<i class="fas fa-arrow-up-right ms-2"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-10 col-xl-4 mt-35 mt-xl-0 sidebar-wrap">
                    <div class="sidebar-area">
                        <div class="widget">
                            <h2 class="sec-title fs-20 has-line">Most Read</h2>
                            <div class="row gy-4">
                                <div class="col-xl-12 col-md-6">
                                    <div class="blog-style2">
                                        <div class="blog-img img-big">
                                            <img src="assets/img/blog/blog_3_3_11.jpg" alt="blog image" />
                                        </div>
                                        <div class="blog-content">
                                            <a data-theme-color="#6234AC" href="blog.html" class="category">Gadget</a>
                                            <h3 class="box-title-20">
                                                <a class="hover-line" href="blog-details.html">Gadgets amaze, connect inspire you.</a>
                                            </h3>
                                            <div class="blog-meta">
                                                <a href="blog.html"><i class="fal fa-calendar-days"></i>22 Mar, 2023</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-6">
                                    <div class="blog-style2">
                                        <div class="blog-img img-big">
                                            <img src="assets/img/blog/blog_3_3_12.jpg" alt="blog image" />
                                        </div>
                                        <div class="blog-content">
                                            <a data-theme-color="#6234AC" href="blog.html" class="category">Phone</a>
                                            <h3 class="box-title-20">
                                                <a class="hover-line" href="blog-details.html">Tech at your fingertips, phone redefines</a>
                                            </h3>
                                            <div class="blog-meta">
                                                <a href="blog.html"><i class="fal fa-calendar-days"></i>26 Mar, 2023</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-6">
                                    <div class="blog-style2">
                                        <div class="blog-img img-big">
                                            <img src="assets/img/blog/blog_3_3_13.jpg" alt="blog image" />
                                        </div>
                                        <div class="blog-content">
                                            <a data-theme-color="#6234AC" href="blog.html" class="category">VR Glass</a>
                                            <h3 class="box-title-20">
                                                <a class="hover-line" href="blog-details.html">Elevate life, embrace modern technology.</a>
                                            </h3>
                                            <div class="blog-meta">
                                                <a href="blog.html"><i class="fal fa-calendar-days"></i>10 Mar, 2023</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-6">
                                    <div class="blog-style2">
                                        <div class="blog-img img-big">
                                            <img src="assets/img/blog/blog_3_3_14.jpg" alt="blog image" />
                                        </div>
                                        <div class="blog-content">
                                            <a data-theme-color="#6234AC" href="blog.html" class="category">Robotic</a>
                                            <h3 class="box-title-20">
                                                <a class="hover-line" href="blog-details.html">Robotic wonders redefine possibilities.</a>
                                            </h3>
                                            <div class="blog-meta">
                                                <a href="blog.html"><i class="fal fa-calendar-days"></i>28 Mar, 2023</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layouts.guest>
