<div class="blog-style3">
    <div class="blog-img">
        <img class="img-fluid" src="{{ asset('storage/' . $post->image) }}" alt="{{ config('app.name') }}">
    </div>
    <div class="blog-content">
        <a data-theme-color="{{ \App\Models\Others\Color::randomHex() }}" href="#" class="category">
            {{ $post->category->name }}
        </a>
        <h3 class="box-title-18">
            <a class="hover-line" href="#">
                {{ Str::limit($post->title, 60) }}
            </a>
        </h3>
        <div class="blog-meta">
            <a href="#">
                <i class="fal fa-calendar-days"></i>
                {{ $post->published_at->format('d M, Y') }}
            </a>
        </div>
    </div>
</div>
