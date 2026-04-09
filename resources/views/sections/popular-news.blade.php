<section class="space-bottom">
    <div class="container">

        @foreach ($section->tabs as $tab)
        @php
        $posts = get_posts_from_tabs([$tab]);
        $featuredPost = $posts->first();
        $sidePosts = $posts->skip(1)->take(2);
        $highlightPosts = $posts->skip(3)->take(2);
        @endphp

        <div class="row">
            <div class="col-xl-8">

                @if ($featuredPost)
                <h2 class="sec-title has-line">
                    {{ $tab['title'] }}
                </h2>

                <div class="mb-4">
                    <div class="dark-theme img-overlay2 space-40">
                        <div class="blog-style3">
                            <div class="blog-img">

                                @include('partials.image', ['post' => $featuredPost])

                            </div>
                            <div class="blog-content">

                                @include('partials.category', ['post' => $featuredPost])

                                <h3 class="box-title-40">

                                    @include('partials.title', ['post' => $featuredPost])

                                </h3>

                                <div class="blog-meta">

                                    @include('partials.author', ['post' => $featuredPost])

                                    @include('partials.date', ['post' => $featuredPost])

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif


                @if ($highlightPosts->isNotEmpty())
                <div class="row gy-4">
                    @foreach ($highlightPosts as $blog)
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


                @if ($sidePosts->isNotEmpty())
                <h2 class="mt-5 sec-title has-line">
                    {{ $tab['sub_title'] }}
                </h2>
                <div class="mbn-24">
                    @foreach ($sidePosts as $blog)
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

                                <a href="{{ route('posts.show', ['slug' => $blog->slug_path]) }}" class="th-btn style2">
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

                @foreach ($section->widgets as $widget)
                @include("widgets.{$widget->slug}", ['widget' => $widget])
                @endforeach

            </div>
        @endforeach
    </div>
</section>
