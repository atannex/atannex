<div class="mt-4 col-xl-5 mt-xl-0">
    @if ($posts)
    <div class="blog-style1 style-big">
        <div class="blog-img">

            @include('partials.image', ['post' => $posts])

            @include('partials.category', ['post' => $posts])

        </div>

        <h3 class="box-title-22">

            @include('partials.title', ['post' => $posts])

        </h3>

        <div class="blog-meta">

            @include('partials.author', ['post' => $posts])

            @include('partials.date', ['post' => $posts])

        </div>
    </div>
    @endif
</div>
