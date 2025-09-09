<x-layouts.guest :title="seo_title('Login')">
    <div class="min-vh-100 d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-xl-8">
                    <div class="shadow card quote-form-box">
                        <div class="p-4 text-center">
                            <img src="{{ asset('storage/' . $global['logo']->image) }}" alt="Login Icon" style="max-width: 200px;" class="mb-3">
                            <p class="form-description text-muted">
                                {{ __('Please sign in to your account to continue accessing your dashboard and manage your services securely.') }}
                            </p>
                        </div>

                        <div class="pt-0 card-body">
                            <form action="{{ route('login') }}" method="POST" class="contact-form">
                                @csrf

                                {{-- Email --}}
                                <div class="mb-4 form-group">
                                    <label for="email" class="form-label fw-medium form-text">
                                        {{ __('Email Address') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" id="email" placeholder="{{ __('@gmail.com / atannex.com / org') }}" value="{{ old('email') }}" required autofocus>

                                    <div class="form-text">
                                        {{ __('We will use this email to verify your identity and send important notifications.') }}
                                    </div>

                                    @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                {{-- Password --}}
                                <div class="mb-4 form-group">
                                    <label for="password" class="form-label fw-medium form-text">
                                        {{ __('Password') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg position-relative">
                                        <input type="password" class="form-control pe-5 @error('password') is-invalid @enderror" name="password" id="password" placeholder="************" required>

                                        <button type="button" class="p-0 bg-transparent border-0 btn position-absolute top-50 end-0 translate-middle-y me-3" onclick="togglePasswordVisibility('password', 'toggle-icon-password')" aria-label="{{ __('Toggle password visibility') }}">
                                            <i class="fas fa-eye text-muted fs-5" id="toggle-icon-password"></i>
                                        </button>
                                    </div>

                                    <div class="form-text">
                                        {{ __('Your password is case-sensitive. Make sure you are on a secure network.') }}
                                    </div>

                                    @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                {{-- Remember Me --}}
                                <div class="mb-4 form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label form-text" for="remember">
                                            {{ __('Keep me signed in on this device') }}
                                        </label>
                                        <div class="form-text">
                                            {{ __('Recommended only for personal devices.') }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="form-btn d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        {{ __('LOGIN NOW') }} <i class="fas fa-arrow-up-right ms-2"></i>
                                    </button>
                                </div>

                                {{-- Links --}}
                                <div class="mt-4 text-center">
                                    @if (Route::has('password.request'))
                                    <a class="mb-2 text-decoration-none d-block" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                    @endif

                                    @if (Route::has('register'))
                                    <span class="text-muted">{{ __("Don't have an account?") }}</span>
                                    <a class="text-decoration-none fw-semibold" href="{{ route('register') }}">
                                        {{ __('Register here') }}
                                    </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div> {{-- col --}}
            </div> {{-- row --}}
        </div> {{-- container --}}
    </div> {{-- min-vh-100 --}}
</x-layouts.guest>
