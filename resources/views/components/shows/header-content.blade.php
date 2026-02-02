<a data-theme-color="{{ \App\Models\Others\Color::randomHex() }}" href="{{ route('page.index', ['slug' => $module->post->category->slug_path]) }}" class="category">
    {{ $module->post->category->name }}
</a>

<h2 class="blog-title">
    {{ $module->post->title }}
</h2>

<div class="blog-meta">
    <a class="author" href="{{ route('page.index', $module->post->author->user->slug) }}">
        <i class="fas fa-user"></i>
        {{ __('By - ') . Str::title($module->post->author->user->name) }}
    </a>

    <a href="{{ route('page.index', $module->post->author->user->slug) }}">
        <i class="fas fa-calendar-days"></i>
        {{ $module->created_at->diffForHumans() }}
    </a>

    @auth
    <a href="{{ route('page.index', $module->post->author->user->slug) }}">
        <i class="fas fa-comments"></i>
        ({{ format_count($module->post->comments_count) }}
        {{ Str::plural('Comment', $module->post->comments_count) }})
    </a>
    @endauth

    <span>
        <i class="fas fa-book-open"></i>
        {{ $module->readingTime() }} {{ Str::plural('Min', $module->readingTime()) . __(" Read") }}
    </span>
</div>
