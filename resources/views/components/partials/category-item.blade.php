<li class="menu-item {{ $category->children->isNotEmpty() ? 'menu-item-has-children' : '' }}">
    <a href="{{ route('page.index', ['slug' => $category->slug_path]) }}">
        {{ $category->name }}
    </a>

    @if(!empty($category->children) && $category->children->isNotEmpty())
    <ul class="sub-menu">
        @foreach($category->children as $child)

        <x-partials.category-item :category="$child" />

        @endforeach
    </ul>
    @endif
</li>
