@extends('layouts.app')

@section('content')
<div class="wrapper">
    <div class="editorial-panel editorial-panel--verify">
        <div class="glow-blob-verify"></div>
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
                    {{ __('ATANNEX: War Crimes Tribunal Opens') }}
                    <span class="ticker-dot">•</span>
                </span>
                <span class="ticker-item">
                    🔴 {{ __('Climate Summit Opens in Geneva') }}
                    <span class="ticker-dot">•</span>
                    {{ __('Inflation Hits 3-Year Low') }}
                    <span class="ticker-dot">•</span>
                    {{ __('Tech Giants Report Record Profits') }}
                    <span class="ticker-dot">•</span>
                    {{ __('ATANNEX: War Crimes Tribunal Opens') }}
                    <span class="ticker-dot">•</span>
                </span>
            </div>
        </div>
        <div class="ep-hero">
            <div class="ep-eyebrow">{{ __('Account Activation') }}</div>
            <h1 class="ep-headline">
                {{ __('One step') }}<br />
                {{ __('from the') }}<br />
                <em>{{ __('newsroom.') }}</em>
            </h1>
            <p class="ep-desc">
                {{ __('Your Atannex account is almost ready. Verify your email to unlock your personalised feed, breaking alerts, and exclusive reporting.') }}
            </p>
            <div class="verify-unlock-list">
                <div class="unlock-item">
                    <div class="unlock-icon">
                        <svg width="18" height="18" fill="none" stroke="#CC0000" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <div class="unlock-title">{{ __('Breaking Alerts') }}</div>
                        <div class="unlock-sub">{{ __('Real-time notifications the moment news breaks') }}</div>
                    </div>
                </div>
                <div class="unlock-item">
                    <div class="unlock-icon">
                        <svg width="18" height="18" fill="none" stroke="#CC0000" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                    </div>
                    <div>
                        <div class="unlock-title">{{ __('Personalised Feed') }}</div>
                        <div class="unlock-sub">{{ __('News tailored to your topics and region') }}</div>
                    </div>
                </div>
                <div class="unlock-item">
                    <div class="unlock-icon">
                        <svg width="18" height="18" fill="none" stroke="#CC0000" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                        </svg>
                    </div>
                    <div>
                        <div class="unlock-title">{{ __('Exclusive Reports') }}</div>
                        <div class="unlock-sub">{{ __('Deep-dive investigations from our correspondents') }}</div>
                    </div>
                </div>
                <div class="unlock-item">
                    <div class="unlock-icon">
                        <svg width="18" height="18" fill="none" stroke="#CC0000" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <div>
                        <div class="unlock-title">{{ __('Morning Briefing') }}</div>
                        <div class="unlock-sub">{{ __('Your daily digest, curated every morning') }}</div>
                    </div>
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

        @include('auth.partials.form-topbar')

        <div class="form-body">
            <div class="verify-envelope-wrap animate-in delay-1">
                <div class="verify-envelope">
                    <svg class="envelope-icon" width="56" height="56" fill="none" viewBox="0 0 56 56">
                        <rect width="56" height="56" rx="28" fill="rgba(204,0,0,0.08)" stroke="rgba(204,0,0,0.25)" stroke-width="1.5" />
                        <path d="M14 20a2 2 0 012-2h24a2 2 0 012 2v16a2 2 0 01-2 2H16a2 2 0 01-2-2V20z" stroke="#CC0000" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                        <path d="M14 20l14 10 14-10" stroke="#CC0000" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="40" cy="16" r="8" fill="#0A0A0A" stroke="#1A1A1A" stroke-width="1" />
                        <path d="M36.5 16l2.5 2.5L43.5 13" stroke="#00b8a0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="verify-dot verify-dot-1"></span>
                    <span class="verify-dot verify-dot-2"></span>
                    <span class="verify-dot verify-dot-3"></span>
                </div>
            </div>
            <div class="animate-in delay-2" style="text-align:center;margin-bottom:28px;">
                <p class="form-eyebrow" style="justify-content:center;">
                    {{ __('Step 3 of 3 · Almost There') }}
                </p>
                <h2 class="form-title" style="font-size:1.9rem;">
                    {{ __('Verify your') }}<br />{{ __('email address.') }}
                </h2>
            </div>
            <div class="verify-sent-card animate-in delay-2">
                <div class="verify-sent-label">{{ __("We've sent a verification link to") }}</div>
                <div class="verify-sent-address">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    <span>{{ auth()->user()->email }}</span>
                </div>
                <div class="verify-sent-note">
                    {{ __('Click the link in the email to activate your account. The link expires in') }}
                    <strong style="color:var(--light);">{{ __('60 minutes') }}</strong>.
                </div>
            </div>

            @if (session('resent'))
            <div class="verify-success-banner animate-in delay-1">
                <svg width="16" height="16" fill="none" stroke="#00b8a0" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
            @endif

            <form method="POST" action="{{ route('verification.resend') }}" id="resend-form">
                @csrf
                <button type="submit" class="btn-submit animate-in delay-3" id="resend-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;margin-right:8px;vertical-align:-2px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    {{ __('Resend Verification Email') }}
                </button>
            </form>

            <div class="verify-resend-row animate-in delay-3">
                <span class="resend-label">{{ __("Didn't receive it?") }}</span>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span class="resend-timer" id="verify-timer"></span>
                    <button type="button" class="resend-btn" id="verify-resend-trigger" onclick="triggerResend()" disabled>{{ __('Resend') }}</button>
                </div>
            </div>

            <div class="verify-tips animate-in delay-4">
                <p class="verify-tips-heading">{{ __("Can't find the email?") }}</p>
                <ul class="verify-tips-list">
                    <li>
                        <span class="verify-tip-arrow">→</span>
                        {{ __('Check your Spam or Junk folder') }}
                    </li>
                    <li>
                        <span class="verify-tip-arrow">→</span>
                        <span class="verify-tip-text">
                            {!! __('Add <strong class="verify-email">noreply@atannex.com</strong> to your contacts') !!}
                        </span>
                    </li>
                    <li>
                        <span class="verify-tip-arrow">→</span>
                        <span class="verify-tip-text">
                            {{ __('Check that') }}
                            <strong class="verify-email">
                                {{ auth()->user()->email }}
                            </strong>
                            {{ __('is the address you intended to use') }}
                        </span>
                    </li>
                    <li>
                        <span class="verify-tip-arrow">{{ __("→") }}</span>
                        {{ __('Allow up to 2 minutes for the email to arrive') }}
                    </li>
                </ul>
            </div>
            <div class="verify-logout-row animate-in delay-5">
                <div class="verify-logout-text">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    {{ __('Used the wrong email address?') }}
                </div>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="verify-logout-btn">
                        {{ __('Log out & start over') }}
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;margin-left:5px;vertical-align:-1px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    </button>
                </form>
            </div>

        </div>

        @include('auth.partials.form-footer')

    </div>
</div>

@endsection
