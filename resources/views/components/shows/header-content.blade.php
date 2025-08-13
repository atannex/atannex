<a data-theme-color="{{ \App\Models\Others\Color::randomHex() }}" href="{{ route('page.index', ['slug' => $module->post->category->slug_path]) }}" class="category">
    {{ $module->post->category->name }}
</a>

<h2 class="blog-title">{{ $module->post->title }}</h2>

<div class="blog-meta">
    <a class="author" href="{{ route('page.index', $module->post->author->user->slug) }}">
        <i class="far fa-user"></i>
        {{ __('By - ') . $module->post->author->user->name }}
    </a>

    <a href="{{ route('page.index', ['slug' => $module->post->category->slug_path]) }}">
        <i class="fal fa-calendar-days"></i>
        {{ $module->post->created_at->format('d F, Y') }}
    </a>

    <a href="blog-details.html">
        <i class="far fa-comments"></i>
        Comments ({{ $module->post->comments_count ?? 0 }})
    </a>

    {{-- <span>
        <i class="far fa-book-open"></i>
        {{ $module->readingTime() }} {{ Str::plural('Min', $module->readingTime()) }} Read
    </span> --}}
</div>

<div class="mb-40 blog-img">
    <img src="{{ asset('storage/' . $module->post->image) }}" alt="{{ $module->post->title }}">
</div>
