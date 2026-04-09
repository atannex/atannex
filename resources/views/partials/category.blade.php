@if($post->category)
@php($data = category_display_data($post->category))
<a href="{{ route('categories.show', $post->category->slug_path) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
    {{ $data['label'] }}
</a>
@endif
