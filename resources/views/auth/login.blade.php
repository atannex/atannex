@extends('layouts.app')

@section('og:title', seo_title('Welcome Back to Atannex!'))

@section('content')
<div class="wrapper">
    <div class="editorial-panel editorial-panel--login">
        <div class="glow-blob"></div>

        <div class="ep-logo">
            <div class="ep-logo-mark"><span>A</span></div>
            <div>
                <div class="ep-name">ATANNEX</div>
                <div class="ep-name-underline"></div>
            </div>
        </div>

        <div class="ticker-wrap">
            <div class="ticker-track">
                <span class="ticker-item">🔴 BREAKING: Climate Emergency Summit Begins in Geneva <span class="ticker-dot">•</span> Inflation Hits 3-Year Low <span class="ticker-dot">•</span> Tech Giants Report Record Profits <span class="ticker-dot">•</span> War Crimes Tribunal Opens in The Hague <span class="ticker-dot">•</span></span>
                <span class="ticker-item">🔴 BREAKING: Climate Emergency Summit Begins in Geneva <span class="ticker-dot">•</span> Inflation Hits 3-Year Low <span class="ticker-dot">•</span> Tech Giants Report Record Profits <span class="ticker-dot">•</span> War Crimes Tribunal Opens in The Hague <span class="ticker-dot">•</span></span>
            </div>
        </div>

        <div class="ep-hero">
            <div class="ep-eyebrow">Trusted Journalism Since 2010</div>
            <h1 class="ep-headline">The World's<br />Stories,<br /><em>Told First.</em></h1>
            <p class="ep-desc">Independent reporting. Uncompromising standards. Sign in to access your personalised news experience from over 190 countries.</p>

            <div class="story-stack">
                <div class="story-card">
                    <span class="story-num">01</span>
                    <div>
                        <div class="story-cat">World</div>
                        <div class="story-title">Global Leaders Convene at Emergency Climate Summit in Geneva</div>
                    </div>
                </div>
                <div class="story-card">
                    <span class="story-num">02</span>
                    <div>
                        <div class="story-cat">Business</div>
                        <div class="story-title">Tech Giants Surge as Q4 Earnings Shatter Analysts' Forecasts</div>
                    </div>
                </div>
                <div class="story-card">
                    <span class="story-num">03</span>
                    <div>
                        <div class="story-cat">Science</div>
                        <div class="story-title">Breakthrough Treatment Promises New Hope in Cancer Fight</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-num">14M+</div>
                <div class="stat-label">Readers</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">190+</div>
                <div class="stat-label">Countries</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">24/7</div>
                <div class="stat-label">Coverage</div>
            </div>
        </div>
    </div>
    <div class="form-panel">

        @include('auth.partials.form-topbar')

        <div class="form-body">
            <div class="animate-in delay-1">
                <p class="ep-eyebrow">{{ __("Sign in to Atannex") }}</p>
                <h2 class="form-title">{{ __('Welcome') }}<br />{{ __("back.") }}</h2>
                <p class="form-sub">{{ __("Your news is waiting. Sign in to continue.") }}</p>
            </div>

            @include('auth.partials.social-grid')

            <div class="or-divider animate-in delay-2">
                <span>{{ __("or sign in with email") }}</span>
            </div>

            @if (Route::has('login'))
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field-group animate-in delay-3">
                    <div class="field-label">
                        <span>{{ __("Email Address") }}</span>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="auth-input @error('email') is-invalid @enderror" placeholder="you@example.com">
                    @error('email')
                    <span class="block mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group animate-in delay-4">
                    <div class="field-label">
                        <span>{{ __("Password") }}</span>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-red-600 hover:text-red-400">
                            {{ __('Forgot Your Password?') }}
                        </a>
                        @endif
                    </div>

                    <div class="pw-wrap">
                        <input id="password" type="password" name="password" required autocomplete="current-password" class="auth-input pr @error('password') is-invalid @enderror" placeholder="••••••••">
                        <button type="button" class="pw-toggle" onclick="togglePwVisibility('password', this)" tabindex="-1">
                            <svg id="eye-show" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eye-hide" width="20" height="20" class="hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                    <span class="block mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="remember-row animate-in delay-5">
                    <input type="checkbox" class="check-box" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="check-label">
                        {{ __("Keep me signed in for 30 days") }}
                    </label>
                </div>
                <button class="btn-submit animate-in delay-4" onclick="handleLogin()">
                    {{ __("Sign In to Atannex") }}
                    <svg style="display:inline;margin-left:8px;vertical-align:-2px;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                </button>

            </form>
            @endif
            @if (Route::has('register'))
            <p class="switch-link animate-in delay-5">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}">
                    {{ __("Create one free →") }}
                </a>
            </p>
            @endif
        </div>

        @include('auth.partials.form-footer')

    </div>
</div>
@endsection
