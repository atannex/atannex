<div class="col-xl-3">
    <div class="row gy-4">
        @foreach ($widget->tabs as $tab)
        @foreach ($tab['content'] as $post)
        <div class="col-xl-12 col-sm-6 border-blog dark-theme img-overlay2">
            <div class="blog-style3">
                <div class="blog-img">

                    @include('partials.image')

                </div>
                <div class="blog-content">

                    @include('partials.category')

                    <h3 class="box-title-22">

                        @include('partials.title')

                    </h3>
                    <div class="blog-meta">

                        @include('partials.author')

                        <a href="#">
                            <i class="fal fa-calendar-days"></i> {{ $post->published_at->format('d M, Y') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endforeach
    </div>
</div>
