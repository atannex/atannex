<section class="mb-4">
    <div class="container">
        @foreach ($section->tabs as $tab)
        @php
        $posts = get_posts_from_tabs([$tab]);
        $featuredPost = $posts->first();
        $sidePosts = $posts->skip(1)->take(2);
        $highlightPost = $posts->skip(3)->first();
        @endphp

        <div class="row">
            <div class="col-xl-8">
                <h2 class="sec-title has-line">
                    {{ $tab['title'] }}
                </h2>
            </div>
            <div class="col-xl-4">
                <div class="d-none d-xl-block">
                    <h2 class="sec-title has-line">
                        {{ $tab['sub_title'] }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3">
                <div class="row gy-4">
                    @foreach ($sidePosts as $post)
                    <div class="col-xl-12 col-sm-6 border-blog">
                        <div class="blog-style1">
                            <div class="blog-img">

                                @include('partials.image')

                                @include('partials.category')

                            </div>
                            <h3 class="box-title-22">

                                @include('partials.title')

                            </h3>
                            <div class="blog-meta">

                                @include('partials.author')

                                @include('partials.date')

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 col-xl-5 mt-xl-0">
                @if ($featuredPost)
                <div class="blog-style1 style-big">
                    <div class="blog-img">

                        @include('partials.image')

                        @include('partials.category')

                    </div>
                    <h3 class="box-title-22">

                        @include('partials.title')

                    </h3>
                    <div class="blog-meta">

                        @include('partials.author')

                        @include('partials.date')

                    </div>
                </div>
                @endif
            </div>

            <div class="col-xl-4 mt-35 mt-xl-0">
                <div class="d-block d-xl-none">
                    <h2 class="sec-title has-line">
                        {{ $tab['sub_title'] }}
                    </h2>
                </div>

                <div class="row gy-4">
                    <div class="col-xl-12 col-md-6 border-blog">
                        @if ($highlightPost)
                        <div class="blog-style2">
                            <div class="blog-img">

                                @include('partials.image')

                            </div>
                            <div class="blog-content">

                                @include('partials.category')

                                <h3 class="box-title-20">

                                    @include('partials.title')

                                </h3>
                                <div class="blog-meta">

                                    @include('partials.date')

                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
