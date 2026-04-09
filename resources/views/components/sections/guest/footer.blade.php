<footer class="footer-wrapper footer-layout4" data-bg-src="{{ asset('assets/img/bg/footer_bg_1.png') }}">

    <div class="widget-area">
        <div class="container text-center">

            <div class="mb-30">
                @include('partials.logo')
            </div>

            {{-- Social Icons --}}
            <div class="th-social style-black">
                @foreach ($global['global_icons'] as $media)
                <a href="{{ $media['url'] }}" target="_blank" rel="noopener" class="d-inline-flex align-items-center justify-content-center rounded-circle me-1 social-icon" style="width: 2.5rem; height: 2.5rem; background-color: {{ $media['color'] }};">

                    <i class="{{ $media['icon'] }} text-white"></i>
                </a>
                @endforeach
            </div>

            {{-- Footer Menu --}}
            <div class="footer-menu mb-30">
                <ul>
                    <li>
                        <a href="{{ route('documents.list', ['type' => 'faq']) }}">
                            {{ __('FAQs') }}
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('documents.list', ['type' => 'privacy']) }}">
                            {{ __('Privacy Policy') }}
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('documents.list', ['type' => 'terms']) }}">
                            {{ __('Terms & Conditions') }}
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('contact') }}">
                            {{ __('Contact Us') }}
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('about') }}">
                            {{ __('About Us') }}
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    {{-- Copyright --}}
    <div class="container">
        <div class="text-center copyright-wrap">
            <p class="copyright-text">
                Copyright &copy; {{ now()->year }}
                <a href="{{ route('home') }}">{{ config('app.name') }}</a>.
                {{ __('All Rights Reserved.') }}
            </p>
        </div>
    </div>

</footer>
