<a href="{{ route('page.index', $post->author->user->slug) }}" title="{{ Str::lower($post->author->user->name) }}">

    <img src="{{ $post->author->user->image
        ? asset('storage/' . $post->author->user->image)
        : asset('assets/img/user_comment_img.jpg') }}" alt="{{ Str::lower($post->author->user->name) }}" class="author-avatar">

    {{ Str::limit(Str::lower($post->author->user->name), 15) }}
</a>
