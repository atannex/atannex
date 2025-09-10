@includeIf('components.partials.mobile-menu')

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
                                <a href="{{ route('document.index', ['type' => 'privacy']) }}">
                                    {{ __('Privacy Policy') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('document.index', ['type' => 'terms']) }}">
                                    {{ __('Terms & Conditions') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="header-links">
                        <ul>

                            @auth
                            <li class="d-none d-sm-inline-block">
                                <i class="fa fa-key" aria-hidden="true"></i>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                </form>
                            </li>
                            <li class="d-none d-sm-inline-block">
                                <i class="far fa-user"></i>
                                <a href="{{ Auth::user()->employee ? url('/admin') : route('home') }}" class="text-decoration-none">
                                    {{ Auth::user()->name }}
                                </a>
                            </li>
                            @endauth

                            @guest
                            <li class="d-none d-sm-inline-block">
                                <i class="far fa-user"></i>
                                <a href="{{ route('home') }}" class="text-decoration-none">
                                    {{ __('Guest') }}
                                </a>
                            </li>
                            @endguest

                            <li>
                                <div class="social-links">
                                    @if(!empty($global['global_icons']))
                                    @foreach ($global['global_icons'] as $media)
                                    <a href="{{ $media['url'] ?? '#' }}" target="_blank" rel="noopener" title="{{ $media['label'] ?? '' }}" class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 1.5rem; height: 1.5rem; background-color: var(--bs-{{ $media['color'] ?? 'primary' }});">
                                        <i class="{{ $media['icon'] ?? 'fas fa-link' }} text-white"></i>
                                    </a>
                                    @endforeach
                                    @endif
                                </div>
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

                            @includeIf('components.partials.nav')

                        </nav>
                    </div>
                    <div class="col-auto">
                        <div class="header-button">
                            <button type="button" class="simple-icon searchBoxToggler">
                                <i class="far fa-search"></i>
                            </button>
                            <a href="#" class="icon-btn sideMenuToggler d-none d-lg-block">
                                <i class="far fa-bars"></i>
                            </a>
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
