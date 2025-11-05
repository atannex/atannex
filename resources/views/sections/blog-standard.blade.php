<section class="th-blog-wrapper space-top space-extra-bottom">
    <div class="container">
        <div class="row">
            <div class="col-xxl-9 col-lg-8">
                @foreach ($posts as $post)
                <div class="th-blog blog-single has-post-thumbnail">

                    <div class="blog-img" data-overlay="black" data-opacity="4">

                        @include('partials.image',['class'=> 'blog-standard'])

                        @include('partials.category')

                    </div>

                    <div class="blog-content">
                        <div class="flex flex-wrap gap-3 blog-meta">

                            @include('partials.author')
                            @include('partials.date')

                            @php
                            $commentsCount = $post->comments->count();
                            $likesCount = $post->likesCount();
                            $ratingCount = $post->ratingCount();
                            $viewCount = $post->viewsCount();
                            @endphp

                            <span class="meta-item disabled-link">
                                <i class="far fa-comments"></i>
                                {{ trans_choice(
            ':count Comment|:count Comments',
            $commentsCount,
            ['count' => format_count($commentsCount)]
        ) }}
                            </span>

                            <span class="meta-item disabled-link like-btn">
                                <i class="far fa-thumbs-up"></i>
                                {{ trans_choice(
            ':count Like|:count Likes',
            $likesCount,
            ['count' => format_count($likesCount)]
        ) }}
                            </span>

                            <span class="meta-item disabled-link like-btn">
                                <i class="far fa-eye"></i>
                                {{ trans_choice(
            ':count View|:count Views',
            $viewCount,
            ['count' => format_count($viewCount)]
        ) }}
                            </span>

                            <span class="meta-item post-rating">
                                <i class="fas fa-star"></i>
                                <span class="rating-score">
                                    {{ number_format($post->averageRating(), 1) }}/5
                                    ({{ format_count($ratingCount, 1) }})
                                </span>
                            </span>

                        </div>


                        <h3 class="box-title-24">

                            @include('partials.title')

                        </h3>

                        <p class="blog-text">
                            {!! Str::limit($post->description, 200) !!}
                        </p>

                        <a href="{{ route('page.index', ['slug' => $post->slug_path ]) }}" class="th-btn style2">
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
