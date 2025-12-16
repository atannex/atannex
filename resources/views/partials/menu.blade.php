@php
$menu = displayGuestData($global);
@endphp

<ul>
    <li>
        <a href="{{ $menu['homeRoute'] }}">{{ __('Home') }}</a>
    </li>

    <li><a href="{{ route('about') }}">{{ __('About Us') }}</a></li>
    <li><a href="{{ route('document.index', ['type' => 'faq']) }}">{{ __('FAQs') }}</a></li>
    <li><a href="{{ route('document.index', ['type' => 'testimonials']) }}">{{ __('Testimonials') }}</a></li>

    <li class="menu-item-has-children">
        <a href="{{ route('document.index', ['type' => 'help-center']) }}">
            {{ __('Help') }}
        </a>
        <ul class="sub-menu">
            @foreach ($menu['helpItems'] as $type => $label)
            <li>
                <a href="{{ route('document.index', ['type' => $type]) }}">
                    {{ $label }}
                </a>
            </li>
            @endforeach
        </ul>
    </li>

    <li class="menu-item-has-children">
        <a href="{{ route('document.index', ['type' => 'privacy']) }}">
            {{ __('Policy') }}
        </a>
        <ul class="sub-menu">
            @foreach ($menu['policyItems'] as $type => $label)
            <li>
                <a href="{{ route('document.index', ['type' => $type]) }}">
                    {{ $label }}
                </a>
            </li>
            @endforeach
        </ul>
    </li>

    <li><a href="{{ route('contact') }}">{{ __('Contact Us') }}</a></li>

    @guest
    @if ($menu['currentRoute'] === 'login')
    <li><a href="{{ route('register') }}">{{ __('Register') }}</a></li>

    @elseif ($menu['currentRoute'] === 'register')
    <li><a href="{{ route('login') }}">{{ __('Login') }}</a></li>

    @else
    <li class="menu-item-has-children">
        <a href="{{ route('login') }}">{{ __('Login') }}</a>
        <ul class="sub-menu">
            <li><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
        </ul>
    </li>
    @endif
    @endguest
</ul>
