<div class="">
    <div class="container">

        @foreach($section->tabs as $index => $tab)
        <div class="mb-3 row align-items-center">
            <div class="col">
                <h2 class="sec-title has-line">{{ $tab['title'] }}</h2>
            </div>
            <div class="col-auto">
                <div class="sec-btn">
                    <div class="icon-box">
                        <button data-slick-prev="#small-{{ $section->id }}-{{ $index }}" class="slick-arrow default">
                            <i class="far fa-arrow-left"></i>
                        </button>
                        <button data-slick-next="#small-{{ $section->id }}-{{ $index }}" class="slick-arrow default">
                            <i class="far fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row th-carousel" id="small-{{ $section->id }}-{{ $index }}" data-slide-show="4" data-lg-slide-show="3" data-md-slide-show="2" data-sm-slide-show="2">

            @foreach($tab['content'] as $post)
            <div class="col-sm-6 col-xl-4">
                <div class="blog-style1">
                    <div class="blog-img">

                        @include('partials.image')

                        @include('partials.category')

                    </div>

                    @include('partials.title')


                    <div class="blog-meta">

                        @include('partials.author')

                        <a href="#">
                            <i class="fal fa-calendar-days"></i>{{ $post->published_at->format('d M, Y') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
        @endforeach

    </div>
</div>
