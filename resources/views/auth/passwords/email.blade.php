<x-layouts.guest :ogTitle="seo_title('Reset Your Password')">
    <div class="space2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-xl-7 col-lg-6">
                    <div class="card quote-form-box">
                        <div class="text-center card-header">
                            <img src="{{ asset('storage/'. $global['logo']?->image) }}" alt="Password Recovery" style="max-width: 200px;" class="mb-3">
                            <h4 class="form-title">{{ __("No Worries, It Happens!") }}</h4>
                            <p class="form-description text-muted">
                                {{ __("We've all been there. Just enter your email address below and we'll send you a secure link to get back into your account.") }}
                            </p>

                            <div class="mt-2 security-badge info">
                                <small class="text-info">
                                    <i class="fas fa-envelope-open-text me-1"></i>
                                    {{ __('Quick & Secure Recovery') }}
                                </small>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (session('status'))
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <div>
                                    <strong>{{ __('Email Sent Successfully!') }}</strong><br>
                                    <small>{{ session('status') }}</small>
                                </div>
                            </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}" class="contact-form">
                                @csrf

                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="email" class="mb-2 form-label text-start d-block">
                                            <i class="fas fa-envelope me-1"></i>
                                            {{ __('Your Email Address') }}
                                        </label>
                                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Enter the email you used to sign up') }}" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                        <div class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                        @enderror
                                        <small class="mt-1 form-text text-muted">
                                            {{ __('We\'ll send the reset link to this email address') }}
                                        </small>
                                    </div>

                                    <div class="form-btn col-12 d-flex justify-content-center flex-column align-items-center">
                                        <button type="submit" class="mb-3 th-btn btn-lg w-100">
                                            <i class="fas fa-paper-plane me-2"></i>
                                            {{ __("Send Me the Reset Link") }}
                                            <i class="fas fa-arrow-right ms-2"></i>
                                        </button>

                                        <div class="text-center alternative-actions">
                                            <p class="mb-2 text-muted">
                                                <small>{{ __('Remember your password now?') }}</small>
                                            </p>
                                            <a href="{{ route('login') }}" class="mb-2 text-decoration-none fw-semibold d-inline-block">
                                                <i class="fas fa-sign-in-alt me-1"></i>
                                                {{ __('Back to Login') }}
                                            </a>
                                            <br>
                                            @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-muted">
                                                <i class="fas fa-user-plus me-1"></i>
                                                {{ __('Don\'t have an account? Sign up') }}
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 row">
                                    <div class="col-12">
                                        <div class="alert alert-info d-flex align-items-start" role="alert">
                                            <i class="mt-1 fas fa-info-circle me-2"></i>
                                            <div>
                                                <small>
                                                    <strong>{{ __('What happens next?') }}</strong><br>
                                                    {{ __('1. Check your email inbox (and spam folder just in case)') }}<br>
                                                    {{ __('2. Click the secure reset link we send you') }}<br>
                                                    {{ __('3. Create your new password and you\'re all set!') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
