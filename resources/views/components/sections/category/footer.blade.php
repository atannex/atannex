    <footer class="footer-wrapper footer-layout3" data-bg-src="assets/img/bg/footer_bg_2.png">
        <div class="widget-area">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-md-6 col-xl-3">
                        <div class="widget footer-widget">
                            <div class="th-widget-about">
                                <div class="about-logo">
                                    <a href="{{ route('home') }}">
                                        <img class="dark-img" src="{{ asset('storage/'. $global['logo']?->image) }}" class="img-fluid" style="max-width: 98px; height: 98px; object-fit: cover;">
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
                    </div>

                    {{-- @include('layouts.categories') --}}

                    <div class="col-md-6 col-xl-auto">
                        <div class="widget footer-widget">

                            @include('partials.recent-posts')

                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="widget newsletter-widget footer-widget">
                            <h3 class="widget_title">Subscribe</h3>
                            <p class="footer-text">Sign up to get update about us. Don't be hasitate your email is safe.
                            </p>
                            <form class="newsletter-form"><input class="form-control" type="email" placeholder="Enter Email" required=""> <button type="submit" class="icon-btn"><i class="fa-solid fa-paper-plane"></i></button></form>
                            <div class="mt-30"><input type="checkbox" id="Agree"> <label for="Agree">I have read and
                                    accept the <a href="about.html">Terms & Policy</a></label></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-wrap">
            <div class="container">
                <div class="row jusity-content-between align-items-center">
                    <div class="col-lg-5">
                        <p class="copyright-text">
                            {{ __("Copyright") }} &copy; {{ now()->year }}
                            <a href="{{ route('home') }}">{{ config('app.name') }}</a>.
                            {{ __(" All Rights Reserved. ") }}
                        </p>
                    </div>
                    <div class="col-lg-auto ms-auto d-none d-lg-block"></div>
                </div>
            </div>
        </div>
    </footer>
