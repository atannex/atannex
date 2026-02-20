@extends('layouts.app')

@section('content')

<div class="wrapper">
    <div class="editorial-panel editorial-panel--reset">
        <div class="glow-blob-reset"></div>

        {{-- Logo --}}
        <div class="ep-logo">
            <div class="ep-logo-mark"><span>A</span></div>
            <div>
                <div class="ep-name">ATANNEX</div>
                <div class="ep-name-underline"></div>
            </div>
        </div>

        {{-- Ticker --}}
        <div class="ticker-wrap">
            <div class="ticker-track">
                <span class="ticker-item">
                    🔴 {{ __('Climate Emergency Summit Begins in Geneva') }}
                    <span class="ticker-dot">•</span>
                    {{ __('Inflation Hits 3-Year Low') }}
                    <span class="ticker-dot">•</span>
                    {{ __('Tech Giants Report Record Profits') }}
                    <span class="ticker-dot">•</span>
                    {{ __('ATANNEX: War Crimes Tribunal Opens') }}
                    <span class="ticker-dot">•</span>
                </span>
                <span class="ticker-item">
                    🔴 {{ __('Climate Emergency Summit Begins in Geneva') }}
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

        {{-- Hero --}}
        <div class="ep-hero">
            <div class="ep-eyebrow">{{ __('Account Security') }}</div>
            <h1 class="ep-headline">
                {{ __('Secure.') }}<br />
                {{ __('Simple.') }}<br />
                <em>{{ __('Back in.') }}</em>
            </h1>
            <p class="ep-desc">
                {{ __("Almost there. Create a strong new password and you'll be back to the news in seconds.") }}
            </p>

            {{-- Left panel step tracker — Steps 1 & 2 are done, Step 3 is active --}}
            <div class="security-steps">
                <div class="security-step done">
                    <div class="sec-step-num">
                        <svg width="14" height="14" fill="none" stroke="#00b8a0" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <div>
                        <div class="sec-step-title">{{ __('Enter your email') }}</div>
                        <div class="sec-step-desc">{{ __("We'll look up the account linked to that address") }}</div>
                    </div>
                </div>
                <div class="security-step done">
                    <div class="sec-step-num">
                        <svg width="14" height="14" fill="none" stroke="#00b8a0" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <div>
                        <div class="sec-step-title">{{ __('Check your inbox') }}</div>
                        <div class="sec-step-desc">{{ __('A secure one-time link will arrive within a minute') }}</div>
                    </div>
                </div>
                <div class="security-step active">
                    <div class="sec-step-num">3</div>
                    <div>
                        <div class="sec-step-title">{{ __('Set a new password') }}</div>
                        <div class="sec-step-desc">{{ __("Choose something strong — we'll check it for you") }}</div>
                    </div>
                </div>
                <div class="security-step inactive">
                    <div class="sec-step-num">4</div>
                    <div>
                        <div class="sec-step-title">{{ __('Back to the news') }}</div>
                        <div class="sec-step-desc">{{ __('Sign in and pick up exactly where you left off') }}</div>
                    </div>
                </div>
            </div>

            {{-- Security badge --}}
            <div class="security-badge">
                <div class="security-badge-icon">
                    <svg width="20" height="20" fill="none" stroke="#00b8a0" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <div class="security-badge-text">
                    <strong>{{ __('256-bit Encrypted') }}</strong>
                    {{ __('Your new password is hashed immediately. We never store it in plain text.') }}
                </div>
            </div>
        </div>

        {{-- Stats --}}
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
            <div class="form-topbar__meta">
                <span class="live-dot"></span>
                <span>{{ __('Secure Connection') }}</span>
            </div>
            <span class="form-topbar__domain">atannex.com</span>
        </div>
        <div class="form-body">
            <div id="step-new-password">
                <div class="reset-steps animate-in delay-1">
                    <div class="reset-pip done"></div>
                    <div class="reset-pip done"></div>
                    <div class="reset-pip active"></div>
                </div>
                <div class="animate-in delay-1">
                    <p class="reset-step-label">{{ __('Step 3 of 3') }} &nbsp;·&nbsp; {{ __('New Password') }}</p>
                    <p class="form-eyebrow">{{ __('Almost done') }}</p>
                    <h2 class="form-title">{{ __('Create a new') }}<br />{{ __('password.') }}</h2>
                    <p class="form-sub">{{ __("Make it strong. You won't need to change it again anytime soon.") }}</p>
                </div>
                @if($errors->any())
                <div class="reset-error-banner animate-in delay-1">
                    <svg width="16" height="16" fill="none" stroke="#CC4400" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif
                <form method="POST" action="{{ route('password.update') }}" id="reset-form" novalidate>
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}" />
                    <input type="hidden" name="email" value="{{ old('email', $email ?? '') }}" />
                    @if(!empty($email))
                    <div class="reset-for-card animate-in delay-2">
                        <svg width="14" height="14" fill="none" stroke="var(--muted)" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        <span>{{ __('Resetting password for') }}
                            <strong style="color:var(--light);">{{ old('email', $email) }}</strong>
                        </span>
                    </div>
                    @endif
                    <div class="field-group animate-in delay-2">
                        <div class="field-label">
                            <span>{{ __('New Password') }}</span>
                        </div>
                        <div class="pw-wrap">
                            <input type="password" id="new-pw" name="password" class="auth-input pr @error('password') input-error @enderror" placeholder="{{ __('Min. 8 characters') }}" oninput="checkNewStrength(this.value); checkMatch();" required autocomplete="new-password" autofocus />
                            <button type="button" class="pw-toggle" onclick="toggleNewPw('new-pw','eye-new-show','eye-new-hide')" tabindex="-1">
                                <svg id="eye-new-show" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-new-hide" style="display:none;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <div class="strength-bar">
                            <div class="strength-seg" id="ns1"></div>
                            <div class="strength-seg" id="ns2"></div>
                            <div class="strength-seg" id="ns3"></div>
                            <div class="strength-seg" id="ns4"></div>
                        </div>
                        <div class="strength-label" id="new-strength-text"></div>
                        @error('password')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field-group animate-in delay-3">
                        <div class="field-label">
                            <span>{{ __('Confirm New Password') }}</span>
                        </div>
                        <div class="pw-wrap">
                            <input type="password" id="confirm-pw" name="password_confirmation" class="auth-input pr" placeholder="{{ __('Repeat your password') }}" oninput="checkMatch()" required autocomplete="new-password" />
                            <button type="button" class="pw-toggle" onclick="toggleNewPw('confirm-pw','eye-cf-show','eye-cf-hide')" tabindex="-1">
                                <svg id="eye-cf-show" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-cf-hide" style="display:none;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <div class="match-indicator" id="match-indicator">
                            <div class="match-dot"></div>
                            <span id="match-text"></span>
                        </div>
                    </div>
                    <div style="background:var(--card);border:1px solid var(--border);padding:14px 16px;margin-bottom:20px;border-radius:2px;" class="animate-in delay-3">
                        <p style="font-family:var(--font-sans);font-size:0.68rem;font-weight:600;letter-spacing:0.15em;text-transform:uppercase;color:var(--muted);margin-bottom:10px;">
                            {{ __('Password requirements') }}
                        </p>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <div class="pw-rule" id="rule-len">
                                <span class="rule-dot">○</span>
                                <span>{{ __('At least 8 characters') }}</span>
                            </div>
                            <div class="pw-rule" id="rule-upper">
                                <span class="rule-dot">○</span>
                                <span>{{ __('One uppercase letter') }}</span>
                            </div>
                            <div class="pw-rule" id="rule-num">
                                <span class="rule-dot">○</span>
                                <span>{{ __('One number') }}</span>
                            </div>
                            <div class="pw-rule" id="rule-special">
                                <span class="rule-dot">○</span>
                                <span>{{ __('One special character (!@#$%…)') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="newsletter-row animate-in delay-4">
                        <input type="checkbox" class="check-box" id="signout-all" name="logout_other_devices" value="1" checked />
                        <label for="signout-all" class="check-label">
                            <strong style="color:var(--light);">{{ __('Sign out all devices') }}</strong>
                            — {{ __('Recommended for security') }}
                        </label>
                    </div>
                    <button type="submit" class="btn-submit animate-in delay-4" id="reset-submit-btn">
                        {{ __('Update Password & Sign In') }}
                        <svg style="display:inline;margin-left:8px;vertical-align:-2px;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </button>

                </form>
            </div>

        </div>

        @include('auth.partials.form-footer')

    </div>
</div>
@endsection
