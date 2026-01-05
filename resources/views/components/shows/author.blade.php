<div class="blog-author">
    <div class="auhtor-img">
        <img src="{{ asset('storage/' . $post->author->user->image) }}" alt="{{ $post->author->user->name }}" class="rounded-circle img-fluid author-image">
    </div>
    <div class="media-body">
        <div class="author-top">
            <div>
                <h3 class="author-name name-text">
                    <a class="text-inherit" href="{{ route('page.index', ['slug' => $post->author->user->slug]) }}">
                        {{ Str::title($post->author->user->name) }}
                    </a>
                </h3>
                <span class="author-desig">{!! $post->author->user->getRoleNames()->first() !!}</span>
            </div>
            <div class="gap-2 social-links d-flex">
                @foreach($medias as $media)
                <a href="{{ $media['url'] }}" target="_blank" rel="noopener" class="d-inline-flex align-items-center justify-content-center rounded-circle me-1 social-icon" style="width: 2.5rem; height: 2.5rem; background-color: {{ $media['color'] }};">
                    <i class="{{ $media['icon'] }} text-white"></i>
                </a>
                @endforeach
            </div>
        </div>
        <p class="mt-3 author-text">{!! $post->author->user->bio !!}</p>
    </div>
</div>
