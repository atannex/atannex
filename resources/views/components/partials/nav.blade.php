<ul>

    @if(!empty($global['mainRegions']) && $global['mainRegions']->isNotEmpty())
    @php $mainRegion = $global['mainRegions']->first(); @endphp

    <li class="menu-item-has-children">
        <a href="{{ route('page.index', ['slug' => $mainRegion->slug]) }}">
            {{ $mainRegion->name }}
        </a>

        <ul class="sub-menu">
            @foreach($global['mainRegions'] as $region)

            <x-partials.page-item :item="$region" />

            @endforeach
        </ul>
    </li>
    @endif
    @if(!empty($global['categoryRegions']) && $global['categoryRegions']->isNotEmpty())
    @foreach($global['categoryRegions'] as $category)

    <x-partials.category-item :category="$category" />

    @endforeach
    @endif
</ul>
