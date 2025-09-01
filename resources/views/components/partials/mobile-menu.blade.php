    <div class="th-menu-wrapper">
        <div class="text-center th-menu-area">
            <button class="th-menu-toggle">
                <i class="fal fa-times"></i>
            </button>
            <div class="mobile-logo">
                <a href="{{ route('home') }}">
                    <img class="dark-img" src="{{ asset('storage/'. $global['logo']?->image) }}" class="img-fluid" style="max-width: 70px; height: 70px; object-fit: cover;">
                </a>
            </div>
            <div class="th-mobile-menu">

                @include('components.partials.nav')

            </div>
        </div>
    </div>
