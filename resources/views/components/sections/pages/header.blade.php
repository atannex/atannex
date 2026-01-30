@include('components.partials.mobile-menu')

<header class="th-header header-layout1">
    <div class="header-top">
        <div class="container">
            <div class="row justify-content-center justify-content-lg-between align-items-center gy-2">
                <div class="col-auto d-none d-lg-block">
                    <div class="header-links">
                        <ul>
                            <li>
                                <i class="fal fa-calendar-days"></i>
                                <a href="javascript:void(0)">
                                    {{ now()->locale(app()->getLocale())->isoFormat('dddd D MMMM, YYYY') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('page.index', ['slug' => 'privacy']) }}">
                                    {{ __('Privacy Policy') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('page.index', ['slug' => 'terms']) }}">
                                    {{ __('Terms & Conditions') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="header-links">
                        <ul class="mb-0 list-unstyled d-flex align-items-center">

                            @auth
                            <li class="d-none d-sm-inline-block me-3">
                                <i class="fa fa-key" aria-hidden="true"></i>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-decoration-none">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>

                            <li class="d-none d-sm-inline-block me-3">
                                <i class="fas fa-user"></i>
                                <a href="{{ Auth::user()->employee ? url('/admin') : route('home') }}" class="text-decoration-none">
                                    {{ Auth::user()->name }}
                                </a>
                            </li>
                            @endauth

                            @guest
                            <li class="d-none d-sm-inline-block me-3">
                                <i class="fas fa-user"></i>
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    {{ __('Guest') }}
                                </a>
                            </li>
                            @endguest

                            <li>
                                @include('partials.social-links')
                            </li>

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="header-middle">
        <div class="container">
            <div class="row justify-content-center justify-content-lg-between align-items-center">
                <div class="col-auto d-none d-lg-block">
                    <div class="header-logo">
                        <a href="{{ route('home') }}">
                            <img class="dark-img" src="{{ isset($global['logo']->image) ? asset('storage/' . $global['logo']->image) : asset('images/default-logo.png') }}" alt="Logo" style="max-width: 98px; height: 98px; object-fit: cover;">
                        </a>
                    </div>
                </div>
                <div class="col-lg-8 text-end">
                    <div class="header-ads">
                        <a href="{{ route('home') }}">
                            <img class="img-fluid page-banner dark-img" src="{{ isset($global['banner']->image) ? asset('storage/' . $global['banner']->image) : asset('images/default-banner.jpg') }}" alt="Banner">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sticky-wrapper">
        <div class="menu-area">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto d-lg-none d-block">
                        <div class="header-logo">
                            <a href="{{ route('home') }}">
                                <img class="dark-img" src="{{ isset($global['logo']->image) ? asset('storage/' . $global['logo']->image) : asset('images/default-logo.png') }}" alt="Logo" style="max-width: 70px; height: 70px; object-fit: cover;">
                            </a>
                        </div>
                    </div>
                    <div class="col-auto">
                        <nav class="main-menu d-none d-lg-inline-block">

                            @include('components.partials.nav')

                        </nav>
                    </div>
                    <div class="col-auto">
                        <div class="header-button">
                            <button type="button" class="simple-icon searchBoxToggler">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="#" class="icon-btn sideMenuToggler d-none d-lg-block">
                                <i class="fas fa-bars"></i>
                            </a>
                            <button type="button" class="th-menu-toggle d-block d-lg-none">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
