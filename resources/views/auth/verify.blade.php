<x-layouts.guest :title="'Verify - ' . __('Top Stories, Breaking News & Headlines') . ' | ' . config('app.name')">
    <div class="space2">
        <div class="container">
            <div class="row">

                <div class="mx-auto col-md-8 col-xl-7">
                    <div class="card quote-form-box">
                        <div class="text-center card-header">
                            <img src="{{ asset('storage/'. $global['logo']?->image) }}" alt="Login Icon" style="max-width: 200px;" class="mb-3">
                            <p class="form-description text-muted">
                                {{ __('Before proceeding, please check your email for a verification link.') }}
                                {{ __('If you did not receive the email') }},
                            </p>
                        </div>

                        <div class="card-body">
                            @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                            @endif

                            <form action="{{ route('verification.resend') }}" method="POST" class="contact-form">
                                @csrf
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-center">
                                        <button type="submit" class="th-btn">
                                            {{ __('Click here to request another') }}
                                            <i class="fas fa-arrow-up-right ms-2"></i>
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
