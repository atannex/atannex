<ul>
    <li>
        @guest
        <a href="{{ route('home') }}">{{ __('Home') }}</a>
        @else
        @if(!empty($global['mainRegions']) && $global['mainRegions']->isNotEmpty())
        <a href="{{ route('page.index', ['slug' => $global['mainRegions']->first()->slug]) }}">
            {{ __('Home') }}
        </a>
        @endif
        @endguest
    </li>

    <li><a href="{{ route('about') }}">{{ __('About Us') }}</a></li>
    <li><a href="{{ route('document.index', ['type' => 'faq']) }}">{{ __('FAQs') }}</a></li>
    <li><a href="{{ route('document.index', ['type' => 'testimonials']) }}">{{ __('Testimonials') }}</a></li>

    @php
    $helpItems = [
    'help-center' => __('Help Center'),
    'guidelines' => __('Guidelines'),
    ];
    @endphp
    <li class="menu-item-has-children">
        <a href="javascript:void(0)">{{ __('Help') }}</a>
        <ul class="sub-menu">
            @foreach($helpItems as $type => $label)
            <li>
                <a href="{{ route('document.index', ['type' => $type]) }}">{{ $label }}</a>
            </li>
            @endforeach
        </ul>
    </li>

    @php
    $policyItems = [
    'privacy' => __('Privacy Policy'),
    'terms' => __('Terms & Conditions'),
    ];
    @endphp
    <li class="menu-item-has-children">
        <a href="javascript:void(0)">{{ __('Policy') }}</a>
        <ul class="sub-menu">
            @foreach($policyItems as $type => $label)
            <li>
                <a href="{{ route('document.index', ['type' => $type]) }}">{{ $label }}</a>
            </li>
            @endforeach
        </ul>
    </li>

    <li><a href="{{ route('contact') }}">{{ __('Contact Us') }}</a></li>

    @guest
    @php $currentRoute = Route::currentRouteName(); @endphp

    @switch($currentRoute)
    @case('login')
    <li><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
    @break

    @case('register')
    <li><a href="{{ route('login') }}">{{ __('Login') }}</a></li>
    @break

    @default
    <li class="menu-item-has-children">
        <a href="{{ route('login') }}">{{ __('Login') }}</a>
        <ul class="sub-menu">
            <li><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
        </ul>
    </li>
    @endswitch
    @endguest
</ul>
