@if($post->category)
<a href="{{ route('page.index', $post->category->slug) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
    {{ $post->category->name }}
</a>
@endif
