<div class="col-xl-3">
    <div class="row gy-4">
        @foreach ($widget->tabs as $tab)
        @foreach ($tab['content'] as $post)
        <div class="col-xl-12 col-sm-6 border-blog dark-theme img-overlay2">
            <div class="blog-style3">
                <div class="blog-img">
                    <img class="img-fluid top-stories-left-sidebar" src="{{ asset('storage/' . $post->image) }}" alt="{{ config('app.name') }}">
                </div>
                <div class="blog-content">
                    <a data-theme-color="{{ \App\Models\Others\Color::randomHex() }}" href="#" class="category">
                        {{ $post->category->name }}
                    </a>
                    <h3 class="box-title-22">
                        <a class="hover-line" href="#">
                            {{ Str::limit($post->title, 60) }}
                        </a>
                    </h3>
                    <div class="blog-meta">
                        <a href="#">
                            <i class="far fa-user"></i> {{ __("By - ") . $post->author->user->name }}
                        </a>
                        <a href="#">
                            <i class="fal fa-calendar-days"></i> {{ $post->published_at->format('d M, Y') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endforeach
    </div>
</div>

