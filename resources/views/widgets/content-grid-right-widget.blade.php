<div class="mb-10 col-xl-4 mt-35 mt-xl-0 sidebar-wrap">
    @foreach($widget->tabs as $tab)
    <h2 class="sec-title has-line">
        {{ $tab['title'] }}
    </h2>

    <div class="sidebar-area">

        @if($tab['content']->isNotEmpty())
        @php
        $firstPost = $tab['content']->first();
        $remainingPosts = $tab['content']->slice(1);
        @endphp


        <div class="mb-30">
            <div class="dark-theme img-overlay2">
                <div class="blog-style3">
                    <div class="blog-img">

                        @include('partials.image', ['post' => $firstPost])

                    </div>

                    <div class="blog-content">

                        @include('partials.category', ['post' => $firstPost])

                        <h3 class="box-title-24">

                            @include('partials.title', ['post' => $firstPost])

                        </h3>

                        <div class="blog-meta">

                            @include('partials.author', ['post' => $firstPost])

                            @include('partials.date', ['post' => $firstPost])

                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($remainingPosts->isNotEmpty())
        <div class="row gy-4">
            @foreach($remainingPosts as $post)
            <div class="col-xl-12 col-md-6 border-blog">
                <div class="blog-style2">
                    <div class="blog-img">

                        @include('partials.image', ['post' => $post])

                    </div>

                    <div class="blog-content">

                        @include('partials.category', ['post' => $post])

                        <h3 class="box-title-20">

                            @include('partials.title', ['post' => $post])

                        </h3>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        @endif
    </div>
    @endforeach
</div>
