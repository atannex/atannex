<header class="modal-navbar-header">
    <a href="{{ route('landing') }}" class="logo-wrapper">
        <img src="{{ asset('storage/' . $global['logo']?->image) }}" alt="{{ config('app.name') }}" class="logo-image">
    </a>
    <nav class="desktop-nav">
        <ul class="nav-menu">
            <li>
                <a href="{{ route('landing') }}" class="nav-link">
                    {{ __('Home') }}
                </a>
            </li>
            <li>
                <a href="{{ route('about') }}" class="nav-link">
                    {{ __('About Us') }}
                </a>
            </li>
            <li>
                <a href="{{ route('testimonials') }}" class="nav-link">
                    {{ __('Testimonials') }}
                </a>
            </li>
            <li>
                <a href="{{ route('documents.list', ['type' => 'faq']) }}" class="nav-link">
                    {{ __('Faqs') }}
                </a>
            </li>
            <li>
                <a href="{{ route('contact') }}" class="nav-link">
                    {{ __('Contact Us') }}
                </a>
            </li>
        </ul>
    </nav>
    <div class="desktop-cta">
        <a href="/subscribe" class="modal-btn-cta modal-btn-primary">
            <i class="fas fa-envelope"></i> {{ __('Subscribe') }}
        </a>
        <a href="/donate" class="modal-btn-cta modal-btn-secondary">
            <i class="fas fa-heart"></i> {{ __('Donate') }}
        </a>
    </div>
    <button class="mobile-menu-trigger" id="modalNavTrigger" aria-label="Open navigation">
        <i class="fas fa-bars"></i>
    </button>
</header>

<div class="lg:hidden modal-nav-overlay" id="modalNavOverlay"></div>

<nav class="lg:hidden modal-bottom-nav" id="modalBottomNav">
    <div class="modal-nav-header">
        <div class="modal-nav-title">
            <i class="fas fa-compass" style="margin-right: 0.5rem;"></i>
            {{ __("Navigation") }}
        </div>
        <button class="modal-close-btn" id="modalNavClose" aria-label="Close navigation">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="modal-nav-content">
        <div class="modal-nav-section">
            <div class="modal-section-title">
                <i class="fas fa-star"></i>
                {{ __('Main Navigation') }}
            </div>
            <ul class="modal-nav-items">
                <li class="modal-nav-item">
                    <a href="{{ route('landing') }}">
                        <i class="modal-nav-item-icon fas fa-info-circle"></i>
                        <span>{{ __('Home') }}</span>
                    </a>
                </li>
                <li class="modal-nav-item">
                    <a href="{{ route('about') }}">
                        <i class="modal-nav-item-icon fas fa-info-circle"></i>
                        <span>{{ __('About Us') }}</span>
                    </a>
                </li>
                <li class="modal-nav-item">
                    <a href="{{ route('testimonials') }}">
                        <i class="modal-nav-item-icon fas fa-newspaper"></i>
                        <span>{{ __('Testimonials') }}</span>
                    </a>
                </li>
                <li class="modal-nav-item">
                    <a href="{{ route('documents.list', ['type' => 'faq']) }}">
                        <i class="modal-nav-item-icon fas fa-map"></i>
                        <span>{{ __('Faqs') }}</span>
                    </a>
                </li>
                <li class="modal-nav-item">
                    <a href="{{ route('contact') }}">
                        <i class="modal-nav-item-icon fas fa-envelope"></i>
                        <span>{{ __('Contact Us') }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="modal-nav-cta-section">
            <a href="/subscribe" class="modal-btn-cta modal-btn-primary">
                <i class="fas fa-envelope"></i> {{ __('Subscribe') }}
            </a>
            <a href="/donate" class="modal-btn-cta modal-btn-secondary">
                <i class="fas fa-heart"></i> {{ __('Donate') }}
            </a>
        </div>

        <div class="modal-nav-info-section modal-info-grid">
            <div class="modal-info-item">
                <div class="modal-info-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="modal-info-content">
                    <div class="modal-info-label">{{ __('Call Us') }}</div>
                    <div class="modal-info-value">{{ __('+1(540)-242-2572') }}</div>
                </div>
            </div>
            <div class="modal-info-item">
                <div class="modal-info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="modal-info-content">
                    <div class="modal-info-label">{{ __('Email') }}</div>
                    <div class="modal-info-value">{{ __("support@atannex.cm") }}</div>
                </div>
            </div>
            <div class="modal-info-item">
                <div class="modal-info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="modal-info-content">
                    <div class="modal-info-label">{{ __('Location') }}</div>
                    <div class="modal-info-value">{{ __('Fontem, Lebialem') }}</div>
                </div>
            </div>
        </div>
    </div>
</nav>
