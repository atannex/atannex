@extends('layouts.app')

@section('title', seo_title('Join Our Community!'))

@section('content')
<div class="flex items-center justify-center min-h-screen wrapper">
    <div class="w-full max-w-2xl form-panel">

        @include('auth.partials.form-topbar')

        <div class="form-body">
            <div class="progress-steps animate-in delay-1" id="progress-steps">
                <div class="step active" id="step-1">
                    <div class="step-num">01</div>
                    <div class="step-label">{{ __('Account') }}</div>
                </div>
                <div class="step-connector"></div>
                <div class="step inactive" id="step-2">
                    <div class="step-num">02</div>
                    <div class="step-label">{{ __('Preferences') }}</div>
                </div>
                <div class="step-connector"></div>
                <div class="step inactive" id="step-3">
                    <div class="step-num">03</div>
                    <div class="step-label">{{ __('Done') }}</div>
                </div>
            </div>

            @if (Route::has('register'))
            <form method="POST" action="{{ route('register') }}" id="register-form" novalidate>
                @csrf
                <div id="form-step-1">
                    <div class="animate-in delay-1">
                        <p class="form-eyebrow">{{ __('Step 1 of 2') }}</p>
                        <h2 class="form-title">{{ __('Create your account.') }}</h2>
                        <p class="form-sub">{{ __("It's free. No credit card needed.") }}</p>
                    </div>

                    <div class="or-divider animate-in delay-2">
                        <span>{{ __('or sign up with email') }}</span>
                    </div>

                    <div class="field-row animate-in delay-3" style="grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px;">
                        <div>
                            <div class="field-label">
                                <span>{{ __('First Name') }}</span>
                            </div>
                            <input type="text" id="fname" name="first_name" class="auth-input @error('first_name') border-red-500 @enderror" placeholder="{{ __('John') }}" value="{{ old('first_name') }}" required autocomplete="given-name" />
                            @error('first_name')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <div class="field-label">
                                <span>{{ __('Last Name') }}</span>
                            </div>
                            <input type="text" id="lname" name="last_name" class="auth-input @error('last_name') border-red-500 @enderror" placeholder="{{ __('Doe') }}" value="{{ old('last_name') }}" required autocomplete="family-name" />
                            @error('last_name')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="field-group animate-in delay-3" style="margin-top: 12px;">
                        <div class="field-label">
                            <span>{{ __('Email Address') }}</span>
                        </div>
                        <input type="email" id="email" name="email" class="auth-input @error('email') border-red-500 @enderror" placeholder="{{ __('you@example.com') }}" value="{{ old('email') }}" required autocomplete="email" />
                        @error('email')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px;">
                        <div class="field-group animate-in delay-4">
                            <div class="field-label">
                                <span>{{ __('Password') }}</span>
                            </div>
                            <div class="pw-wrap">
                                <input type="password" id="reg-pw" name="password" class="auth-input pr @error('password') border-red-500 @enderror" placeholder="{{ __('Min. 8 characters') }}" oninput="checkStrength(this.value)" required autocomplete="new-password" />
                                <button type="button" class="pw-toggle" onclick="togglePwVisibility('reg-pw','eye-show','eye-hide')" tabindex="-1" aria-label="{{ __('Toggle password visibility') }}">
                                    <svg id="eye-show" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg id="eye-hide" style="display:none;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <div class="strength-bar">
                                <div class="strength-seg" id="s1"></div>
                                <div class="strength-seg" id="s2"></div>
                                <div class="strength-seg" id="s3"></div>
                                <div class="strength-seg" id="s4"></div>
                            </div>
                            <div class="strength-label" id="strength-text"></div>
                            @error('password')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field-group animate-in delay-4">
                            <div class="field-label">
                                <span>{{ __('Confirm Password') }}</span>
                            </div>
                            <input type="password" name="password_confirmation" class="auth-input" placeholder="{{ __('Repeat your password') }}" required autocomplete="new-password" />
                        </div>
                    </div>

                    <button type="button" class="btn-submit animate-in delay-5" style="margin-top: 16px;" onclick="goStep2()">
                        {{ __('Continue') }}
                        <svg style="display:inline;margin-left:8px;vertical-align:-2px;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </button>

                    <p class="switch-link animate-in delay-6">
                        {{ __('Already a member?') }}
                        <a href="{{ route('login') }}">{{ __('Sign in →') }}</a>
                    </p>
                </div>

                <div id="form-step-2" style="display:none;">
                    <div class="animate-in delay-1">
                        <p class="form-eyebrow">{{ __('Step 2 of 2') }}</p>
                        <h2 class="form-title">{{ __('Personalise your feed.') }}</h2>
                        <p class="form-sub">{{ __('Tell us what you care about.') }}</p>
                    </div>

                    <div class="field-group animate-in delay-2">
                        <div class="field-label">
                            <span>{{ __('Your Region') }}</span>
                        </div>
                        <select name="region" class="auth-input">
                            <option value="">{{ __('Select your region…') }}</option>
                            @foreach(['Africa','Asia Pacific','Europe','Latin America','Middle East','North America','South Asia'] as $region)
                            <option value="{{ $region }}" {{ old('region') === $region ? 'selected' : '' }}>
                                {{ $region }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field-group animate-in delay-2">
                        <div class="field-label">
                            <span>{{ __('Topics of Interest') }}</span>
                        </div>
                        <div class="interests-wrap" style="display: flex; flex-wrap: wrap; gap: 6px;">
                            @foreach(['Politics','Business','Technology','Sport','Culture & Arts','Health','Climate','Finance','Education','Travel'] as $topic)
                            <button type="button" class="interest-tag {{ in_array($topic, ['Business','Science','Health']) ? 'selected' : '' }}" data-topic="{{ $topic }}" style="font-size: 12px; padding: 4px 8px;" onclick="toggleTag(this)">
                                {{ $topic }}
                            </button>
                            <input type="checkbox" name="topics[]" value="{{ $topic }}" id="topic-{{ Str::slug($topic) }}" style="display:none;" {{ in_array($topic, ['Business','Science','Health']) ? 'checked' : '' }} />
                            @endforeach
                        </div>
                    </div>

                    <hr class="section-divider animate-in delay-3" style="margin: 16px 0;" />

                    <div class="field-group animate-in delay-4">
                        <div class="field-label">
                            <span>{{ __('Email Preferences') }}</span>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <div class="newsletter-row">
                                <input type="checkbox" class="check-box" id="nl-morning" name="newsletter[]" value="morning" checked />
                                <label for="nl-morning" class="check-label">
                                    <strong style="color: var(--light);">{{ __('Morning Briefing') }}</strong>
                                    <span style="font-size: 13px; color: var(--muted);">{{ __('Daily digest of top stories') }}</span>
                                </label>
                            </div>
                            <div class="newsletter-row">
                                <input type="checkbox" class="check-box" id="nl-breaking" name="newsletter[]" value="breaking" />
                                <label for="nl-breaking" class="check-label">
                                    <strong style="color: var(--light);">{{ __('Breaking Alerts') }}</strong>
                                    <span style="font-size: 13px; color: var(--muted);">{{ __('Instant updates on major news') }}</span>
                                </label>
                            </div>
                            <div class="newsletter-row">
                                <input type="checkbox" class="check-box" id="nl-weekly" name="newsletter[]" value="weekly" />
                                <label for="nl-weekly" class="check-label">
                                    <strong style="color: var(--light);">{{ __('Weekly Analysis') }}</strong>
                                    <span style="font-size: 13px; color: var(--muted);">{{ __('In-depth insights every Sunday') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider animate-in delay-4" style="margin: 16px 0;" />

                    <div class="field-group animate-in delay-5">
                        <div class="newsletter-row" style="align-items: flex-start;">
                            <input type="checkbox" class="check-box" id="agree" name="agree" value="1" style="margin-top: 2px;" />
                            <label for="agree" class="check-label" style="font-size: 13px; line-height: 1.5;">
                                {!! __("I agree to Atannex's <a href='#'>Terms of Service</a> and <a href='#'>Privacy Policy</a>. I confirm I am 16 or older.") !!}
                            </label>
                        </div>
                        @error('agree')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="btn-row-back-submit animate-in delay-5" style="margin-top: 20px; gap: 12px;">
                        <button type="button" class="btn-back" onclick="goBack()">
                            ← {{ __('Back') }}
                        </button>
                        <button type="submit" class="btn-submit" onclick="return handleRegister()">
                            {{ __('Create My Account') }}
                            <svg style="display:inline;margin-left:8px;vertical-align:-2px;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </div>

            </form>
            @endif

        </div>

        @include('auth.partials.form-footer')

    </div>

</div>
@endsection
