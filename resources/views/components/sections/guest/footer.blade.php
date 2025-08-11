<footer class="footer-wrapper footer-layout4" data-bg-src="{{ asset('assets/img/bg/footer_bg_1.png') }}">
    <div class="widget-area">
        <div class="container text-center">
            <div class="mb-30">
                <a href="{{ route('home') }}">
                    <img class="dark-img" src="{{ asset('storage/'. $global['logo']?->image) }}" class="img-fluid" style="max-width: 90px; height: 90px; object-fit: cover;">
                </a>
            </div>
            <div class="th-social style-black">
                @foreach ($global['global_icons'] as $media)
                <a href="{{ $media['url'] }}" target="_blank" rel="noopener" class="d-inline-flex align-items-center justify-content-center rounded-circle me-1" style="width: 2.5rem; height: 2.5rem; background-color: var(--bs-{{ $media['color'] }});">
                    <i class="{{ $media['icon'] }} text-white"></i>
                </a>
                @endforeach
            </div>
            <div class="footer-menu mb-30">
                <ul>
                    <li>
                        <a href="{{ route('document.index', ['type' => 'faq']) }}">
                            {{ __('FAQs') }}
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
                    <li>
                        <a href="{{ route('contact') }}">{{ __('Contact Us') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="text-center copyright-wrap">
            <p class="copyright-text">
                Copyright &copy; {{ now()->year }}
                <a href="{{ route('home') }}">{{ config('app.name') }}</a>.
                {{ __("All Rights Reserved.") }}
            </p>
        </div>
    </div>
</footer>
