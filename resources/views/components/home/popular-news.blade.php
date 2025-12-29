@props(['popularPosts'])

@if($popularPosts->isNotEmpty())
<div class="mb-5">
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
</div>
@endif
