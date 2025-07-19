<li class="{{ $category->children->isNotEmpty() ? 'menu-item-has-children' : '' }}">
    <a href="{{ route('page.index', ['slug' => $category->slug_path]) }}">
        {{ $category->name }}
    </a>

    @if ($category->children->isNotEmpty())
    <ul class="sub-menu">
        @foreach ($category->children as $child)
        @include('components.partials.item', ['category' => $child])
        @endforeach
    </ul>
    @endif
</li>
