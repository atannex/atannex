<a href="{{ route('archive.month', ['year' => $post->published_at->format('Y'), 'month' => $post->published_at->format('m')]) }}" title="{{ $post->published_at->format('j F Y, H:i A') }}" class="post-date text-light">
    <i class="fal fa-calendar"></i>
    {{ $post->published_at->format('D j M, Y') }}
</a>
