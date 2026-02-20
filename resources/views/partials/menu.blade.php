@php
$menu = displayGuestData($global['mainRegions']);
@endphp

<ul class="main-menu">

    <li>
        <a href="{{ $menu['homeRoute'] }}">
            {{ __('navigation.home') }}
        </a>
    </li>

    <li>
        <a href="{{ route('about') }}">
            {{ __('navigation.about') }}
        </a>
    </li>

    <li class="menu-item-has-children">
        <a href="{{ route('page.index', ['slug' => 'faq']) }}">
            {{ __('navigation.faqs') }}
        </a>
        <ul class="sub-menu">

            <li>
                <a href="{{ route('page.index', ['slug' => 'faq']) }}">
                    {{ __('navigation.faqs') }}
                </a>
            </li>

            <li>
                <a href="{{ route('page.index', ['slug' => 'testimonials']) }}">
                    {{ __('navigation.testimonials') }}
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item-has-children">
        <a href="{{ route('page.index', ['slug' => 'help-center']) }}">
            {{ __('navigation.help') }}
        </a>
        <ul class="sub-menu">
            @foreach ($menu['helpItems'] as $slug => $label)
            <li>
                <a href="{{ route('page.index', ['slug' => $slug]) }}">
                    {{ $label }}
                </a>
            </li>
            @endforeach
        </ul>
    </li>

    <li class="menu-item-has-children">
        <a href="{{ route('page.index', ['slug' => 'privacy']) }}">
            {{ __('navigation.policy') }}
        </a>

        <ul class="sub-menu">
            @foreach ($menu['policyItems'] as $slug => $label)
            <li>
                <a href="{{ route('page.index', ['slug' => $slug]) }}">
                    {{ $label }}
                </a>
            </li>
            @endforeach
        </ul>
    </li>

    <li>
        <a href="{{ route('contact') }}">
            {{ __('navigation.contact') }}
        </a>
    </li>

    @guest
    @if (Route::has('login'))
    <li class="menu-item-has-children">
        <a href="{{ route('login') }}">
            {{ __('navigation.login') }}
        </a>

        <ul class="sub-menu">
            <li>
                <a href="{{ route('login') }}">
                    {{ __('navigation.login') }}
                </a>
            </li>

            @if (Route::has('register'))
            <li>
                <a href="{{ route('register') }}">
                    {{ __('navigation.register') }}
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif
    @else
    <li class="menu-item-has-children">
        <a href="javascript:void(0)">
            {{ Auth::user()->name }}
        </a>

        <ul class="sub-menu">
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('navigation.logout') }}
                </a>
            </li>
        </ul>
    </li>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    @endguest


</ul>
