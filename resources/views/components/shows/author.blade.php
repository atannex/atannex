<div class="blog-author">
    <div class="auhtor-img">
        <img src="{{ asset('storage/' . $module->post->author->user->image) }}" alt="{{ $module->post->author->user->name }}" class="rounded-circle img-fluid author-image">
    </div>
    <div class="media-body">
        <div class="author-top">
            <div>
                <h3 class="author-name">
                    <a class="text-inherit" href="{{ route('page.index', ['slug' => $module->post->author->user->slug]) }}">
                        {{ Str::title($module->post->author->user->name) }}
                    </a>
                </h3>
                <span class="author-desig">
                    {{ $module->post->author->user->getRoleNames()->first() }}
                </span>
            </div>
            @if(!empty($medias))
            <div class="gap-2 social-links d-flex">
                @foreach($medias as $media)
                <a href="{{ $media['url'] }}" target="_blank" rel="noopener noreferrer" class="d-inline-flex align-items-center justify-content-center rounded-circle social-icon" style="width: 2.5rem; height: 2.5rem; background-color: {{ $media['color'] }};" aria-label="{{ $media['name'] ?? 'Social link' }}">
                    <i class="{{ $media['icon'] }} text-white"></i>
                </a>
                @endforeach
            </div>
            @endif
        </div>
        @if(!empty($module->post->author->user->bio))
        <p class="mt-3 author-text">
            {!! $module->post->author->user->bio !!}
        </p>
        @endif
    </div>
</div>
