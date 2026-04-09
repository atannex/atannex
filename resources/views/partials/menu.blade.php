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

    {{-- FAQ / Documents --}}
    <li class="menu-item-has-children">
        <a href="{{ route('documents.list', ['type' => 'faq']) }}">
            {{ __('navigation.faqs') }}
        </a>

        <ul class="sub-menu">
            <li>
                <a href="{{ route('documents.list', ['type' => 'faq']) }}">
                    {{ __('navigation.faqs') }}
                </a>
            </li>

            <li>
                <a href="{{ route('documents.list', ['type' => 'testimonials']) }}">
                    {{ __('navigation.testimonials') }}
                </a>
            </li>
        </ul>
    </li>

    {{-- Help Center --}}
    <li class="menu-item-has-children">
        <a href="{{ route('documents.list', ['type' => 'help-center']) }}">
            {{ __('navigation.help') }}
        </a>

        <ul class="sub-menu">
            @foreach ($menu['helpItems'] as $type => $label)
            <li>
                <a href="{{ route('documents.list', ['type' => $type]) }}">
                    {{ $label }}
                </a>
            </li>
            @endforeach
        </ul>
    </li>

    {{-- Policy / Legal --}}
    <li class="menu-item-has-children">
        <a href="{{ route('documents.list', ['type' => 'privacy']) }}">
            {{ __('navigation.policy') }}
        </a>

        <ul class="sub-menu">
            @foreach ($menu['policyItems'] as $type => $label)
            <li>
                <a href="{{ route('documents.list', ['type' => $type]) }}">
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

    {{-- AUTH --}}
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
