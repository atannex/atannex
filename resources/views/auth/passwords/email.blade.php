@extends('layouts.app')

@section('title', seo_title('Reset Your Password'))

@section('content')

@php

$status = session('status');

$sentMessage = __('passwords.sent');
$throttleMessage = __('passwords.throttled');

$step = $status === $sentMessage ? 2 : 1;
$isThrottled = $status === $throttleMessage;

$sentEmail = old('email', request('email'));
@endphp

<div class="flex items-center justify-center min-h-screen wrapper">
    <div class="w-full max-w-2xl form-panel">

        @include('auth.partials.form-topbar')

        <div class="form-body">
            @if($step === 1)
            <div id="step-request">

                <div class="reset-steps animate-in delay-1">
                    <div class="reset-pip active"></div>
                    <div class="reset-pip"></div>
                    <div class="reset-pip"></div>
                </div>

                <div class="animate-in delay-1">
                    <p class="reset-step-label">{{ __('Step 1 of 3') }} &nbsp;·&nbsp; {{ __('Enter Email') }}</p>
                    <p class="form-eyebrow">{{ __('Password Recovery') }}</p>
                    <h2 class="form-title">{{ __('Forgot your password?') }}</h2>
                    <p class="form-sub">
                        {{ __("No problem. Enter the email address on your Atannex account and we'll send you a secure reset link.") }}
                    </p>
                </div>
                @if($isThrottled)
                <div class="fp-info-banner animate-in delay-1" style="margin-top: 12px;">
                    <svg width="16" height="16" fill="none" stroke="#ccaa00" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ __('Please wait a few minutes before requesting another reset link.') }}</span>
                </div>
                @endif
                @if($errors->any())
                <div class="auth-error-banner animate-in delay-1" style="margin-top: 12px;">
                    <svg width="16" height="16" fill="none" stroke="#CC4400" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif
                <form method="POST" action="{{ route('password.email') }}" id="forgot-form" novalidate>
                    @csrf
                    <div class="field-group animate-in delay-2" style="margin-top: 12px;">
                        <div class="field-label">
                            <span>{{ __('Email Address') }}</span>
                        </div>
                        <input type="email" id="reset-email" name="email" class="auth-input @error('email') input-error @enderror" placeholder="{{ __('you@example.com') }}" value="{{ old('email') }}" required autocomplete="email" autofocus oninput="validateEmail(this)" />
                        <div id="email-hint" class="fp-email-hint"></div>
                        @error('email')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field-group animate-in delay-3" style="margin-top: 12px;">
                        <div class="field-label">
                            <span>{{ __('Signed in with') }}</span>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
                            <label class="account-type-btn selected" id="type-email" onclick="selectType('email')">
                                <input type="radio" name="actype" value="email" checked style="display:none" />
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                                <span>{{ __('Email') }}</span>
                            </label>
                            <label class="account-type-btn" id="type-google" onclick="selectType('google')">
                                <input type="radio" name="actype" value="google" style="display:none" />
                                <svg width="16" height="16" viewBox="0 0 24 24">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                                </svg>
                                <span>{{ __('Google') }}</span>
                            </label>
                            <label class="account-type-btn" id="type-apple" onclick="selectType('apple')">
                                <input type="radio" name="actype" value="apple" style="display:none" />
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="color:#ccc;">
                                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z" />
                                </svg>
                                <span>{{ __('Apple') }}</span>
                            </label>

                        </div>
                        <div id="social-notice" class="fp-social-notice"></div>
                    </div>
                    <button type="submit" class="btn-submit animate-in delay-3" id="send-btn" style="margin-top: 16px;">
                        {{ __('Send Reset Link') }}
                        <svg style="display:inline;margin-left:8px;vertical-align:-2px;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                        </svg>
                    </button>
                </form>
                <p class="switch-link animate-in delay-4" style="margin-top:12px;">
                    {{ __('Remembered it?') }}
                    <a href="{{ route('login') }}">{{ __('Back to Sign In →') }}</a>
                </p>

            </div>
            @endif
            @if($step === 2)
            <div id="step-sent">
                <div class="reset-steps animate-in delay-1">
                    <div class="reset-pip done"></div>
                    <div class="reset-pip active"></div>
                    <div class="reset-pip"></div>
                </div>
                <p class="reset-step-label animate-in delay-1">
                    {{ __('Step 2 of 3') }} &nbsp;·&nbsp; {{ __('Check Your Inbox') }}
                </p>
                <div class="email-sent-box animate-in delay-2">
                    <div class="email-sent-icon">
                        <svg width="28" height="28" fill="none" stroke="#CC0000" stroke-width="1.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <div class="email-sent-title">{{ __('Reset link sent!') }}</div>
                    <p class="email-sent-desc">{{ __("We've sent a secure password reset link to") }}</p>
                    <div class="email-sent-address">{{ $sentEmail ?: __('your email address') }}</div>
                    <p class="email-sent-desc">
                        {{ __('The link expires in') }}
                        <strong style="color:var(--light);">{{ __('15 minutes') }}</strong>
                        {{ __('and can only be used once.') }}
                    </p>
                </div>
                @if($sentEmail)
                @php
                $domain = strtolower(substr(strrchr($sentEmail, '@'), 1));
                $provider = match(true) {
                str_contains($domain, 'gmail.com') => 'gmail',
                str_contains($domain, 'googlemail.com') => 'gmail',
                str_contains($domain, 'outlook.com') => 'outlook',
                str_contains($domain, 'hotmail.com') => 'outlook',
                str_contains($domain, 'hotmail.co') => 'outlook',
                str_contains($domain, 'live.com') => 'outlook',
                str_contains($domain, 'msn.com') => 'outlook',
                str_contains($domain, 'yahoo.com') => 'yahoo',
                str_contains($domain, 'yahoo.co') => 'yahoo',
                str_contains($domain, 'ymail.com') => 'yahoo',
                default => null,
                };
                @endphp
                @if($provider)
                <div class="animate-in delay-2">
                    <p class="fp-provider-label">{{ __('Open your inbox directly') }}</p>
                    <div class="fp-provider-links" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 8px;">
                        @if($provider === 'gmail')
                        <a href="https://mail.google.com" target="_blank" rel="noopener noreferrer" class="fp-provider-btn">
                            <svg width="13" height="13" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                            </svg>
                            {{ __('Open Gmail') }}
                        </a>
                        <a href="https://mail.google.com/#search/from%3Anoreply%40atannex.com" target="_blank" rel="noopener noreferrer" class="fp-provider-btn">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            {{ __('Search') }}
                        </a>
                        <a href="https://mail.google.com/#spam" target="_blank" rel="noopener noreferrer" class="fp-provider-btn">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.646c-1.73 0-2.813-1.874-1.948-3.374L10.052 3.378c.866-1.5 3.032-1.5 3.898 0l7.353 12.748z" />
                            </svg>
                            {{ __('Check Spam') }}
                        </a>
                        @elseif($provider === 'outlook')
                        <a href="https://outlook.live.com/mail/inbox" target="_blank" rel="noopener noreferrer" class="fp-provider-btn">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            {{ __('Open Outlook') }}
                        </a>
                        <a href="https://outlook.live.com/mail/junkemail" target="_blank" rel="noopener noreferrer" class="fp-provider-btn">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.646c-1.73 0-2.813-1.874-1.948-3.374L10.052 3.378c.866-1.5 3.032-1.5 3.898 0l7.353 12.748z" />
                            </svg>
                            {{ __('Check Junk') }}
                        </a>
                        <a href="https://outlook.live.com/mail/archive" target="_blank" rel="noopener noreferrer" class="fp-provider-btn">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            {{ __('Archive') }}
                        </a>
                        @elseif($provider === 'yahoo')
                        <a href="https://mail.yahoo.com" target="_blank" rel="noopener noreferrer" class="fp-provider-btn">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            {{ __('Open Yahoo Mail') }}
                        </a>
                        <a href="https://mail.yahoo.com/b/folders/67" target="_blank" rel="noopener noreferrer" class="fp-provider-btn">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.646c-1.73 0-2.813-1.874-1.948-3.374L10.052 3.378c.866-1.5 3.032-1.5 3.898 0l7.353 12.748z" />
                            </svg>
                            {{ __('Check Spam') }}
                        </a>
                        @endif
                    </div>
                </div>
                @endif
                @endif
                <div class="resend-row animate-in delay-3" style="margin-top: 16px;">
                    <span class="resend-label">{{ __("Didn't receive it?") }}</span>
                    <div class="resend-actions">
                        <span class="resend-timer" id="resend-timer"></span>
                        <form method="POST" action="{{ route('password.email') }}" id="resend-form">
                            @csrf
                            <input type="hidden" name="email" value="{{ $sentEmail }}" />
                            <button type="submit" class="resend-btn" id="resend-btn" disabled>
                                {{ __('Resend') }}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="fp-tips-box animate-in delay-3" style="margin-top: 16px;">
                    <p class="fp-tips-heading">{{ __("Can't find the email?") }}</p>
                    <ul class="fp-tips-list">
                        <li>
                            <span class="fp-tip-arrow">→</span>
                            <span>{{ __('Check your Spam or Junk folder') }}</span>
                        </li>
                        <li>
                            <span class="fp-tip-arrow">→</span>
                            <span>{{ __('Make sure you typed your email correctly') }}</span>
                        </li>
                        <li>
                            <span class="fp-tip-arrow">→</span>
                            <span>
                                {{ __('Add') }}
                                <span class="fp-tip-email">{{ __("noreply@atannex.com") }}</span>
                                {{ __('to your contacts') }}
                            </span>
                        </li>
                        <li>
                            <span class="fp-tip-arrow">→</span>
                            <span>{{ __('Allow up to 2 minutes for delivery') }}</span>
                        </li>
                    </ul>
                </div>

                <p class="switch-link animate-in delay-4">
                    {{ __('Wrong email?') }}
                    <a href="{{ route('password.request') }}">{{ __('Try again →') }}</a>
                </p>

            </div>
            @endif

        </div>

        @include('auth.partials.form-footer')

    </div>
</div>

@endsection
