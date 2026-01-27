<header class="header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="header__content">
                    <a href="{{ route('home') }}" class="header__logo">
                        <img src="{{ asset('storage/'. $global['logo']?->image) }}" class="img-fluid" style="max-width: 65px; height: 65px; object-fit: cover;">
                    </a>

                    <ul class="header__nav">
                        <li class="header__nav-item">
                            <a href="{{ route('video') }}" class="header__nav-link">
                                {{ __("Home") }}
                            </a>
                        </li>

                        <li class="header__nav-item">
                            <a href="{{ route('about') }}" class="header__nav-link">
                                {{ __("About Us") }}
                            </a>
                        </li>
                        <li class="header__nav-item">
                            <a href="{{ route('contact') }}" class="header__nav-link">
                                {{ __("Contacts") }}
                            </a>
                        </li>
                        <li class="header__nav-item">
                            <a href="{{ route('page.index', ['slug' => 'help-center']) }}" class="header__nav-link">
                                {{ __("Help Center") }}
                            </a>
                        </li>
                        <li class="header__nav-item">
                            <a href="{{ route('page.index', ['slug' => 'privacy']) }}" class="header__nav-link">
                                {{ __("Privacy Policy") }}
                            </a>
                        </li>
                    </ul>

                    <div class="header__auth">
                        <form action="#" class="header__search">
                            <input class="header__search-input" type="text" placeholder="Search...">
                            <button class="header__search-button" type="button">
                                <i class="ti ti-search"></i>
                            </button>
                            <button class="header__search-close" type="button">
                                <i class="ti ti-x"></i>
                            </button>
                        </form>

                        <button class="header__search-btn" type="button">
                            <i class="ti ti-search"></i>
                        </button>

                        <div class="header__lang">
                            <a class="header__nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ __("EN ") }}<i class="ti ti-chevron-down"></i></a>

                            <ul class="dropdown-menu header__dropdown-menu">
                                <li><a href="#">{{ __("English") }}</a></li>
                                <li><a href="#">{{ __("Spanish") }}</a></li>
                                <li><a href="#">{{__("French")}}</a></li>
                            </ul>
                        </div>
                    </div>

                    <button class="header__btn" type="button">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
