<div class="col-xl-7">
    <div class="quote-form-box">
        <div class="p-4 text-center card-header">
            <img src="{{ asset('storage/' . $global['logo']?->image) }}" alt="{{ config('app.name') }}" style="max-width: 200px;" class="mb-3">
            <h4 class="mb-1 form-title">{{ __("Send Message") }}</h4>
            <p class="form-description text-muted">
                {{ __('We would love to hear from you! Fill out the form below and we will get back to you shortly.') }}
            </p>
            <div class="mt-2 security-badge success">
                <small class="text-success">
                    <i class="fas fa-shield-alt me-1"></i>
                    {{ __('Secure Submission') }}
                </small>
            </div>
        </div>
        <form wire:submit.prevent="submit" class="contact-form">
            <div class="row">
                @guest
                <div class="form-group col-md-12">
                    <input type="text" class="form-control" wire:model.defer="name" placeholder="Your Name">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group col-md-12">
                    <input type="email" class="form-control" wire:model.defer="email" placeholder="Email Address">
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group col-md-6">
                    <input type="tel" class="form-control" wire:model.defer="number" placeholder="Phone Number">
                    @error('number') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                @endguest

                @auth
                <input type="hidden" wire:model.defer="name">
                <input type="hidden" wire:model.defer="email">
                <input type="hidden" wire:model.defer="number">
                @endauth

                <div class="form-group @guest col-md-6 @endguest @auth col-md-12 @endauth">
                    <select wire:model.defer="subject" class="form-select">
                        <option value="" disabled selected hidden>{{ __('Select Subject') }}</option>
                        @foreach($subjects as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('subject') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group col-12">
                    <textarea wire:model.defer="message" class="form-control" cols="30" rows="3" placeholder="Your Message"></textarea>
                    @error('message') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="text-center form-btn col-12">
                    <button class="mb-3 th-btn btn-lg w-100" type="submit">
                        {{ __('Submit Now') }}
                        <i class="fas fa-arrow-up-right ms-2"></i>
                    </button>
                </div>
            </div>
        </form>

        @if (session()->has('success'))
        <div class="mt-2 alert alert-success">{{ session('success') }}</div>
        @endif
    </div>
</div>
