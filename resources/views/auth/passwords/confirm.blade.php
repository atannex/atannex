<x-layouts.guest :title="seo_title('Verify It\'s Really You')">
    <div class="space2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-xl-7 col-lg-6">
                    <div class="card quote-form-box">

                        <div class="text-center card-header">
                            <img src="{{ asset('storage/'. $global['logo']?->image) }}" alt="Secure Login" style="max-width: 200px;" class="mb-3">

                            @auth
                            <h4 class="form-title">{{ __("Hi :name, let's verify it's you", ['name' => Auth::user()->first_name ?? 'there']) }}</h4>
                            <p class="form-description text-muted">
                                {{ __('We want to keep your account secure. Please enter your password to continue with this important action.') }}
                            </p>
                            @else
                            <h4 class="form-title">{{ __("Security Check Required") }}</h4>
                            <p class="form-description text-muted">
                                {{ __('For your protection, we need to verify your identity before proceeding.') }}
                            </p>
                            @endauth

                            <div class="mt-2 security-badge">
                                <small class="text-success">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    {{ __('Secure & Protected') }}
                                </small>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('password.confirm') }}" method="POST" class="contact-form">
                                @csrf

                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="password" class="mb-2 form-label text-start d-block">
                                            <i class="fas fa-lock me-1"></i>
                                            {{ __('Your Password') }}
                                        </label>
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Enter your current password') }}" required autocomplete="current-password" autofocus>

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                        <small class="mt-1 form-text text-muted">
                                            {{ __('This is the same password you use to log into your account') }}
                                        </small>
                                    </div>

                                    <div class="form-btn col-12 d-flex justify-content-center flex-column align-items-center">
                                        <button type="submit" class="mb-3 th-btn btn-lg w-100">
                                            <i class="fas fa-check-circle me-2"></i>
                                            {{ __("Yes, It's Me - Continue") }}
                                            <i class="fas fa-arrow-right ms-2"></i>
                                        </button>

                                        <div class="text-center alternative-actions">
                                            @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="mb-2 text-decoration-none text-primary d-inline-block">
                                                <i class="fas fa-question-circle me-1"></i>
                                                {{ __('Can\'t remember your password?') }}
                                            </a>
                                            <br>
                                            @endif

                                            <a href="{{ url()->previous() }}" class="text-decoration-none text-muted">
                                                <i class="fas fa-arrow-left me-1"></i>
                                                {{ __('Take me back') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 row">
                                    <div class="col-12">
                                        <div class="alert alert-info d-flex align-items-center" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <small>
                                                {{ __('This extra step helps protect your account and personal information from unauthorized access.') }}
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
</x-layouts.guest>
