@include('components.partials.mobile-menu')

<header class="th-header header-layout1">

    {{-- ================= HEADER TOP ================= --}}
    <div class="header-top">
        <div class="container">
            <div class="row justify-content-center justify-content-lg-between align-items-center gy-2">

                {{-- LEFT SIDE --}}
                <div class="col-auto d-none d-lg-block">
                    <div class="header-links">
                        <ul>
                            {{-- DATE --}}
                            <li>
                                <i class="fal fa-calendar-days"></i>
                                <a href="javascript:void(0)">
                                    {{ now()->locale(app()->getLocale())->isoFormat('dddd D MMMM, YYYY') }}
                                </a>
                            </li>

                            {{-- STATIC PAGES --}}
                            @php
                            $staticPages = [
                            'privacy' => 'Privacy Policy',
                            'terms' => 'Terms & Conditions',
                            ];
                            @endphp

                            @foreach ($staticPages as $slug => $label)
                            <li>
                                <a href="{{ route('pages.show', $slug) }}">
                                    {{ __($label) }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- RIGHT SIDE --}}
                <div class="col-auto">
                    <div class="header-links">
                        <ul class="mb-0 list-unstyled d-flex align-items-center">

                            {{-- AUTH --}}
                            <li class="d-none d-sm-inline-block me-3">
                                <i class="fas fa-user"></i>

                                @auth
                                <a href="{{ url('/admin') }}" class="text-decoration-none">
                                    {{ auth()->user()->name }}
                                </a>
                                @else
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    {{ __('Login') }}
                                </a>
                                @endauth
                            </li>

                            {{-- SOCIAL --}}
                            <li>
                                @include('partials.social-links')
                            </li>

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= HEADER MIDDLE ================= --}}
    <div class="header-middle">
        <div class="container">
            <div class="row justify-content-center justify-content-lg-between align-items-center">

                {{-- LOGO --}}
                <div class="col-auto d-none d-lg-block">
                    <div class="header-logo">
                        <a href="{{ route('home') }}">
                            <img class="dark-img" src="{{ asset('storage/' . ($global['logo']->image ?? '')) }}" alt="Logo" style="max-width: 98px; height: 98px; object-fit: cover;">
                        </a>
                    </div>
                </div>

                {{-- BANNER --}}
                <div class="col-lg-8 text-end">
                    <div class="header-ads">
                        <a href="{{ route('home') }}">
                            <img class="img-fluid page-banner dark-img" src="{{ asset('storage/' . ($global['banner']->image ?? '')) }}" alt="Banner">
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= NAVBAR ================= --}}
    <div class="sticky-wrapper">
        <div class="menu-area">
            <div class="container">
                <div class="row align-items-center justify-content-between">

                    {{-- MOBILE LOGO --}}
                    <div class="col-auto d-lg-none d-block">
                        <div class="header-logo">
                            <a href="{{ route('home') }}">
                                <img class="dark-img" src="{{ asset('storage/' . ($global['logo']->image ?? '')) }}" alt="Logo" style="max-width: 70px; height: 70px; object-fit: cover;">
                            </a>
                        </div>
                    </div>

                    {{-- NAV MENU --}}
                    <div class="col-auto">
                        <nav class="main-menu d-none d-lg-inline-block">
                            @include('components.partials.nav')
                        </nav>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="col-auto">
                        <div class="header-button">

                            {{-- SEARCH --}}
                            <button type="button" class="simple-icon searchBoxToggler">
                                <i class="fas fa-search"></i>
                            </button>

                            {{-- DESKTOP MENU --}}
                            <a href="#" class="icon-btn sideMenuToggler d-none d-lg-block">
                                <i class="fas fa-bars"></i>
                            </a>

                            {{-- MOBILE MENU --}}
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
