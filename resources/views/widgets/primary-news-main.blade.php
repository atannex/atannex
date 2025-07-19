<div class="col-xl-8">
    @foreach($widget->tabs as $tab)
    <h2 class="sec-title has-line">{{ $tab['title'] }}</h2>
    <div class="row gy-4">
        @foreach($tab['content'] as $post)
        <div class="col-sm-6 border-blog two-column">
            <div class="blog-style1">
                <div class="blog-img">
                    <img class="img-fluid large-carousel-image" src="{{ asset('storage/' . $post->image) }}" alt="{{ config('app.name') }}">

                    <a href="{{ route('page.index', $post->category->slug_path) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
                        {{ $post->category->name }}
                    </a>
                </div>

                <h3 class="box-title-24">
                    <a class="hover-line" href="">
                        {{ Str::limit($post->title, 70) }}
                    </a>
                </h3>

                <div class="blog-meta">
                    <a href="">
                        <i class="far fa-user"></i>{{ __("By - ") . $post->author->user->name }}
                    </a>
                    <a href="">
                        <i class="fal fa-calendar-days"></i>{{ $post->published_at->format('d M, Y') }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
</div>

