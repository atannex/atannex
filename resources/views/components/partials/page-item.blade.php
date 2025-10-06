<li class="{{ $item->childrenRecursive->isNotEmpty() ? 'menu-item-has-children' : '' }}">
    <a href="{{ route('page.index', ['slug' => $item->slug_path]) }}">
        {{ $item->title ?? $item->name }}
    </a>

    @if(!empty($item->childrenRecursive) && $item->childrenRecursive->isNotEmpty())
    <ul class="sub-menu">
        @foreach($item->childrenRecursive as $child)

        <x-partials.page-item :item="$child" />

        @endforeach
    </ul>
    @endif
</li>
