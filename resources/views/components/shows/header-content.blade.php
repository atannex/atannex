<a data-theme-color="{{ \App\Models\Others\Color::randomHex() }}" href="{{ route('categories.show', ['path' => $module->post->category->slug_path]) }}" class="category">
    {{ $module->post->category->name }}
</a>

<h2 class="blog-title">
    {!! $module->post->title !!}
</h2>

<div class="blog-meta">
    <a class="author" href="{{ route('authors.show', $module->post->author->user->slug) }}">
        <i class="fas fa-user"></i>
        {{ __('By - ') . Str::title($module->post->author->user->name) }}
    </a>

    @include('partials.date', ['post' =>$module->post])

    <span>
        <i class="fas fa-book-open"></i>
        {{ $module->readingTime() }} {{ Str::plural('Min', $module->readingTime()) . __(" Read") }}
    </span>
</div>
