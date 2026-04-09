@extends('layouts.app')

@section('title', seo_title('Welcome Back to Atannex!'))

@section('content')
<div class="flex items-center justify-center min-h-screen wrapper">
    <div class="w-full max-w-2xl form-panel">

        @include('auth.partials.form-topbar')

        <div class="form-body">
            <div class="animate-in delay-1">
                <p class="form-eyebrow">{{ __("Sign in to Atannex") }}</p>
                <h2 class="form-title">{{ __('Welcome back.') }}</h2>
                <p class="form-sub">{{ __("Your news is waiting. Sign in to continue.") }}</p>
            </div>

            <div class="social-login-grid animate-in delay-2" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 12px; margin-bottom: 16px;">
                <button type="button" class="social-login-btn" onclick="loginWithGoogle()" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 12px; border: 1px solid var(--border); border-radius: 4px; background: transparent; cursor: pointer; transition: all 0.2s;">
                    <svg width="20" height="20" viewBox="0 0 24 24" style="margin-bottom: 6px;">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                    </svg>
                    <span style="font-size: 12px; color: var(--light);">{{ __('Google') }}</span>
                </button>

                <button type="button" class="social-login-btn" onclick="loginWithApple()" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 12px; border: 1px solid var(--border); border-radius: 4px; background: transparent; cursor: pointer; transition: all 0.2s;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="color: #ccc; margin-bottom: 6px;">
                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z" />
                    </svg>
                    <span style="font-size: 12px; color: var(--light);">{{ __('Apple') }}</span>
                </button>

                <button type="button" class="social-login-btn" onclick="loginWithMicrosoft()" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 12px; border: 1px solid var(--border); border-radius: 4px; background: transparent; cursor: pointer; transition: all 0.2s;">
                    <svg width="20" height="20" viewBox="0 0 24 24" style="margin-bottom: 6px;">
                        <rect x="3" y="3" width="8" height="8" fill="#F25022" />
                        <rect x="13" y="3" width="8" height="8" fill="#7FBA00" />
                        <rect x="3" y="13" width="8" height="8" fill="#00A4EF" />
                        <rect x="13" y="13" width="8" height="8" fill="#FFB900" />
                    </svg>
                    <span style="font-size: 12px; color: var(--light);">{{ __('Microsoft') }}</span>
                </button>
            </div>

            <div class="or-divider animate-in delay-2">
                <span>{{ __("or sign in with email") }}</span>
            </div>

            @if (Route::has('login'))
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field-group animate-in delay-3" style="margin-top: 12px;">
                    <div class="field-label">
                        <span>{{ __("Email Address") }}</span>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="auth-input @error('email') is-invalid @enderror" placeholder="you@example.com">
                    @error('email')
                    <span class="block mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group animate-in delay-4" style="margin-top: 12px;">
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

                <div class="remember-row animate-in delay-5" style="margin-top: 12px;">
                    <input type="checkbox" class="check-box" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="check-label">
                        {{ __("Keep me signed in for 30 days") }}
                    </label>
                </div>

                <button class="btn-submit animate-in delay-4" style="margin-top: 16px;" onclick="handleLogin()">
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
