<section class="section section--border">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section__title-wrap">
                    <h2 class="section__title">{{ __("Latest") }}</h2>
                    <a href="{{ route('catalog') }}" class="section__view section__view--carousel">View All</a>
                </div>
            </div>

            <div class="col-12">
                <div class="section__carousel splide splide--content">
                    <div class="splide__arrows">
                        <button class="splide__arrow splide__arrow--prev" type="button">
                            <i class="ti ti-chevron-left"></i>
                        </button>
                        <button class="splide__arrow splide__arrow--next" type="button">
                            <i class="ti ti-chevron-right"></i>
                        </button>
                    </div>

                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach ($videos as $video)
                            <li class="splide__slide">
                                <div class="item item--carousel">
                                    <div class="item__cover">
                                        <div class="d-flex justify-content-center">
                                            <img src="{{ asset('storage/' . $video->image) }}" alt="{{ config('app.name') }}" class="img-fluid video-thumbnail">
                                        </div>
                                        <a href="{{ route('video.show', ['slug' => $video->slug]) }}" class="item__play">
                                            <i class="ti ti-player-play-filled"></i>
                                        </a>
                                        @if($video->rating)
                                        <span class="item__rate item__rate--green">{{ number_format($video->rating, 1) }}</span>
                                        @endif
                                        <button class="item__favorite" type="button">
                                            <i class="ti ti-bookmark"></i>
                                        </button>
                                    </div>
                                    <div class="item__content">
                                        <h3 class="item__title">
                                            <a href="{{ route('video.show', ['slug' => $video->slug]) }}">{{ $video->title }}</a>
                                        </h3>
                                        <span class="item__category">
                                            <a href="{{ route('page.index', $video->category->slug_path) }}">{{ $video->category->name }}</a>
                                        </span>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
