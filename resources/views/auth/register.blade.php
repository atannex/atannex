<x-layouts.guest :title="seo_title('Join Our Community!')">
    <div class="space2 d-flex justify-content-center align-items-center" style="min-height: 50vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-xl-7 col-lg-6">
                    <div class="card quote-form-box">
                        <div class="text-center card-header">
                            <img src="{{ asset('storage/' . $global['logo']?->image) }}" alt="Welcome" style="max-width: 200px;" class="mb-3">
                            <h4 class="mb-3 form-title">{{ __('Join Our Community!') }}</h4>
                            <p class="form-description text-muted">
                                {{ __('Ready to get started? Create your account and unlock exclusive features designed just for you.') }}
                            </p>

                            <div class="mt-2 security-badge primary">
                                <small class="text-primary">
                                    <i class="fas fa-user-shield me-1"></i>
                                    {{ __('Quick & Secure Registration') }}
                                </small>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('register') }}" method="POST" class="contact-form" id="registerForm">
                                @csrf

                                <!-- Hidden input for timezone -->
                                <input type="hidden" name="timezone" id="timezone">

                                <div class="mb-4 form-group">
                                    <label for="name" class="form-label fw-medium form-text text-start d-block">
                                        <i class="fas fa-user me-1"></i>
                                        {{ __('What\'s your full name?') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" placeholder="{{ __('Enter your first and last name') }}" value="{{ old('name') }}" required autofocus>
                                    <div class="form-text">{{ __('We\'ll use this to personalize your experience') }}</div>
                                    @error('name')
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4 form-group">
                                    <label for="email" class="form-label fw-medium form-text text-start d-block">
                                        <i class="fas fa-envelope me-1"></i>
                                        {{ __('Your email address') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="{{ __('Enter your best email address') }}" value="{{ old('email') }}" required>
                                    <div class="form-text">{{ __('We\'ll send you a quick confirmation email - check your spam folder too!') }}</div>
                                    @error('email')
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4 form-group">
                                    <label for="password" class="form-label fw-medium form-text text-start d-block">
                                        <i class="fas fa-lock me-1"></i>
                                        {{ __('Create a secure password') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg position-relative">
                                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror pe-5" placeholder="{{ __('Make it strong and memorable') }}" required>
                                        <button type="button" class="p-0 bg-transparent border-0 btn position-absolute top-50 end-0 translate-middle-y me-3" onclick="togglePasswordVisibility('password', 'toggle-icon-password')" aria-label="{{ __('Toggle password visibility') }}">
                                            <i class="fas fa-eye text-muted fs-5" id="toggle-icon-password"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">{{ __('At least 8 characters with letters, numbers, and symbols - your account\'s best friend!') }}</div>
                                    @error('password')
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4 form-group">
                                    <label for="password-confirm" class="form-label fw-medium form-text text-start d-block">
                                        <i class="fas fa-check-double me-1"></i>
                                        {{ __('Confirm your password') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" id="password-confirm" name="password_confirmation" class="form-control form-control-lg" placeholder="{{ __('Type it again to make sure we got it right') }}" required>
                                    <div class="form-text">{{ __('Just to make sure there are no typos!') }}</div>
                                </div>

                                <div class="mb-4 form-group">
                                    <div class="form-check align-items-start">
                                        <input class="form-check-input mt-1 @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" {{ old('terms') ? 'checked' : '' }} required>
                                        <label class="form-check-label form-text" for="terms">
                                            <i class="fas fa-handshake me-1 text-muted"></i>
                                            {{ __('I agree to the') }}
                                            <a href="{{ route('document.index', ['type' => 'terms']) }}" target="_blank" class="text-primary">{{ __('Terms of Service') }}</a>
                                            {{ __('and') }}
                                            <a href="{{ route('document.index', ['type' => 'privacy']) }}" target="_blank" class="text-primary">{{ __('Privacy Policy') }}</a>
                                        </label>
                                        <div class="form-text">
                                            {{ __('Don\'t worry, we keep it simple and fair!') }}
                                        </div>
                                    </div>
                                    @error('terms')
                                    <div class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-4 form-btn d-grid">
                                    <button type="submit" class="mb-3 th-btn btn-lg w-100">
                                        <i class="fas fa-rocket me-2"></i>
                                        {{ __('Create My Account') }}
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>

                                <p class="mt-3 mb-0 form-messages"></p>

                                <div class="text-center alternative-actions">
                                    <div class="existing-user-section">
                                        <p class="mb-2 text-muted">{{ __("Already part of our community?") }}</p>
                                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                                            <i class="fas fa-sign-in-alt me-1"></i>
                                            {{ __('Sign In Instead') }}
                                        </a>
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
        document.addEventListener('DOMContentLoaded', function() {
            const timezoneInput = document.getElementById('timezone');
            if (timezoneInput) {
                timezoneInput.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
            }
        });

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