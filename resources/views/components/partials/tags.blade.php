<h3 class="widget_title">
    {{ __("Popular Tags") }}
</h3>
<div class="tagcloud">
    @forelse ($global['popularTags'] as $tag)
    @if($tag->slug)
    <a href="{{ route('page.index', ['slug' => $tag->slug]) }}" title="{{ $tag->name }}">
        {{ $tag->name }}
    </a>
    @else
    <span title="{{ $tag->name }}">{{ $tag->name }}</span>
    @endif
    @empty
    <p>{{ __("No popular tags available.") }}</p>
    @endforelse
</div>
