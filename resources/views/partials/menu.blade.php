<ul>
    <li>
        @guest
        <a href="{{ route('home') }}">{{ __('Home') }}</a>
        @else
        @if($global['mainRegions'])
        <a href="{{ route('page.index', ['slug' => $global['mainRegions']->first()->slug]) }}">{{ __('Home') }}</a>
        @endif
        @endguest
    </li>

    @foreach([
    'faq' => 'FAQs',
    'testimonials' => 'Testimonials',
    'help-center' => 'Help Center',
    'privacy' => 'Privacy Policy',
    'terms' => 'Terms & Conditions',
    'guidelines' => 'Guidelines'
    ] as $type => $label)
    <li>
        <a href="{{ route('document.index', ['type' => $type]) }}">{{ __($label) }}</a>
    </li>
    @endforeach

    @guest
    @php $currentRoute = Route::currentRouteName(); @endphp
    @switch(true)
    @case($currentRoute === 'login')
    <li><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
    @break
    @case($currentRoute === 'register')
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
