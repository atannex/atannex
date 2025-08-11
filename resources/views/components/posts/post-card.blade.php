<div class="blog-style3">
    <div class="blog-img">

        @include('partials.image')

    </div>
    <div class="blog-content">

        @include('partials.category')

        <h3 class="box-title-18">

            @include('partials.title')

        </h3>
        <div class="blog-meta">
            <a href="#">
                <i class="fal fa-calendar-days"></i>
                {{ $post->published_at->format('d M, Y') }}
            </a>
        </div>
    </div>
</div>
