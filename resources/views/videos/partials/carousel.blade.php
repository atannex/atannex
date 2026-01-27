<section class="home home--hero">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="hero splide splide--hero">
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
                                <div class="hero__slide" data-bg="{{ asset('storage/' . $video->image) }}">
                                    <div class="hero__content">
                                        <h2 class="hero__title">{{ $video->title }}</h2>
                                        <p class="hero__text">{{ $video->description }}</p>
                                        <p class="hero__category">
                                            <a href="{{ route('page.index', $video->category->slug_path) }}">{{ $video->category->name }}</a>
                                        </p>
                                        <div class="hero__actions">
                                            <a href="{{ route('video.show', $video->slug) }}" class="hero__btn">
                                                <span>{{ __("Watch now") }}</span>
                                            </a>
                                        </div>
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
