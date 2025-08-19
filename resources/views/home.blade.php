<x-layouts.guest :title="seo_title('Be Our Guest')">

    <div class="mt-2 mb-4 th-hero-wrapper hero-1" id="hero">
        <div class="hero-slider-1 th-carousel" data-fade="true" data-slide-show="1" data-md-slide-show="1" data-adaptive-height="true">
            @foreach($recentPosts as $post)
            <div class="th-hero-slide">
                <div class="th-hero-bg" data-overlay="black" data-opacity="6" data-bg-src="{{ asset('storage/' . $post->image) }}"></div>
                <div class="container">
                    <div class="blog-bg-style1">
                        <a data-theme-color="{{ \App\Models\Others\Color::randomHex() }}" data-ani="slideinup" data-ani-delay="0.1s" href="{{ route('page.index', $post->category->slug_path) }}" class="category">
                            {{ $post->category->name }}
                        </a>
                        <h3 data-ani="slideinup" data-ani-delay="0.3s" class="box-title-50">

                            @include('partials.title')

                        </h3>
                        <div class="blog-meta" data-ani="slideinup" data-ani-delay="0.5s">

                            @include('partials.author')

                            @include('partials.date')

                        </div>
                        <p class="blog-text" data-ani="slideinup" data-ani-delay="0.7s">

                            {{ $post->description }}

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

                        @include('partials.image')

                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mt-2 mb-4">
        <div class="container container-full">
            <div class="row th-carousel" data-slide-show="5" data-xl-slide-show="4" data-ml-slide-show="3" data-lg-slide-show="3" data-md-slide-show="2" data-sm-slide-show="1">
                @foreach($editorPicks as $post)
                <div class="col-sm-6 col-xl-3 dark-theme">
                    <div class="blog-style3">
                        <div class="blog-img">

                            @include('partials.image')

                        </div>
                        <div class="blog-content">

                            @include('partials.category')

                            <h3 class="box-title-24">

                                @include('partials.title')

                            </h3>
                            <div class="blog-meta">

                                @include('partials.author')

                                @include('partials.date')

                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>

    <section class="mt-2 mb-4">
        <div class="container container-full">
            <div class="row gy-4">

                <div class="col-xxl-6">
                    <div class="row gy-4">
                        @foreach($smallPosts as $post)
                        <div class="col-md-6 dark-theme img-overlay2">
                            <div class="blog-style3">
                                <div class="blog-img">

                                    @include('partials.image')

                                </div>
                                <div class="blog-content">

                                    @include('partials.category')

                                    <h3 class="box-title-24">

                                        @include('partials.title')

                                    </h3>
                                    <div class="blog-meta">

                                        @include('partials.author')

                                        @include('partials.date')

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>


                @if($featuredPost)
                <div class="col-xxl-6">
                    <div class="dark-theme img-overlay2">
                        <div class="blog-style3">
                            <div class="blog-img">

                                @include('partials.image', ['post'=>$featuredPost])

                            </div>
                            <div class="blog-content">

                                @include('partials.category', ['post'=>$featuredPost])

                                <h3 class="box-title-30">

                                    @include('partials.title', ['post'=>$featuredPost])

                                </h3>
                                <div class="blog-meta">

                                    @include('partials.author', ['post'=>$featuredPost])

                                    @include('partials.date', ['post'=>$featuredPost])

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </section>

</x-layouts.guest>
