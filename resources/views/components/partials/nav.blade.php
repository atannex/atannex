<ul>

    @if(!empty($global['mainRegions']) && $global['mainRegions']->isNotEmpty())
    @php
    $mainRegion = $global['mainRegions']->first();
    @endphp

    <li class="menu-item{{ $global['mainRegions']->count() > 1 ? ' menu-item-has-children' : '' }}">
        <a href="{{ route('page.index', ['slug' => $mainRegion->slug]) }}">
            {{ $mainRegion->name }}
        </a>

        @if($global['mainRegions']->count() > 1)
        <ul class="sub-menu">
            @foreach($global['mainRegions']->skip(1) as $region)
            <x-partials.page-item :item="$region" />
            @endforeach
        </ul>
        @endif
    </li>
    @endif

    @if(!empty($global['categoryRegions']) && $global['categoryRegions']->isNotEmpty())
    @foreach($global['categoryRegions'] as $category)

    <x-partials.category-item :category="$category" />

    @endforeach
    @endif
</ul>
