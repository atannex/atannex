<div class="related-post-wrapper pt-30 mb-30">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="sec-title has-line">{{ __("Related Posts") }}</h2>
        </div>
        <div class="col-auto">
            <div class="sec-btn">
                <div class="icon-box">
                    <button data-slick-prev="#related-post-slide" class="slick-arrow default">
                        <i class="far fa-arrow-left"></i>
                    </button>
                    <button data-slick-next="#related-post-slide" class="slick-arrow default">
                        <i class="far fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row slider-shadow th-carousel" id="related-post-slide" data-slide-show="3" data-lg-slide-show="2" data-md-slide-show="2" data-sm-slide-show="2">

        @forelse ($relatedPosts as $post)
        <div class="col-sm-6 col-xl-4">
            <div class="blog-style1">
                <div class="blog-img">

                    @include('partials.image',['class'=> 'category-3-column'])

                    @include('partials.category')

                </div>

                <h3 class="box-title-22">

                    @include('partials.title')

                </h3>

                <div class="blog-meta">

                    @include('partials.author')

                    @include('partials.date')

                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <p>{{ __("No posts available.") }}</p>
        </div>
        @endforelse

    </div>
</div>
