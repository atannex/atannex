<ul>
    @if ($global['home']->count() > 1)
    <li class="menu-item-has-children">
        <a href="{{ route('page.index', ['slug' => $global['home']->first()->slug]) }}">{{ __("Home") }}</a>
        <ul class="sub-menu">
            @foreach ($global['home'] as $page)
            <li>
                <a href="{{ route('page.index', ['slug' => $page->slug]) }}">
                    {{ $page->title }}
                </a>
            </li>
            @endforeach
        </ul>
    </li>
    @elseif ($global['home']->isNotEmpty())
    <li>
        <a href="{{ route('page.index', ['slug' => $global['home']->first()->slug]) }}">{{ __("Home") }}</a>
    </li>
    @endif

    @foreach ($global['navs'] as $category)
    @include('components.partials.item', ['category' => $category])
    @endforeach

</ul>
