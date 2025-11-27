<x-layouts.guest :ogTitle="seo_title('Contact Atannex | Get in Touch')">

    <x-partials.breadcrumb />

    <div class="space2">
        <div class="container">
            <div class="row">
                <div class="col-xl-5">
                    <div class="mb-40 text-center pe-xxl-4 me-xl-3 text-xl-start mb-lg-0">
                        <div class="mb-32 title-area">
                            <h2 class="sec-title2">
                                {{ __('Get in Touch') }}
                            </h2>
                            <p class="sec-text">
                                {{__('Reach out with questions, ideas, or collaborations.')}}
                            </p>
                        </div>

                        <div class="contact-feature-wrap">
                            @foreach($infos as $feature)
                            <div class="contact-feature">
                                <div class="box-icon">
                                    <img src="{{ asset('storage/' . $feature['icon']) }}" alt="{{ $feature['title'] }} icon">
                                </div>
                                <div class="box-content">
                                    <h3 class="box-title-22">
                                        {{ $feature['title'] }}
                                    </h3>
                                    <p class="box-text">
                                        @foreach($feature['items'] as $item)
                                        @switch($item['type'])
                                        @case('email')
                                        <a href="mailto:{{ $item['value'] }}">
                                            {{ $item['value'] }}
                                        </a>
                                        @break
                                        @case('phone')
                                        <a href="tel:{{ $item['value'] }}">
                                            {{ $item['label'] ?? $item['value'] }}
                                        </a>
                                        @break
                                        @default
                                        {{ $item['value'] }}
                                        @endswitch
                                        @if(!$loop->last)
                                        @endif
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>

                <livewire:forms.contact :subjects="$subjects" />

            </div>
        </div>
    </div>
    <iframe src="{{ $map }}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</x-layouts.guest>
