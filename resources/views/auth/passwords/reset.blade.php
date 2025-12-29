@extends('components.layouts.guest')

@section('og:title', seo_title('Create Your New Password'))

@section('guest')
<div class="space2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-xl-7 col-lg-6">
                <div class="card quote-form-box">
                    <div class="text-center card-header">
                        <img src="{{ asset('storage/'. $global['logo']?->image) }}" alt="Password Reset" style="max-width: 200px;" class="mb-3">
                        <h4 class="form-title">{{ __("Almost There! Set Your New Password") }}</h4>
                        <p class="form-description text-muted">
                            {{ __("You're just one step away from securing your account with a fresh new password.") }}
                        </p>
                        <div class="mt-2 security-badge">
                            <small class="text-success">
                                <i class="fas fa-shield-alt me-1"></i>
                                {{ __('Secure Password Reset') }}
                            </small>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('password.update') }}" method="POST" class="contact-form" novalidate>
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ old('email', request()->email) }}">

                            <div class="row">

                                <div class="mb-4 col-12 form-group position-relative">
                                    <label for="password" class="mb-2 form-label fw-medium text-start d-block">
                                        <i class="fas fa-key me-1"></i>
                                        {{ __('Create New Password') }}
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group position-relative">
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Enter your new secure password') }}" autocomplete="new-password" required>

                                        <button type="button" class="p-0 bg-transparent border-0 btn position-absolute top-50 end-0 translate-middle-y me-3" onclick="togglePasswordVisibility('password', 'toggle-icon-password')" aria-label="{{ __('Toggle password visibility') }}">
                                            <i class="fas fa-eye text-muted" id="toggle-icon-password"></i>
                                        </button>
                                    </div>

                                    @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                    @enderror

                                    <div class="mt-1 form-text">
                                        {{ __('Use at least 8 characters with a mix of letters, numbers, and symbols.') }}
                                    </div>

                                    <div class="mt-2" id="password-strength-container" style="display: none;">
                                        <small id="password-strength-text" class="form-text fw-medium"></small>

                                        <div class="mt-1 progress" style="height: 5px;">
                                            <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

                                        <div id="password-feedback" class="mt-2 small"></div>
                                    </div>
                                </div>

                                <div class="mb-4 col-12 form-group position-relative">
                                    <label for="password-confirm" class="mb-2 form-label fw-medium text-start d-block">
                                        <i class="fas fa-check-double me-1"></i>
                                        {{ __('Confirm Your New Password') }}
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group position-relative">
                                        <input type="password" name="password_confirmation" id="password-confirm" class="form-control" placeholder="{{ __('Type your password again to confirm') }}" autocomplete="new-password" required>

                                        <button type="button" class="p-0 bg-transparent border-0 btn position-absolute top-50 end-0 translate-middle-y me-3" onclick="togglePasswordVisibility('password-confirm', 'toggle-icon-confirm')" aria-label="{{ __('Toggle password visibility') }}">
                                            <i class="fas fa-eye text-muted" id="toggle-icon-confirm"></i>
                                        </button>
                                    </div>

                                    <small id="password-match-text" class="mt-1 form-text"></small>
                                </div>

                                <div class="col-12 form-btn d-flex flex-column align-items-center">
                                    <button type="submit" class="mb-3 th-btn btn-lg w-100">
                                        <i class="fas fa-lock me-2"></i>
                                        {{ __('Update My Password') }}
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </button>

                                    <a href="{{ route('login') }}" class="fw-semibold text-muted text-decoration-none">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        {{ __('Back to login') }}
                                    </a>
                                </div>

                            </div>

                            <div class="mt-4 row">
                                <div class="col-12">
                                    <div class="alert alert-success d-flex align-items-start" role="alert">
                                        <i class="mt-1 fas fa-lightbulb me-2"></i>
                                        <small>
                                            <strong>{{ __('Pro Tip:') }}</strong>
                                            {{ __('Use a passphrase — a few unrelated words are easier to remember and far more secure.') }}
                                        </small>
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

@endsection
