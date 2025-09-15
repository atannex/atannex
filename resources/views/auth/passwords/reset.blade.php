<x-layouts.guest :title="seo_title('Create Your New Password')">
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
                            <form action="{{ route('password.update') }}" method="POST" class="contact-form">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="email" class="mb-2 form-label text-start d-block">
                                            <i class="fas fa-envelope me-1"></i>
                                            {{ __('Your Email Address') }}
                                        </label>
                                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Enter your email address') }}" value="{{ old('email', request()->email) }}" required autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                        <small class="mt-1 form-text text-muted">
                                            {{ __('This should be the email you used to request the password reset') }}
                                        </small>
                                    </div>

                                    <div class="form-group col-md-12 position-relative">
                                        <label for="password" class="mb-2 form-label text-start d-block">
                                            <i class="fas fa-key me-1"></i>
                                            {{ __('Create New Password') }}
                                        </label>
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Enter your new secure password') }}" required autocomplete="new-password">
                                        <span class="toggle-password" onclick="togglePasswordVisibility('password', 'toggle-icon-password')" style="position:absolute; right: 15px; top: 70%; transform: translateY(-50%); cursor:pointer;">
                                            <i class="fas fa-eye" id="toggle-icon-password"></i>
                                        </span>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                        <small class="mt-1 form-text text-muted">
                                            {{ __('Use at least 8 characters with a mix of letters, numbers, and symbols') }}
                                        </small>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="password-confirm" class="mb-2 form-label text-start d-block">
                                            <i class="fas fa-check-double me-1"></i>
                                            {{ __('Confirm Your New Password') }}
                                        </label>
                                        <input type="password" name="password_confirmation" id="password-confirm" class="form-control" placeholder="{{ __('Type your password again to confirm') }}" required>
                                        <small class="mt-1 form-text text-muted">
                                            {{ __('Make sure both passwords match perfectly') }}
                                        </small>
                                    </div>

                                    <div class="form-btn col-12 d-flex justify-content-center flex-column align-items-center">
                                        <button type="submit" class="mb-3 th-btn btn-lg w-100">
                                            <i class="fas fa-lock me-2"></i>
                                            {{ __("Update My Password") }}
                                            <i class="fas fa-arrow-right ms-2"></i>
                                        </button>

                                        <div class="text-center alternative-actions">
                                            <a href="{{ route('login') }}" class="text-decoration-none text-muted">
                                                <i class="fas fa-arrow-left me-1"></i>
                                                {{ __('Back to login') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 row">
                                    <div class="col-12">
                                        <div class="alert alert-success d-flex align-items-center" role="alert">
                                            <i class="fas fa-lightbulb me-2"></i>
                                            <small>
                                                <strong>{{ __('Pro Tip:') }}</strong>
                                                {{ __('Choose a password that\'s easy for you to remember but hard for others to guess. Consider using a passphrase!') }}
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

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

    </script>
</x-layouts.guest>
