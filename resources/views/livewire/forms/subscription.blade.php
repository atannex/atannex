<div class="popup-subscribe-area" x-data="popupSubscribe({{ $alreadySubscribed ? 'true' : 'false' }})" x-show="open" x-transition x-cloak aria-hidden="true" role="dialog" aria-labelledby="subscribePopupTitle">
    <div class="container">
        <div class="popup-subscribe">
            <div class="box-img">
                <img src="{{ asset('storage/' . $global['subscription']?->image) }}" alt="{{ __('Subscription popup for ') . config('app.name') }}" class="img-fluid subscription" loading="lazy" />
            </div>

            <div class="box-content">
                <button class="simple-icon popupClose" @click="closePopup()" aria-label="{{ __('Close subscription popup') }}">
                    <i class="fal fa-times"></i>
                </button>

                <div class="widget newsletter-widget footer-widget">
                    <h3 class="widget_title" id="subscribePopupTitle">{{ __('Subscribe to Our Newsletter') }}</h3>
                    <p class="footer-text">
                        {{ __('Sign up to receive updates about our services. Your email is safe with us.') }}
                    </p>

                    <form wire:submit.prevent="subscribe" class="newsletter-form" id="subscriptionForm">
                        <div class="form-group">
                            <label for="emailInput" class="visually-hidden">{{ __('Email Address') }}</label>
                            <input type="email" wire:model.defer="email" id="emailInput" class="form-control" placeholder="{{ __('Enter your email address') }}" required aria-describedby="emailError">

                            @error('email')
                            <span class="text-danger small" id="emailError">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="icon-btn" aria-label="{{ __('Subscribe') }}">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>

                    <div class="mt-2">
                        @if ($successMessage)
                        <div class="text-success" role="alert">{{ $successMessage }}</div>
                        @endif
                        @if ($errorMessage)
                        <div class="text-danger" role="alert">{{ $errorMessage }}</div>
                        @endif
                    </div>

                    <div class="mt-3 form-check">
                        <input type="checkbox" id="destroyPopup" class="form-check-input" @change="permanentlyHide()">
                        <label for="destroyPopup" class="form-check-label">
                            {{ __('Do not show this popup again') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
