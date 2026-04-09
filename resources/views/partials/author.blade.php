<a href="{{ route('authors.show', $post->author->user->slug) }}" title="{{ Str::lower($post->author->user->name) }}">

    <img src="{{ $post->author->user->image
        ? asset('storage/' . $post->author->user->image)
        : asset('logo.jpg') }}" alt="{{ Str::lower($post->author->user->name) }}" class="author-avatar">

    {{ Str::limit(Str::lower($post->author->user->name), 10) }}
</a>
