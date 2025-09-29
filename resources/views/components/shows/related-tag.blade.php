<div class="blog-tag">
    <h6 class="title">{{ __("Related Tag :") }}</h6>
    <div class="tagcloud">
        @forelse ($relatedTags as $tag)
        @if ($tag->posts->first() && $tag->posts->first()->category)
        <a href="{{ route('page.index', ['slug' => $tag->slug]) }}">
            {{ $tag->name }}
        </a>
        @endif
        @empty
        <span>{{ __("No related tags found.") }}</span>
        @endforelse
    </div>
</div>
