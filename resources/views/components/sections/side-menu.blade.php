<div class="sidemenu-wrapper sidemenu-1 d-none d-md-block">
    <div class="sidemenu-content">
        <button class="closeButton sideMenuCls">
            <i class="far fa-times"></i>
        </button>
        <div class="widget">
            <div class="th-widget-about">
                <div class="about-logo">
                    <a href="{{ route('home') }}">
                        <img class="dark-img" src="{{ asset('storage/'. $global['logo']->image) }}" class="img-fluid" style="max-width: 98px; height: 98px; object-fit: cover;">
                    </a>
                </div>
                <p class="about-text">
                    {{-- {{ $company->description }} --}}
                </p>
                <div class="th-social style-black">
                    @foreach ($global['global_icons'] as $media)
                    <a href="{{ $media['url'] }}" target="_blank" rel="noopener" class="d-inline-flex align-items-center justify-content-center rounded-circle me-1" style="width: 2.5rem; height: 2.5rem; background-color: var(--bs-{{ $media['color'] }});">
                        <i class="{{ $media['icon'] }} text-white"></i>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="widget">

            @include('partials.recent-posts')

        </div>
        <div class="widget newsletter-widget">
            <h3 class="widget_title">Subscribe</h3>
            <p class="footer-text">Sign up to get update about us. Don't be hasitate your email is safe.</p>
            <form class="newsletter-form"><input class="form-control" type="email" placeholder="Enter Email" required=""> <button type="submit" class="icon-btn"><i class="fa-solid fa-paper-plane"></i></button></form>
            <div class="mt-30"><input type="checkbox" id="Agree2"> <label for="Agree2">I have read and accept the <a href="about.html">Terms & Policy</a></label></div>
        </div>
    </div>
</div>
