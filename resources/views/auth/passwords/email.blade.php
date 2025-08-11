<x-layouts.guest :title="'Email - ' . __('Top Stories, Breaking News & Headlines') . ' | ' . config('app.name')">
    <div class="space2">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-md-8 col-xl-8">
                    <div class="quote-form-box">
                        <div class="text-center">
                            <img src="{{ asset('storage/'. $global['logo']?->image) }}" alt="Site Logo" style="max-width: 200px;" class="mb-3">
                            <h4 class="form-title">Reset Password</h4>
                            <p class="form-description text-muted">
                                Enter your email address and we’ll send you a link to reset your password.
                            </p>
                        </div>

                        <div class="card-body">
                            @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}" class="contact-form">
                                @csrf

                                {{-- Email Field --}}
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                    <div class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>

                                {{-- Submit Button --}}
                                <div class="form-btn col-12 d-flex justify-content-center">
                                    <button type="submit" class="th-btn">
                                        Send Password Reset Link <i class="fas fa-paper-plane ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.guest>
