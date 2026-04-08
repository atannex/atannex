<li class="menu-item {{ $category->descendants->isNotEmpty() ? 'menu-item-has-children' : '' }}">
    <a href="{{ route('categories.show', ['path' => $category->slug_path]) }}">
        {{ $category->name }}
    </a>

    @if(!empty($category->descendants) && $category->descendants->isNotEmpty())
    <ul class="sub-menu">
        @foreach($category->descendants as $child)

        <x-partials.category-item :category="$child" />

        @endforeach
    </ul>
    @endif
</li>
