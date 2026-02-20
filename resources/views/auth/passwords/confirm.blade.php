@extends('layouts.app')

@section('content')
<div class="wrapper">
    <div class="editorial-panel editorial-panel--confirm">
        <div class="glow-blob-confirm"></div>
        <div class="ep-logo">
            <div class="ep-logo-mark"><span>A</span></div>
            <div>
                <div class="ep-name">ATANNEX</div>
                <div class="ep-name-underline"></div>
            </div>
        </div>
        <div class="ticker-wrap">
            <div class="ticker-track">
                <span class="ticker-item">
                    🔴 {{ __('Climate Summit Opens in Geneva') }}
                    <span class="ticker-dot">•</span>
                    {{ __('Inflation Hits 3-Year Low') }}
                    <span class="ticker-dot">•</span>
                    {{ __('Tech Giants Report Record Profits') }}
                    <span class="ticker-dot">•</span>
                    {{ __('War Crimes Tribunal Opens in The Hague') }}
                    <span class="ticker-dot">•</span>
                </span>
                <span class="ticker-item">
                    🔴 {{ __('Climate Summit Opens in Geneva') }}
                    <span class="ticker-dot">•</span>
                    {{ __('Inflation Hits 3-Year Low') }}
                    <span class="ticker-dot">•</span>
                    {{ __('Tech Giants Report Record Profits') }}
                    <span class="ticker-dot">•</span>
                    {{ __('War Crimes Tribunal Opens in The Hague') }}
                    <span class="ticker-dot">•</span>
                </span>
            </div>
        </div>
        <div class="ep-hero">
            <div class="ep-eyebrow">{{ __('Security Checkpoint') }}</div>
            <h1 class="ep-headline">
                {{ __('Prove') }}<br />
                {{ __("it's") }}<br />
                <em>{{ __('really you.') }}</em>
            </h1>
            <p class="ep-desc">
                {{ __("This area contains sensitive account settings. We need to re-confirm your identity before you proceed — it only takes a second.") }}
            </p>
            <div class="confirm-why-box">
                <div class="confirm-why-heading">
                    <svg width="14" height="14" fill="none" stroke="#CC0000" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                    {{ __('Why are we asking?') }}
                </div>
                <p class="confirm-why-text">
                    {{ __("Your session has been open for a while, or you're accessing a protected area. Re-entering your password ensures it's really you — not someone who borrowed your device.") }}
                </p>
            </div>
            <div class="confirm-protected-list">
                <div class="confirm-protected-heading">{{ __('Protected actions include') }}</div>

                <div class="confirm-protected-item">
                    <div class="confirm-protected-icon">
                        <svg width="14" height="14" fill="none" stroke="#CC0000" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                        </svg>
                    </div>
                    <span>{{ __('Changing your password') }}</span>
                </div>

                <div class="confirm-protected-item">
                    <div class="confirm-protected-icon">
                        <svg width="14" height="14" fill="none" stroke="#CC0000" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <span>{{ __('Updating your email address') }}</span>
                </div>

                <div class="confirm-protected-item">
                    <div class="confirm-protected-icon">
                        <svg width="14" height="14" fill="none" stroke="#CC0000" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 3.75h3m-3 3.75h3" />
                        </svg>
                    </div>
                    <span>{{ __('Managing connected devices') }}</span>
                </div>

                <div class="confirm-protected-item">
                    <div class="confirm-protected-icon">
                        <svg width="14" height="14" fill="none" stroke="#CC0000" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 2.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                        </svg>
                    </div>
                    <span>{{ __('Deleting your account') }}</span>
                </div>
            </div>
        </div>

        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-num">14M+</div>
                <div class="stat-label">{{ __('Readers') }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">190+</div>
                <div class="stat-label">{{ __('Countries') }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">24/7</div>
                <div class="stat-label">{{ __('Coverage') }}</div>
            </div>
        </div>
    </div>
    <div class="form-panel">
        <div class="form-topbar desktop-topbar">
            <a href="{{ url()->previous() }}" class="back-link" style="margin-bottom:0;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                {{ __('Go Back') }}
            </a>
            <div class="form-topbar__meta">
                <span class="live-dot"></span>
                <span>{{ __('Secure Connection') }}</span>
            </div>
        </div>

        <div class="form-body">
            <div class="confirm-shield-wrap animate-in delay-1">
                <div class="confirm-shield">
                    <div class="confirm-shield-ring confirm-shield-ring--outer"></div>
                    <div class="confirm-shield-ring confirm-shield-ring--inner"></div>
                    <svg class="confirm-shield-icon" width="32" height="32" fill="none" stroke="#CC0000" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
            </div>
            <div class="animate-in delay-1" style="text-align:center;margin-bottom:28px;">
                <p class="form-eyebrow" style="justify-content:center;">
                    {{ __('Identity Confirmation') }}
                </p>
                <h2 class="form-title" style="font-size:1.85rem;">
                    {{ __('Confirm your') }}<br />{{ __('password.') }}
                </h2>
                <p class="form-sub" style="margin-bottom:0;">
                    {{ __('This is a secure area. Please confirm your password before continuing.') }}
                </p>
            </div>
            <div class="confirm-identity-card animate-in delay-2">
                <div class="confirm-identity-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="confirm-identity-info">
                    <div class="confirm-identity-name">{{ auth()->user()->name }}</div>
                    <div class="confirm-identity-email">{{ auth()->user()->email }}</div>
                </div>
                <div class="confirm-identity-badge">
                    <svg width="12" height="12" fill="none" stroke="#00b8a0" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    {{ __('Signed in') }}
                </div>
            </div>
            @if ($errors->any())
            <div class="confirm-error-banner animate-in delay-2">
                <svg width="16" height="16" fill="none" stroke="#CC4400" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif
            <form method="POST" action="{{ route('password.confirm') }}" id="confirm-form" novalidate>
                @csrf
                <div class="field-group animate-in delay-3">
                    @if (Route::has('password.request'))
                    <div class="field-label">
                        <span>{{ __('Your Password') }}</span>
                        <a href="{{ route('password.request') }}">{{ __('Forgot it?') }}</a>
                    </div>
                    @endif
                    <div class="pw-wrap">
                        <input type="password" id="confirm-pw-input" name="password" class="auth-input pr @error('password') input-error @enderror" placeholder="{{ __('Enter your current password') }}" required autocomplete="current-password" autofocus />
                        <button type="button" class="pw-toggle" onclick="togglePwVisibility('confirm-pw-input', 'cp-eye-show', 'cp-eye-hide')" tabindex="-1" aria-label="{{ __('Toggle password visibility') }}">
                            <svg id="cp-eye-show" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="cp-eye-hide" style="display:none;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                    <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="confirm-capslock-warn" id="capslock-warn" style="display:none;">
                    <svg width="13" height="13" fill="none" stroke="#CC8800" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    {{ __('Caps Lock is on') }}
                </div>
                <button type="submit" class="btn-submit animate-in delay-4" id="confirm-submit-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;margin-right:8px;vertical-align:-2px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    {{ __('Confirm & Continue') }}
                </button>

            </form>
            <div class="confirm-security-note animate-in delay-5">
                <svg width="14" height="14" fill="none" stroke="#00b8a0" stroke-width="1.8" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
                <span>
                    {{ __('This confirmation is encrypted and never stored. It expires automatically after') }}
                    <strong style="color:var(--light);">{{ __('3 hours') }}</strong>.
                </span>
            </div>
            <div class="confirm-not-you animate-in delay-5">
                <span>{{ __('Not') }} {{ auth()->user()->name }}?</span>
                @if (Route::has('logout'))
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="confirm-switch-btn">
                        {{ __('Switch account') }}
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;margin-left:4px;vertical-align:-1px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    </button>
                </form>
                @endif
            </div>

        </div>

        @include('auth.partials.form-footer')

    </div>
</div>
@endsection
