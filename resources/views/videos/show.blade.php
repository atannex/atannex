@extends('components.layouts.videos.app')

@section('og:title', seo_title($video->slug))

@section('video-app')

<section class="section section--details">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="section__title section__title--head">
                    {{ $video->title }}
                </h1>
            </div>
            <div class="col-12 col-xl-6">
                <div class="item item--details">
                    <div class="row">
                        <div class="col-12 col-sm-5 col-md-5 col-lg-4 col-xl-6 col-xxl-5">
                            <div class="item__cover">
                                <img src="{{ asset('storage/' . $video->image) }}" alt="{{ $video->title }}" loading="lazy" class="img-fluid video-thumbnail">
                                @if($video->rating)
                                <span class="item__rate item__rate--green">
                                    {{ number_format($video->rating, 1) }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-7 col-lg-8 col-xl-6 col-xxl-7">
                            <div class="item__content">
                                <ul class="item__meta">
                                    <li>
                                        <span>{{ __('Director:') }}</span>
                                        <a href="{{ route('page.index', $video->author->user->slug) }}">
                                            {{ $video->author->user->name }}
                                        </a>
                                    </li>

                                    <li>
                                        <span>{{ __('Category:') }}</span>
                                        <a href="{{ route('page.index', $video->category->slug_path) }}">
                                            {{ $video->category->name }}
                                        </a>
                                    </li>

                                    <li>
                                        <span>{{ __('Published At:') }}</span>
                                        {{ $video->published_at->format('M d, Y') }}
                                    </li>

                                    <li>
                                        <span>{{ __('Running time:') }}</span>
                                        {{ $video->duration }}
                                    </li>

                                    <li>
                                        <span>{{ __('Region:') }}</span>
                                        @if ($video->region)
                                        <a href="{{ route('page.index', ['slug' => $video->region->slug_path]) }}">
                                            {{ $video->region->name }}
                                        </a>
                                        @endif
                                    </li>
                                </ul>
                                @if(!empty($module->content))
                                <div class="item__description">
                                    <p> {!! $module->content !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6 d-flex justify-content-center">
                <video id="player" class="plyr w-100 h-100" controls playsinline preload="metadata" poster="{{ asset('storage/' . $video->image) }}">
                    <source src="{{ asset('storage/' . $video->video_url) }}" type="video/mp4">
                </video>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="content__head content__head--mt">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="content__title">{{ __("Discover") }}</h2>
                    <ul class="nav nav-tabs content__tabs" id="content__tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button id="1-tab" class="active" data-bs-toggle="tab" data-bs-target="#tab-1" type="button" role="tab" aria-controls="tab-1" aria-selected="true">
                                {{ __("Comments") }}
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button id="2-tab" data-bs-toggle="tab" data-bs-target="#tab-2" type="button" role="tab" aria-controls="tab-2" aria-selected="false">
                                {{ __('Reviews') }}
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button id="3-tab" data-bs-toggle="tab" data-bs-target="#tab-3" type="button" role="tab" aria-controls="tab-3" aria-selected="false">
                                {{ _('Photos') }}
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab" tabindex="0">
                        <div class="row">
                            <div class="col-12">

                                <livewire:forms.video-comment :videoId="$video->id" />

                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="2-tab" tabindex="0">
                        <div class="row">
                            <div class="col-12">

                                <livewire:forms.video-review :videoId="$video->id" />

                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="3-tab" tabindex="0">
                        <div class="gallery" itemscope>
                            <div class="row">
                                @foreach($images as $image)
                                <figure class="col-12 col-sm-6 col-xl-4" itemprop="associatedMedia" itemscope>
                                    <a href="{{ asset('storage/'. $image['file']) }}" itemprop="contentUrl" data-size="1920x1280">
                                        <img src="{{ asset('storage/'. $image['file']) }}" itemprop="thumbnail" alt="{{ $image['alt'] }}" />
                                    </a>
                                    @if(!empty($image['caption']))
                                    <figcaption itemprop="caption description">{{ $image['caption'] }}</figcaption>
                                    @endif
                                </figure>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="row">
                    <div class="col-12">
                        <h2 class="section__title section__title--sidebar">
                            {{ __("You may also like...") }}
                        </h2>
                    </div>

                    @foreach ($relatedVideos as $related)
                    <div class="col-6 col-sm-4 col-lg-6">
                        <div class="item">
                            <div class="item__cover">
                                <div class="d-flex justify-content-center">
                                    <img src="{{ asset('storage/' . $related->image) }}" alt="{{ config('app.name') }}" class="img-fluid video-thumbnail">
                                </div>
                                <a href="{{ route('video.show', ['slug' => $related->slug]) }}" class="item__play">
                                    <i class="ti ti-player-play-filled"></i>
                                </a>
                                @if($related->rating)
                                <span class="item__rate item__rate--green">{{ number_format($related->rating, 1) }}</span>
                                @endif
                            </div>
                            <div class="item__content">
                                <h3 class="item__title">
                                    <a href="{{ route('video.show', ['slug' => $related->slug]) }}">{{ $related->title }}</a>
                                </h3>
                                <span class="item__category">
                                    <a href="{{ route('page.index', $related->category->slug_path) }}">{{ $related->category->name }}</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
