<x-layouts.guest :title="seo_title('Welcome Back!')">
     <div class="space2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-xl-7 col-lg-6">
                    <div class="shadow card quote-form-box">
                        <div class="p-4 text-center card-header">
                            <img src="{{ asset('storage/' . $global['logo']?->image) }}" alt="Welcome Back" style="max-width: 200px;" class="mb-3">
                            <h4 class="mb-3 form-title">{{ __('Welcome Back!') }}</h4>
                            <p class="form-description text-muted">
                                {{ __('Great to see you again! Sign in to access your dashboard and continue where you left off.') }}
                            </p>

                            <div class="mt-2 security-badge success">
                                <small class="text-success">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    {{ __('Secure Login') }}
                                </small>
                            </div>
                        </div>

                        <div class="pt-0 card-body">
                            <form action="{{ route('login') }}" method="POST" class="contact-form">
                                @csrf

                                {{-- Email --}}
                                <div class="mb-4 form-group">
                                    <label for="email" class="form-label fw-medium form-text text-start d-block">
                                        <i class="fas fa-envelope me-1"></i>
                                        {{ __('Your Email Address') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" id="email" placeholder="{{ __('Enter your email address') }}" value="{{ old('email') }}" required autofocus>

                                    <div class="form-text">
                                        {{ __('The email address you used when signing up') }}
                                    </div>

                                    @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                {{-- Password --}}
                                <div class="mb-4 form-group">
                                    <label for="password" class="form-label fw-medium form-text text-start d-block">
                                        <i class="fas fa-lock me-1"></i>
                                        {{ __('Your Password') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg position-relative">
                                        <input type="password" class="form-control pe-5 @error('password') is-invalid @enderror" name="password" id="password" placeholder="{{ __('Enter your password') }}" required>

                                        <button type="button" class="p-0 bg-transparent border-0 btn position-absolute top-50 end-0 translate-middle-y me-3" onclick="togglePasswordVisibility('password', 'toggle-icon-password')" aria-label="{{ __('Toggle password visibility') }}">
                                            <i class="fas fa-eye text-muted fs-5" id="toggle-icon-password"></i>
                                        </button>
                                    </div>

                                    <div class="form-text">
                                        {{ __('The secure password for your account') }}
                                    </div>

                                    @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                {{-- Remember Me --}}
                                <div class="mb-4 form-group">
                                    <div class="form-check d-flex align-items-start">
                                        <input type="checkbox" class="mt-1 form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <div class="ms-2">
                                            <label class="form-check-label form-text" for="remember">
                                                <i class="fas fa-user-clock me-1 text-muted"></i>
                                                {{ __('Keep me signed in on this device') }}
                                            </label>
                                            <div class="form-text">
                                                {{ __('Perfect for your personal devices - we\'ll remember you next time!') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="mb-4 form-btn d-grid">
                                    <button type="submit" class="mb-3 th-btn btn-lg w-100">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        {{ __('Sign Me In') }}
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>

                                {{-- Links --}}
                                <div class="text-center alternative-actions">
                                    @if (Route::has('password.request'))
                                    <div class="mb-3">
                                        <a class="text-decoration-none fw-semibold" href="{{ route('password.request') }}">
                                            <i class="fas fa-key me-1"></i>
                                            {{ __('Forgot your password? No worries!') }}
                                        </a>
                                    </div>
                                    @endif

                                    @if (Route::has('register'))
                                    <div class="new-user-section">
                                        <p class="mb-2 text-muted">{{ __("New here?") }}</p>
                                        <a class="text-decoration-none fw-semibold" href="{{ route('register') }}">
                                            <i class="fas fa-user-plus me-1"></i>
                                            {{ __('Create Your Account') }}
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div> {{-- col --}}
            </div> {{-- row --}}
        </div> {{-- container --}}
    </div> {{-- min-vh-100 --}}

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
