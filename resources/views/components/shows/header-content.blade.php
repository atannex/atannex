<a data-theme-color="{{ \App\Models\Others\Color::randomHex() }}" href="{{ route('page.index', ['slug' => $module->post->category->slug_path]) }}" class="category">
    {{ $module->post->category->name }}
</a>

<h2 class="blog-title">
    {{ $module->post->title }}
</h2>

<div class="blog-meta">
    <a class="author" href="{{ route('page.index', $module->post->author->user->slug) }}">
        <i class="far fa-user"></i>
        {{ __('By - ') . Str::title($module->post->author->user->name) }}
    </a>

    <a href="{{ route('page.index', $module->post->author->user->slug) }}">
        <i class="fal fa-calendar-days"></i>
        {{ $module->created_at->diffForHumans() }}
    </a>

    <a href="{{ route('page.index', $module->post->author->user->slug) }}">
        <i class="far fa-comments"></i>
        ({{ format_count($module->post->comments->count()) }}
        {{ Str::plural('Comment', $module->post->comments->count()) }})
    </a>

    <span>
        <i class="far fa-book-open"></i>
        {{ $module->readingTime() }} {{ Str::plural('Min', $module->readingTime()) . __(" Read") }}
    </span>
</div>

<div class="mb-40 blog-img">
    <img class="img-fluid image-show" src="{{ asset('storage/' . $module->post->image) }}" alt="{{ config('app.name') }}">
</div>
