<div class="blog-tag">
    <h6 class="title">{{ __("Related Tag :") }}</h6>
    <div class="tagcloud">
        @forelse ($relatedTags as $tag)
        @php
        $firstPost = $tag->posts->first();
        @endphp

        @if ($firstPost && $firstPost->category)
        <a href="{{ route('page.index', ['slug' => $firstPost->category->slug_path]) }}">
            {{ $tag->name }}
        </a>
        @endif
        @empty
        <span>{{ __("No related tags found.") }}</span>
        @endforelse
    </div>
</div>
