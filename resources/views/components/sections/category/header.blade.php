@include('components.partials.mobile-menu')

<header class="th-header header-layout4">
    <div class="header-middle">
        <div class="container">
            <div class="row justify-content-center justify-content-lg-between align-items-center gy-2">
                <div class="col-auto d-none d-lg-inline-block">
                    <div class="header-logo">

                        @include('partials.logo')

                    </div>
                </div>
                <div class="text-center col d-none d-md-block">
                    <div class="header-ads">
                        <a href="{{ route('home') }}">
                            <img class="dark-img" src="{{ asset('storage/'. $global['banner']?->image) }}" />
                        </a>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="th-social style-black">
                        @foreach ($global['global_icons'] as $media)
                        <a href="{{ $media['url'] }}" target="_blank" rel="noopener" class="d-inline-flex align-items-center justify-content-center rounded-circle me-1" style="width: 2.5rem; height: 2.5rem; background-color: var(--bs-{{ $media['color'] }});">
                            <i class="{{ $media['icon'] }} text-white"></i>
                        </a>
                        @endforeach
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

                            @include('partials.logo')

                        </div>
                    </div>
                    <div class="col-auto d-none d-lg-block">
                        <div class="header-button">
                            <a href="javascript:void(0)" class="simple-icon sideMenuToggler d-none d-lg-block">
                                <i class="far fa-bars"></i>
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
                                <i class="far fa-search"></i>
                            </button>
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
