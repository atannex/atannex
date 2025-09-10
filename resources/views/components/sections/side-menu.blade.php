<div class="sidemenu-wrapper sidemenu-1 d-none d-md-block">
    <div class="sidemenu-content">
        <button class="closeButton sideMenuCls">
            <i class="far fa-times"></i>
        </button>

        {{-- Company Info --}}
        <div class="widget">
            <div class="th-widget-about">
                <div class="about-logo">
                    <a href="{{ route('home') }}">
                        <img class="dark-img img-fluid" src="{{ isset($global['logo']->image)
                                    ? asset('storage/' . $global['logo']->image)
                                    : asset('images/default-logo.png') }}" alt="{{ config('app.name') }}" style="max-width: 98px; height: 98px; object-fit: cover;">
                    </a>
                </div>

                <p class="about-text">
                    {{-- {{ $company->description ?? '' }} --}}
                </p>

                {{-- Social Icons --}}
                <div class="th-social style-black">
                    @if(!empty($global['global_icons']))
                    @foreach ($global['global_icons'] as $media)
                    <a href="{{ $media['url'] ?? '#' }}" target="_blank" rel="noopener" title="{{ $media['label'] ?? 'Social Link' }}" class="d-inline-flex align-items-center justify-content-center rounded-circle me-1" style="width: 2.5rem; height: 2.5rem; background-color: var(--bs-{{ $media['color'] ?? 'primary' }});">
                        <i class="{{ $media['icon'] ?? 'fas fa-link' }} text-white"></i>
                    </a>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Posts --}}
        @includeIf('partials.recent-posts')

        {{-- Newsletter Subscription --}}
        @if(View::exists('livewire.forms.subscriber'))
        @livewire('forms.subscriber')
        @endif

    </div>
</div>
