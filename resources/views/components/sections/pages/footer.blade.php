<footer class="footer-wrapper footer-layout1" data-bg-src="{{ asset('assets/img/bg/footer_bg_1.png') }}">
    <div class="widget-area">
        <div class="container">
            <div class="row justify-content-between">

                <div class="col-md-6 col-xl-3">
                    <div class="widget footer-widget">
                        <div class="th-widget-about">
                            <div class="about-logo">

                                @include('partials.logo')

                            </div>

                            <p class="about-text">
                                {{-- Optional: {{ $company->description ?? '' }} --}}
                            </p>

                            <div class="th-social style-black">
                                @if(!empty($global['global_icons']))
                                @foreach ($global['global_icons'] as $media)
                                <a href="{{ $media['url'] }}" target="_blank" rel="noopener" class="d-inline-flex align-items-center justify-content-center rounded-circle me-1 social-icon" style="width: 2.5rem; height: 2.5rem; background-color: {{ $media['color'] }};">
                                    <i class="{{ $media['icon'] }} text-white"></i>
                                </a>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-auto">
                    <div class="widget footer-widget">

                        @include('partials.recent-posts')

                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="widget widget_tag_cloud footer-widget">

                        @include('partials.aside.tag')

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright-wrap">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-5">
                    <p class="copyright-text">
                        {{ __('Copyright') }}
                        <a href="{{ route('home') }}">{{ config('app.name') }}</a>
                        &copy; {{ now()->year }}
                    </p>
                </div>
                <div class="col-lg-auto ms-auto d-none d-lg-block">
                    <div class="footer-links">
                        <ul>
                            <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                            <li><a href="{{ route('about') }}">{{ __('About Us') }}</a></li>
                            <li><a href="{{ route('page.index', ['slug' => 'faq']) }}">{{ __('FAQs') }}</a></li>
                            <li><a href="{{ route('page.index', ['slug' => 'privacy']) }}">{{ __('Policy') }}</a>
                            </li>
                            <li><a href="{{ route('contact') }}">{{ __('Contact Us') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
