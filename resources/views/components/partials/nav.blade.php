<ul>
    @if ($global['mainRegions']->isNotEmpty())
        <li class="menu-item-has-children">
            <a href="{{ route('page.index', ['slug' => $global['mainRegions']->first()->slug]) }}">
                {{ $global['mainRegions']->first()->name }}
            </a>

            <ul class="sub-menu">
                @foreach ($global['mainRegions'] as $region)

                    <x-partials.page-item :item="$region" />

                @endforeach
            </ul>
        </li>
    @endif

    @foreach ($global['categoryRegions'] as $category)

        <x-partials.category-item :category="$category" />

    @endforeach
</ul>
