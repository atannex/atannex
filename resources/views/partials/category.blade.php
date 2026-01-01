@if($post->category)
@php($data = category_display_data($post->category))
<a href="{{ route('page.index', $post->category->slug_path) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
    {{ $data['label'] }}
</a>
@endif
