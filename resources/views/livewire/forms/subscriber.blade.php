<div class="widget newsletter-widget">
    <h3 class="widget_title">
        {{ __('Subscribe') }}
    </h3>

    <p class="footer-text">
        {{ __("Sign up to get updates about us. Don't hesitate, your email is safe.") }}
    </p>

    @if (session()->has('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif

    @if (session()->has('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <form wire:submit.prevent="submit" class="newsletter-form" novalidate>
        <div class="input-group">
            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Enter Email') }}" wire:model.defer="email" required aria-label="{{ __('Email address') }}">
            <button type="submit" class="icon-btn" aria-label="{{ __('Submit') }}">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>

        @error('email')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </form>

    <div class="mt-30 form-check">
        <input type="checkbox" id="agree" wire:model="agree" class="form-check-input">
        <label for="agree" class="form-check-label">
            {{ __('I have read and accept the') }}
            <a href="{{ route('document.index', ['type' => 'terms']) }}">
                {{ __('Terms & Policy') }}
            </a>
        </label>

        @error('agree')
        <div class="text-danger">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
