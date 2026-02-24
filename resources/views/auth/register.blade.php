@extends('layouts.app')

@section('content')
<div class="wrapper">
    <div class="editorial-panel editorial-panel--register">
        <div class="glow-blob-register"></div>
        <div class="ep-logo">
            <div>
                <img class="dark-img img-fluid" src="{{ asset('auth.png') }}" alt="{{ config('app.name', 'Website') }}" style="max-width: 98px; height: 98px; object-fit: cover;">
            </div>
        </div>

        <div class="ticker-wrap">
            <div class="ticker-track">
                <span class="ticker-item">🔴 Climate Emergency Summit Begins in Geneva <span class="ticker-dot">•</span> Inflation Hits 3-Year Low <span class="ticker-dot">•</span> Tech Giants Report Record Profits <span class="ticker-dot">•</span> ATANNEX: War Crimes Tribunal Opens <span class="ticker-dot">•</span></span>
                <span class="ticker-item">🔴 Climate Emergency Summit Begins in Geneva <span class="ticker-dot">•</span> Inflation Hits 3-Year Low <span class="ticker-dot">•</span> Tech Giants Report Record Profits <span class="ticker-dot">•</span> ATANNEX: War Crimes Tribunal Opens <span class="ticker-dot">•</span></span>
            </div>
        </div>

        <div class="ep-hero">
            <div class="ep-eyebrow">Start your free account</div>
            <h1 class="ep-headline">Join the<br />Atannex<br /><em>Community.</em></h1>
            <p class="ep-desc">Free access to world-class journalism. Personalise your feed. Read on any device.</p>

            <div class="perks-list">
                <div class="perk-item">
                    <div class="perk-icon">
                        <svg width="18" height="18" fill="none" stroke="#CC0000" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div>
                        <div class="perk-title">Breaking News, Instantly</div>
                        <div class="perk-sub">Real-time alerts on the stories that matter to you</div>
                    </div>
                </div>
                <div class="perk-item">
                    <div class="perk-icon">
                        <svg width="18" height="18" fill="none" stroke="#CC0000" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                    </div>
                    <div>
                        <div class="perk-title">Personalised News Feed</div>
                        <div class="perk-sub">Choose your topics and get news curated just for you</div>
                    </div>
                </div>
                <div class="perk-item">
                    <div class="perk-icon">
                        <svg width="18" height="18" fill="none" stroke="#CC0000" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <div>
                        <div class="perk-title">Ad-Light Experience</div>
                        <div class="perk-sub">Read without interruption. No paywalls on breaking news</div>
                    </div>
                </div>
                <div class="perk-item">
                    <div class="perk-icon">
                        <svg width="18" height="18" fill="none" stroke="#CC0000" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20" /></svg>
                    </div>
                    <div>
                        <div class="perk-title">190+ Countries Covered</div>
                        <div class="perk-sub">Local and global news from our network of correspondents</div>
                    </div>
                </div>
            </div>

            <div class="testimonial">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"Atannex is the first thing I open every morning. The reporting is sharp, fair, and always ahead of the curve."</p>
                <div class="testimonial-author">— Sarah M., Senior Policy Analyst, Brussels</div>
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
                        <h2 class="form-title">{{ __('Create your') }}<br />{{ __('account.') }}</h2>
                        <p class="form-sub">{{ __("It's free. No credit card needed.") }}</p>
                    </div>

                    @include('auth.partials.social-grid')

                    <div class="or-divider animate-in delay-2">
                        <span>{{ __('or sign up with email') }}</span>
                    </div>
                    <div class="field-row animate-in delay-3">
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

                    <div class="field-group animate-in delay-3">
                        <div class="field-label">
                            <span>{{ __('Email Address') }}</span>
                        </div>
                        <input type="email" id="email" name="email" class="auth-input @error('email') border-red-500 @enderror" placeholder="{{ __('you@example.com') }}" value="{{ old('email') }}" required autocomplete="email" />
                        @error('email')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

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
                    <button type="button" class="btn-submit animate-in delay-5" onclick="goStep2()">
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
                        <h2 class="form-title">{{ __('Personalise') }}<br />{{ __('your feed.') }}</h2>
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
                    <div class="field-group animate-in delay-3">
                        <div class="field-label">
                            <span>{{ __('Topics of Interest') }}</span>
                        </div>
                        <div class="interests-wrap">
                            @foreach(['Politics','Business','Technology','Science','Sport','Culture & Arts','Health','Climate','Finance','Education','War & Conflict','Travel'] as $topic)
                            <button type="button" class="interest-tag {{ in_array($topic, ['Business','Science','Health']) ? 'selected' : '' }}" data-topic="{{ $topic }}" onclick="toggleTag(this)">
                                {{ $topic }}
                            </button>
                            <input type="checkbox" name="topics[]" value="{{ $topic }}" id="topic-{{ Str::slug($topic) }}" style="display:none;" {{ in_array($topic, ['Business','Science','Health']) ? 'checked' : '' }} />
                            @endforeach
                        </div>
                    </div>

                    <hr class="section-divider animate-in delay-3" />
                    <div class="newsletter-row animate-in delay-4">
                        <input type="checkbox" class="check-box" id="nl-morning" name="newsletter[]" value="morning" checked />
                        <label for="nl-morning" class="check-label">
                            <strong style="color:var(--light);">{{ __('Morning Briefing') }}</strong>
                            — {{ __('Daily digest of the top stories') }}
                        </label>
                    </div>
                    <div class="newsletter-row animate-in delay-4">
                        <input type="checkbox" class="check-box" id="nl-breaking" name="newsletter[]" value="breaking" />
                        <label for="nl-breaking" class="check-label">
                            <strong style="color:var(--light);">{{ __('Breaking Alerts') }}</strong>
                            — {{ __('Instant push when big news breaks') }}
                        </label>
                    </div>
                    <div class="newsletter-row animate-in delay-4">
                        <input type="checkbox" class="check-box" id="nl-weekly" name="newsletter[]" value="weekly" />
                        <label for="nl-weekly" class="check-label">
                            <strong style="color:var(--light);">{{ __('Weekly Analysis') }}</strong>
                            — {{ __('In-depth editorial every Sunday') }}
                        </label>
                    </div>

                    <hr class="section-divider animate-in delay-4" />

                    <div class="newsletter-row animate-in delay-5" style="margin-bottom:24px;">
                        <input type="checkbox" class="check-box" id="agree" name="agree" value="1" />
                        <label for="agree" class="check-label">
                            {!! __("I agree to Atannex's <a href='#'>Terms of Service</a> and <a href='#'>Privacy Policy</a>. I confirm I am 16 or older.") !!}
                        </label>
                        @error('agree')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="btn-row-back-submit animate-in delay-5">
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
