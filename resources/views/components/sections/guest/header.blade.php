<header class="th-header header-layout1">
    <div class="sticky-wrapper">
        <div class="menu-area">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto d-lg-none d-block">
                        <div class="header-logo">
                            <a href="{{ route('home') }}">
                                <img class="dark-img" src="{{ asset('storage/'. $global['logo']->image) }}" class="img-fluid" style="max-width: 70px; height: 70px; object-fit: cover;">
                            </a>
                        </div>
                    </div>
                    <div class="col-auto">
                        <nav class="main-menu d-none d-lg-inline-block">
                            <ul>
                                <li>
                                    @guest
                                    <a href="{{ route('home') }}">{{ __('Home') }}</a>
                                    @elseauth
                                    @if($global['home'])
                                    <a href="{{ route('page.index', ['slug' => $global['home']->first()->slug]) }}">
                                        {{ __('Home') }}
                                    </a>
                                    @endif
                                    @endguest
                                </li>

                                <li>
                                    <a href="{{ route('document.index', ['type' => 'faq']) }}">
                                        {{ __('FAQs') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('document.index', ['type' => 'testimonials']) }}">{{ __('Testimonials') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('document.index', ['type' => 'help-center']) }}">{{ __('Help Center') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('document.index', ['type' => 'privacy']) }}">
                                        {{ __('Privacy Policy') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('document.index', ['type' => 'terms']) }}">
                                        {{ __('Terms & Conditions') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('document.index', ['type' => 'guidelines']) }}">
                                        {{ __('Guidelines') }}
                                    </a>
                                </li>
                                @guest
                                @switch(true)
                                @case(Route::currentRouteNamed('login'))
                                <li>
                                    <a href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                                @break
                                @case(Route::currentRouteNamed('register'))
                                <li>
                                    <a href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @break
                                @default
                                <li class="menu-item-has-children">
                                    <a href="{{ route('login') }}">{{ __("Authenticate") }}</a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="{{ route('login') }}">{{ __('Login') }}</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('register') }}">{{ __('Register') }}</a>
                                        </li>
                                    </ul>
                                </li>
                                @endswitch
                                @endguest
                            </ul>

                        </nav>
                    </div>
                    <div class="col-auto">
                        <div class="header-button">
                            <button type="button" class="th-menu-toggle d-block d-lg-none">
                                <i class="far fa-bars"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
