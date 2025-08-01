<div class="widget newsletter-widget">
    <h3 class="widget_title">
        {{ __("Subscribe") }}
    </h3>
    <p class="footer-text">
        {{ __("Sign up to get updates about us. Don't hesitate, your email is safe.") }}
    </p>

    @if (session()->has('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="submit" class="newsletter-form" novalidate>
        <input class="form-control @error('email') is-invalid @enderror" type="email" placeholder="Enter Email" wire:model.defer="email" required>
        <button type="submit" class="icon-btn"><i class="fa-solid fa-paper-plane"></i></button>

        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </form>

    <div class="mt-30">
        <input type="checkbox" id="agree" wire:model="agree" />
        <label for="agree">
            {{ __("I have read and accept the ") }}<a href="{{ route('document.index', ['type' => 'terms']) }}">
                {{ __("Terms & Policy") }}
            </a>
        </label>

        @error('agree')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

