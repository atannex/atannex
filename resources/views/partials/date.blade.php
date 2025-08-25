<a href="{{ route('page.index', $post->published_at->format('Y/m'))}}">
    <i class="fal fa-calendar-days"></i>
    {{ $post->published_at->format('d M, Y') }}
</a>
