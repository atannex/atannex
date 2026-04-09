<li class="{{ $item->descendants->isNotEmpty() ? '' : '' }}">
    <a href="{{ route('regions.show', ['path' => $item->slug_path]) }}">
        {{ $item->title ?? $item->name }}
    </a>

    @if(!empty($item->descendants) && $item->descendants->isNotEmpty())
    <ul class="sub-menu">
        @foreach($item->descendants as $child)

        <x-partials.page-item :item="$child" />

        @endforeach
    </ul>
    @endif
</li>
