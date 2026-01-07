<a href="{{ route('page.index', $post->published_at->format('Y/m')) }}" title="{{ $post->published_at->format('j F Y, H:i A') }}" class="post-date">
    <i class="fal fa-calendar"></i>
    {{ $post->published_at->format('D j M, Y') }}
</a>
