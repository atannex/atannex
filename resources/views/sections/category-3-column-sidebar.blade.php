<section class="space">
    <div class="container">
        <div class="row gy-30">
            @foreach ($posts as $post)
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="blog-style1">
                    <div class="blog-img" data-overlay="black" data-opacity="4">

                       @include('partials.image')

                        @include('partials.category')

                    </div>

                    <h3 class="box-title-24">

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
            @endforeach
        </div>

        <x-partials.pagination :paginator="$posts" />
    </div>
</section>
