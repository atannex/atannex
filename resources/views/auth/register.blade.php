<x-layouts.guest :title="'Register - ' . __('Top Stories, Breaking News & Headlines') . ' | ' . config('app.name')">
    <div class="space2 d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-md-8 col-xl-8">
                    <div class="card quote-form-box">
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $global['logo']?->image) }}" alt="Logo" style="max-width: 200px;" class="mb-3">
                            <p class="form-description text-muted">
                                {{ __('Create an account to personalize your experience and access member-exclusive content.') }}
                            </p>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('register') }}" method="POST" class="contact-form">
                                @csrf

                                <div class="mb-4 form-group">
                                    <label for="name" class="form-label fw-medium form-text">
                                        {{ __('Full Name') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                                    @error('name')
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4 form-group">
                                    <label for="email" class="form-label fw-medium form-text">
                                        {{ __('Email Address') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                    <div class="form-text">{{ __('A confirmation email will be sent to verify your identity.') }}</div>
                                    @error('email')
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4 form-group">
                                    <label for="password" class="form-label fw-medium form-text">
                                        {{ __('Password') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg position-relative">
                                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror pe-5" placeholder="************" required>
                                        <button type="button" class="p-0 bg-transparent border-0 btn position-absolute top-50 end-0 translate-middle-y me-3" onclick="togglePasswordVisibility('password', 'toggle-icon-password')" aria-label="{{ __('Toggle password visibility') }}">
                                            <i class="fas fa-eye text-muted fs-5" id="toggle-icon-password"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">{{ __('Password must be at least 8 characters and contain a mix of letters and numbers.') }}</div>
                                    @error('password')
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4 form-group">
                                    <label for="password-confirm" class="form-label fw-medium form-text">
                                        {{ __('Confirm Password') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" id="password-confirm" name="password_confirmation" class="form-control form-control-lg" required>
                                </div>

                                <div class="mb-4 form-group">
                                    <div class="form-check">
                                        <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" {{ old('terms') ? 'checked' : '' }} required>
                                        <label class="form-check-label form-text" for="terms">
                                            {{ __('I agree to the') }}
                                            <a href="{{ route('document.index', ['type' => 'terms']) }}" target="_blank">{{ __('Terms of Service') }}</a>
                                            {{ __('and') }}
                                            <a href="{{ route('document.index', ['type' => 'privacy']) }}" target="_blank">{{ __('Privacy Policy') }}</a>.
                                        </label>
                                        @error('terms')
                                        <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-btn col-12 d-flex justify-content-center">
                                    <button type="submit" class="th-btn">
                                        {{ __('Register Now') }} <i class="fas fa-arrow-up-right ms-2"></i>
                                    </button>
                                </div>

                                <p class="mt-3 mb-0 form-messages"></p>

                                <div class="mt-4 text-center">
                                    <p class="form-text">
                                        {{ __("Already have an account?") }}
                                        <a href="{{ route('login') }}" class="fw-bold">{{ __('Login here') }}</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.guest>
<<<<<<< HEAD
=======

{{-- <form method="POST" action="{{ route('register') }}">
    @csrf
    <div>
        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required>
        @error('name') <span>{{ $message }}</span> @enderror
    </div>
    <div>
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        @error('email') <span>{{ $message }}</span> @enderror
    </div>
    <div>
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
        @error('password') <span>{{ $message }}</span> @enderror
    </div>
    <div>
        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>
    </div>
    <div>
        <label for="locale">Language</label>
        <select id="locale" name="locale">
            <option value="en" {{ old('locale') == 'en' ? 'selected' : '' }}>English</option>
            <option value="es" {{ old('locale') == 'es' ? 'selected' : '' }}>Español</option>
        </select>
        @error('locale') <span>{{ $message }}</span> @enderror
    </div>
    <div>
        <label>
            <input type="checkbox" name="terms" required>
            I accept the <a href="{{ config('app.terms_url', '/terms') }}">Terms of Service</a>
        </label>
        @error('terms') <span>{{ $message }}</span> @enderror
    </div>
    <button type="submit">Register</button>
</form> --}}
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
