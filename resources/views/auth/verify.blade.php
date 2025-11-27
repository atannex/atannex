@extends('components.layouts.guest')

@section('og:title', seo_title('Almost There - Verify Your Email!'))

@section('guest')

<div class="space2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-xl-7 col-lg-6">
                <div class="card quote-form-box">
                    <div class="text-center card-header">
                        <img src="{{ asset('storage/'. $global['logo']?->image) }}" alt="Email Verification" style="max-width: 200px;" class="mb-3">

                        <div class="mb-3 verification-icon">
                            <i class="fas fa-envelope-open text-primary" style="font-size: 3rem;"></i>
                        </div>

                        <h4 class="mb-3 form-title">{{ __("You're Almost There!") }}</h4>

                        <p class="form-description text-muted">
                            {{ __('We\'ve sent a verification email to your inbox. Please check your email and click the verification link to activate your account.') }}
                        </p>

                        <div class="mt-3 security-badge">
                            <small class="text-info">
                                <i class="fas fa-shield-check me-1"></i>
                                {{ __('Email Security Verification') }}
                            </small>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('resent'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="fas fa-paper-plane me-2"></i>
                            <div>
                                <strong>{{ __('Email Sent Successfully!') }}</strong><br>
                                <small>{{ __('A fresh verification link has been sent to your email address.') }}</small>
                            </div>
                        </div>
                        @endif

                        <div class="mb-4 verification-steps">
                            <div class="alert alert-info">
                                <h6 class="mb-2">
                                    <i class="fas fa-list-check me-1"></i>
                                    {{ __('What to do next:') }}
                                </h6>
                                <ol class="mb-0 small">
                                    <li>{{ __('Check your email inbox (and spam/junk folder)') }}</li>
                                    <li>{{ __('Look for an email from us with the subject "Verify Your Email"') }}</li>
                                    <li>{{ __('Click the verification button in the email') }}</li>
                                    <li>{{ __('You\'ll be redirected back here and ready to go!') }}</li>
                                </ol>
                            </div>
                        </div>

                        <form action="{{ route('verification.resend') }}" method="POST" class="contact-form">
                            @csrf
                            <div class="row">
                                <div class="text-center col-12">
                                    <p class="mb-3 text-muted">
                                        {{ __('Didn\'t receive the email? No worries!') }}
                                    </p>

                                    <button type="submit" class="mb-3 th-btn btn-lg w-100">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        {{ __('Send Me Another Verification Email') }}
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="text-center card-footer bg-light">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            {{ __('Verification emails are usually delivered within a few minutes.') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
