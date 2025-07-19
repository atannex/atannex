<div class="space-bottom">
    <div class="container">

        @foreach($section->tabs as $index => $tab)
        <div class="row align-items-center mb-3">
            <div class="col">
                <h2 class="sec-title has-line">{{ $tab['title'] }}</h2>
            </div>
            <div class="col-auto">
                <div class="sec-btn">
                    <div class="icon-box">
                        <button data-slick-prev="#large-{{ $section->id }}-{{ $index }}" class="slick-arrow default">
                            <i class="far fa-arrow-left"></i>
                        </button>
                        <button data-slick-next="#large-{{ $section->id }}-{{ $index }}" class="slick-arrow default">
                            <i class="far fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row th-carousel" id="large-{{ $section->id }}-{{ $index }}" data-slide-show="4" data-lg-slide-show="3" data-md-slide-show="2" data-sm-slide-show="2">
            @foreach($tab['content'] as $post)
            <div class="col-sm-6 col-xl-4">
                <div class="blog-style1">
                    <div class="blog-img">
                        <img class="img-fluid large-carousel-image" src="{{ asset('storage/' . $post->image) }}" alt="{{ config('app.name') }}">
                        <a href="{{ route('page.index', $post->category->slug_path) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
                            {{ $post->category->name }}
                        </a>
                    </div>
                    <h3 class="box-title-22">
                        <a href="#" class="hover-line">
                            {{ Str::limit($post->title, 60) }}
                        </a>
                    </h3>
                    <div class="blog-meta">
                        <a href="#">
                            <i class="far fa-user"></i>{{ __('By - ') . $post->author->user->name }}
                        </a>
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
