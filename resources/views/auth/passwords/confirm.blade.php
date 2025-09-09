<x-layouts.guest :title="seo_title('Confirm Your Password')">
    <div class="space2">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-xl-7">
                    <div class="card quote-form-box">

                        <div class="text-center card-header">
                            <img src="{{ asset('storage/'. $global['logo']?->image) }}" alt="Login Icon" style="max-width: 200px;" class="mb-3">
                            <h4 class="form-title">Confirm Password</h4>
                            <p class="form-description text-muted">
                                Please confirm your password before continuing.
                            </p>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('password.confirm') }}" method="POST" class="contact-form">
                                @csrf

                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required autocomplete="current-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-btn col-12 d-flex justify-content-center flex-column align-items-center">
                                        <button type="submit" class="th-btn">
                                            {{ __("Confirm Password") }}
                                            <i class="fas fa-arrow-up-right ms-2"></i>
                                        </button>

                                        @if (Route::has(''))
                                        <a class="mt-2 btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('') }}
                                        </a>
                                        @endif
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
