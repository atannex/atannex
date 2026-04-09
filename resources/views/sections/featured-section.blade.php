<section class="mb-4">
    <div class="container">
        @foreach ($section->tabs as $tab)
        @php
        $posts = get_posts_from_tabs([$tab]);
        $featuredPost = $posts->first();
        $sidePosts = $posts->skip(1)->take(2);
        $highlightPosts = $posts->skip(3)->take(4);
        @endphp

        <div class="mb-3 row align-items-center">
            <div class="col-xl-8">
                <h2 class="sec-title has-line">{{ $tab['title'] }}</h2>
            </div>
            <div class="col-xl-4 d-none d-xl-block">
                <h2 class="sec-title has-line">{{ $tab['sub_title'] }}</h2>
            </div>
        </div>

        <div class="row">

            @include('partials.featured.side', ['posts' => $sidePosts])

            @include('partials.featured.featured', ['posts' => $featuredPost])


            <div class="col-xl-4 mt-35 mt-xl-0">
                <div class="mb-3 d-block d-xl-none">
                    <h2 class="sec-title has-line">{{ $tab['sub_title'] }}</h2>
                </div>

                @include('partials.featured.highlight', ['posts' => $highlightPosts])
            </div>
        </div>
        @endforeach
    </div>
</section>
