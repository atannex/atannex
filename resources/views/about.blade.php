@extends('components.layouts.guest')

@section('og:title', seo_title('About Atannex | Innovative Solutions'))

@section('guest')

<x-partials.breadcrumb />

<div class="space2" id="about-sec">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-7 mb-30 mb-xl-0">
                <div class="img-box1">
                    @if(!empty($about->image))
                    @foreach($about->image as $key => $image)
                    @if($key === 0)
                    <div class="img1">
                        <img src="{{ asset('storage/' . $image['path']) }}" alt="{{ config('app.name') }}">
                    </div>
                    @elseif($key === 1)
                    <div class="img2">
                        <img src="{{ asset('storage/' . $image['path']) }}" alt="{{ config('app.name') }}">
                    </div>
                    @endif
                    @endforeach
                    @endif


                    @if(!empty($about->video_url))
                    <a href="{{ $about->video_url }}" class="icon-btn popup-video">
                        <i class="fas fa-play"></i>
                    </a>
                    @endif
                </div>
            </div>

            <div class="col-xl-5">
                <div class="mb-32 title-area">
                    <span class="sub-title">
                        {{ $about->subtitle }}
                    </span>

                    <h2 class="sec-title2">
                        {{ $about->title }}
                    </h2>

                    <p class="sec-text">
                        {!! $about->description !!}
                    </p>

                </div>

                @if(!empty($about->features))
                <div class="checklist mt-n2 mb-35">
                    <ul>
                        @foreach($about->features as $feature)
                        <li>
                            <i class="far fa-check-circle"></i>
                            {{ $feature['text'] }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <a href="{{ route('about') }}" class="th-btn">
                    {{ __('About More ') }}
                    <i class="fas fa-arrow-up-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@if($about->cta)
<section class="cta-sec-1" data-bg-src="{{ asset('storage/' . $about->cta['bg_image']) }}">
    <div class="container space2">
        <div class="text-center row text-md-start align-items-center justify-content-md-between justify-content-center">

            <div class="mb-40 col-lg-7 col-md-8 mb-md-0">
                <div class="mb-0 title-area">
                    <span class="sub-title">
                        {{ $about->cta['subtitle'] }}
                    </span>
                    <h2 class="text-white sec-title2 h1">
                        {{ $about->cta['title'] }}
                    </h2>
                </div>
            </div>

            <div class="col-md-auto">
                <a href="{{ route('contact') }}" class="th-btn style3">
                    {{ __('Contact Us') }}
                    <i class="fas fa-arrow-up-right ms-2"></i>
                </a>
            </div>

        </div>
    </div>
</section>
@endif

<div class="counter-sec-1">
    <div class="container">
        <div class="counter-card-wrap">
            @foreach($counters as $counter)
            <div class="counter-card">
                <h2 class="counter-card_number">
                    <span class="counter-number">
                        {{ $counter['number'] }}
                    </span>{{ __(" + ") }}
                </h2>
                <span class="counter-card_text">
                    {{ $counter['label'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="space2">
    <div class="container">
        <div class="text-center title-area">
            <span class="sub-title">
                {{ __('History') }}
            </span>
            <h2 class="sec-title2">
                {{ _('Company History') }}
            </h2>
        </div>

        <div class="story-box-area">
            @foreach(collect($about->story)->sortBy('year') as $item)
            <div class="story-box-wrap">
                <div class="story-box">
                    @if(!empty($item['image']))
                    <div class="box-img">
                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ config('app.name') }}">
                    </div>
                    @endif
                    <div class="box-content">
                        <h3 class="box-title">
                            {{ $item['title'] }}
                        </h3>
                        <p class="box-text">
                            {!! $item['description'] !!}
                        </p>
                    </div>
                </div>
                <div class="story-year">
                    {{ $item['year'] }}
                </div>
            </div>
            @endforeach

            <div class="story-box-wrap">
                <div class="story-year">
                    {{ __('End') }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
