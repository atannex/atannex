<footer class="footer-wrapper footer-layout1" data-bg-src="{{ asset('assets/img/bg/footer_bg_1.png') }}">
    <div class="widget-area">
        <div class="container">
            <div class="row justify-content-between">

                {{-- Company Info --}}
                <div class="col-md-6 col-xl-3">
                    <div class="widget footer-widget">
                        <div class="th-widget-about">
                            <div class="about-logo">
                                <a href="{{ route('home') }}">
                                    <img class="dark-img img-fluid" src="{{ isset($global['logo']->image)
                                                ? asset('storage/' . $global['logo']->image)
                                                : asset('images/default-logo.png') }}" alt="{{ config('app.name') }}" style="max-width: 98px; height: 98px; object-fit: cover;">
                                </a>
                            </div>

                            <p class="about-text">
                                {{-- Optional: {{ $company->description ?? '' }} --}}
                            </p>

                            {{-- Social Links --}}
                            <div class="th-social style-black">
                                @if(!empty($global['global_icons']))
                                @foreach ($global['global_icons'] as $media)
                                <a href="{{ $media['url'] ?? '#' }}" target="_blank" rel="noopener" title="{{ $media['label'] ?? 'Social Link' }}" class="d-inline-flex align-items-center justify-content-center rounded-circle me-1" style="width: 2.5rem; height: 2.5rem; background-color: var(--bs-{{ $media['color'] ?? 'primary' }});">
                                    <i class="{{ $media['icon'] ?? 'fas fa-link' }} text-white"></i>
                                </a>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Posts --}}
                <div class="col-md-6 col-xl-auto">
                    <div class="widget footer-widget">

                        @include('partials.recent-posts')

                    </div>
                </div>

                {{-- Tags --}}
                <div class="col-md-6 col-xl-3">
                    <div class="widget widget_tag_cloud footer-widget">

                        @include('components.partials.tags')

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright --}}
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
                            <li><a href="javascript:void(0)">{{ __('About Us') }}</a></li>
                            <li><a href="{{ route('document.index', ['type' => 'faq']) }}">{{ __('FAQs') }}</a></li>
                            <li><a href="javascript:void(0)">{{ __('Contact Us') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
