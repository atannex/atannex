<section class="th-blog-wrapper space-top space-extra-bottom">
    <div class="container">
        <div class="row">
            <div class="col-xxl-9 col-lg-8">
                @foreach ($posts as $blog)
                <div class="th-blog blog-single has-post-thumbnail">

                    <div class="blog-img" data-overlay="black" data-opacity="4">

                        @include('partials.image')
                        
                        <a href="{{ route('page.index', $blog->category->slug) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
                            {{ $blog->category->name }}
                        </a>
                    </div>

                    <div class="blog-content">
                        <div class="flex flex-wrap gap-3 blog-meta">
                             @include('partials.author')
                            <a href="#">
                                <i class="fal fa-calendar-days"></i> {{ $blog->published_at->format('d F, Y') }}
                            </a>
                            <a href="#">
                                <i class="far fa-comments"></i>
                                {{ trans_choice(':count Comment|:count Comments', $blog->comment_count ?? 0, ['count' => $blog->comment_count ?? 0]) }}
                            </a>
                            <a href="#">
                                <i class="far fa-thumbs-up"></i>
                                {{ trans_choice(':count Like|:count Likes', $blog->like_count ?? 0, ['count' => $blog->like_count ?? 0]) }}
                            </a>
                            <a href="#">
                                <i class="far fa-star"></i>
                                {{ number_format($blog->average_rating ?? 0, 1) }} / 5
                            </a>
                        </div>

                        <h2 class="blog-title box-title-30">
                            <a href="#">{{ $blog->title }}</a>
                        </h2>

                        <p class="blog-text">
                            {!! Str::limit($blog->description, 200) !!}
                        </p>

                        <a href="#" class="th-btn style2">
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
