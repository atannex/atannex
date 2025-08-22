<section class="th-blog-wrapper space-top space-extra-bottom">
    <div class="container">
        <div class="row">
            <div class="col-xxl-9 col-lg-8">
                @foreach ($posts as $post)
                <div class="th-blog blog-single has-post-thumbnail">

                    <div class="blog-img" data-overlay="black" data-opacity="4">

                        @include('partials.image')

                        @include('partials.category')

                    </div>

                    <div class="blog-content">
                        <div class="flex flex-wrap gap-3 blog-meta">

                            @include('partials.author')

                            <a href="#">
                                <i class="fal fa-calendar-days"></i> {{ $post->published_at->format('d F, Y') }}
                            </a>
                            <a href="#">
                                <i class="far fa-comments"></i>
                                {{ trans_choice(':count Comment|:count Comments', $post->comment_count ?? 0, ['count' => $post->comment_count ?? 0]) }}
                            </a>
                            <a href="#">
                                <i class="far fa-thumbs-up"></i>
                                {{ trans_choice(':count Like|:count Likes', $post->like_count ?? 0, ['count' => $post->like_count ?? 0]) }}
                            </a>
                            <a href="#">
                                <i class="far fa-star"></i>
                                {{ number_format($post->average_rating ?? 0, 1) }} / 5
                            </a>
                        </div>

                        <h3 class="box-title-24">

                            @include('partials.title')

                        </h3>

                        <p class="blog-text">
                            {!! Str::limit($post->description, 200) !!}
                        </p>

                        <a href="javascript:void(0)" class="th-btn style2">
                            {{ __("Read More") }}
                            <i class="fas fa-arrow-up-right ms-2"></i>
                        </a>
                    </div>
                </div>
                @endforeach

                <x-partials.pagination :paginator="$posts" />

            </div>

            <div class="col-xxl-3 col-lg-4 sidebar-wrap">

                @include('partials.aside')

            </div>
        </div>
    </div>
</section>
