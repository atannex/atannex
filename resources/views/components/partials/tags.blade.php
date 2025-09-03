<h3 class="widget_title">
    {{ __("Popular Tags") }}
</h3>
<div class="tagcloud">
    @forelse ($global['popularTags'] as $tag)
    @php
    $firstPost = $tag->posts->first();
    $slug = $firstPost?->pivot->slug_path;
    @endphp

    @if($slug)
    <a href="{{ route('page.index', ['slug' => $slug]) }}" title="{{ $tag->name }}">
        {{ $tag->name }}
    </a>
    @else
    <span title="{{ $tag->name }}">{{ $tag->name }}</span>
    @endif
    @empty
    <p>{{ __("No popular tags available.") }}</p>
    @endforelse
</div>
