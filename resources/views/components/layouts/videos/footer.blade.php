<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center footer__content">

                    <a href="{{ route('home') }}" class="footer__logo d-inline-block">
                        <img src="{{ asset('storage/' . $global['logo']?->image) }}" alt="{{ config('app.name') }} Logo" class="img-fluid" style="max-width: 65px; height: 65px; object-fit: cover;">
                    </a>

                    <span class="footer__copyright d-block">
                        © {{ config('app.name') }}, {{ __('2023—') . date('Y') }}<br>
                        {{ __('Powered by') }}
                        <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer">
                            {{ config('app.name') }}
                        </a>
                    </span>

                    <nav class="footer__nav">
                        <a href="{{ route('about') }}">{{ __("About Us") }}</a>
                        <a href="{{ route('contact') }}">{{ __("Contacts") }}</a>
                        <a href="{{ route('page.index', ['slug' => 'privacy']) }}">{{ __("Privacy Policy") }}</a>
                    </nav>

                    <button class="footer__back" type="button" aria-label="Back to top" onclick="window.scrollTo({ top: 0, behavior: 'smooth' });">
                        <i class="ti ti-arrow-narrow-up"></i>
                    </button>

                </div>
            </div>
        </div>
    </div>
</footer>
