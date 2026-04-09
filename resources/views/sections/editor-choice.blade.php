<div class="space dark-theme" data-bg-src="{{ asset('assets/img/bg/blog_bg_1.jpg') }}">
    <div class="container">
        @foreach ($section->tabs as $tab)
        <h2 class="text-center sec-title has-line">{{ $tab['title'] }}</h2>
        @php
        $featuredPosts = $tab['content']->take(2);
        $otherPosts = $tab['content']->skip(2);
        @endphp

        @if ($featuredPosts->isNotEmpty())
        <div class="mb-4 row gy-4">
            @foreach ($featuredPosts as $post)
            <div class="col-lg-6">
                <div class="blog-style3">
                    <div class="blog-img">

                        @include('partials.image')

                    </div>
                    <div class="blog-content">

                        @include('partials.category', ['post' => $post])

                        <h3 class="box-title-30">

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
        @endif

        @if ($otherPosts->isNotEmpty())
        <div class="row gy-4">
            @foreach ($otherPosts as $post)
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="blog-style1">
                    <div class="blog-img">

                        @include('partials.image')

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
        @endif
        @endforeach
    </div>
</div>
