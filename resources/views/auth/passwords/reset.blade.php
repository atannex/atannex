<x-layouts.guest :title="'Reset Your Password - ' . __('Top Stories, Breaking News & Headlines') . ' | ' . config('app.name')">
<div class="space2">
    <div class="container">
        <div class="row">

            <div class="col-md-8 col-xl-7">
                <div class="card quote-form-box">
                    <div class="card-header text-center">
                        <img src="{{ asset('storage/'. $global['logo']->image) }}" alt="Login Icon" style="max-width: 200px;" class="mb-3">
                        <h4 class="form-title">{{ __("Reset Password") }}</h4>
                        <p class="form-description text-muted">{{ __("Fill in the fields below to reset your password.") }}</p>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('password.update') }}" method="POST" class="contact-form">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" value="{{ old('email', request()->email) }}" required autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12 position-relative">
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="New Password" required autocomplete="new-password">
                                    <span class="toggle-password" onclick="togglePasswordVisibility('password', 'toggle-icon-password')" style="position:absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor:pointer;">
                                        <i class="fas fa-eye" id="toggle-icon-password"></i>
                                    </span>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <input type="password" name="password_confirmation" id="password-confirm" class="form-control" placeholder="Confirm Password" required>
                                </div>

                                <div class="form-btn col-12 d-flex justify-content-center">
                                    <button type="submit" class="th-btn">
                                        {{ __("Reset Password ") }}<i class="fas fa-arrow-up-right ms-2"></i>
                                    </button>
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
