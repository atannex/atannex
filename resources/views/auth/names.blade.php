<x-layouts.guest :title="seo_title('Tell Us Your Name!')">
    <div class="space2 d-flex justify-content-center align-items-center" style="min-height: 50vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-xl-7 col-lg-6">
                    <div class="card quote-form-box">
                        <div class="text-center card-header">
                            <img src="{{ asset('storage/' . $global['logo']?->image) }}" alt="Welcome" style="max-width: 200px;" class="mb-3">

                            <h4 class="mb-3 form-title">{{ __('Tell Us Your Name!') }}</h4>
                            <p class="form-description text-muted">
                                {{ __('Please provide your full name so we can personalize your experience and address you properly.') }}
                            </p>

                            <div class="mt-2 security-badge primary">
                                <small class="text-primary">
                                    <i class="fas fa-user-shield me-1"></i>
                                    {{ __('Your Name Will Be Kept Secure') }}
                                </small>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('name.complete', ['token' => $user->name_token]) }}" method="POST" class="contact-form" id="registerForm">
                                @csrf

                                <div class="mb-4 form-group">
                                    <label for="name" class="form-label fw-medium form-text text-start d-block">
                                        <i class="fas fa-user me-1"></i>
                                        {{ __('What\'s your full name?') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" placeholder="{{ __('Type your first and last name here') }}" value="{{ old('name') }}" required autofocus>
                                    <div class="form-text">{{ __('This helps us personalize your account and community experience.') }}</div>
                                    @error('name')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-4 form-btn d-grid">
                                    <button type="submit" class="mb-3 th-btn btn-lg w-100">
                                        <i class="fas fa-check-circle me-2"></i>
                                        {{ __('Submit My Name') }}
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>

                                <p class="mt-3 mb-0 form-messages"></p>

                                <div class="text-center alternative-actions">
                                    <div class="existing-user-section">
                                        <p class="mb-2 text-muted">{{ __("Already gave us your name?") }}</p>
                                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                                            <i class="fas fa-sign-in-alt me-1"></i>
                                            {{ __('Go to Sign In') }}
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
</x-layouts.guest>
