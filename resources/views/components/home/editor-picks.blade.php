@props(['editorPicks'])

@if($editorPicks->isNotEmpty())
<div class="space-top">
    <div class="container">

        <div class="row align-items-center">
            <div class="col">
                <h2 class="sec-title has-line">
                    {{ __("Editor Picks") }}
                </h2>
            </div>
            <div class="col-auto">
                <div class="sec-btn">
                    <div class="icon-box">
                        <button data-slick-prev="#blog-slide7" class="slick-arrow default">
                            <i class="far fa-arrow-left"></i>
                        </button>
                        <button data-slick-next="#blog-slide7" class="slick-arrow default">
                            <i class="far fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row th-carousel" id="blog-slide7" data-slide-show="4" data-lg-slide-show="3" data-md-slide-show="2" data-sm-slide-show="2">

            @foreach($editorPicks as $post)
            <div class="col-sm-6 col-lg-4 col-xl-3 dark-theme">
                <div class="blog-style3">
                    <div class="blog-img">

                        @include('partials.image', ['post' => $post])

                    </div>
                    <div class="blog-content">

                        @include('partials.category', ['post' => $post])

                        <h3 class="box-title-20">

                            @include('partials.title', ['post' => $post])

                        </h3>

                        <div class="blog-meta">

                            @include('partials.author', ['post' => $post])

                            @include('partials.date', ['post' => $post])

                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
