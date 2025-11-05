<a href="{{ route('page.index', $post->published_at->format('Y/m')) }}" title="{{ strtolower($post->published_at->format('j F Y, H:i A')) }}" class="post-date">
    <i class="fal fa-clock"></i>
    {{ $post->published_at->diffForHumans() }}
</a>
