@extends('components.layouts.base')

@section('og:title', $module->post->title)
@section('og:description', $module->post->description)

@section('og:video', asset('storage/' . $module->video['path'] ?? $module->video['cover']))
@section('og:type', 'video.other')

{{-- @if(!empty(asset('storage/' . $module->video['path'] ?? $module->video['cover'])))
@section('twitter:card', 'player')
@section('twitter:player', route('video.embed', $module->post->slug))
@else
@section('twitter:card', 'summary_large_image')
@endif --}}

@section('og:image', asset('storage/' . ($module->video['cover'] ?? $module->video['poster'])))

@section('og:publishedAt', $module->post->published_at)
@section('og:updatedAt', $module->post->updated_at)

@section('base')

<x-sections.preloader />

@livewire('search.web')

<x-sections.side-menu />

<x-sections.category.header />

<x-partials.breadcrumb />

<section class="th-blog-wrapper blog-details bg-smoke space-top space-extra-bottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="th-blog blog-single style-bg">

                    <x-shows.header-content :module="$module" />

                    <div class="blog-video">
                        @if (!empty($module->video['id']))

                        <div class="yt-wrapper vf-aspect" data-yt-id="{{ $module->video['id'] }}" role="button" aria-label="Play video">

                            <img class="yt-cover" src="{{ asset('storage/' . $module->video['thumbnail']) }}" alt="Video cover" draggable="false">

                            <button class="yt-play-btn vf-play-btn" type="button" aria-label="Play video">
                                <i class="fas fa-play" aria-hidden="true"></i>
                            </button>
                        </div>

                        @elseif (!empty($module->video['path']))

                        <div class="lv-wrapper vf-aspect" data-lv>
                            <video class="lv-video" preload="metadata" playsinline disablePictureInPicture controlsList="nodownload noplaybackrate noremoteplayback" @if (!empty($module->video['poster']))
                                poster="{{ asset('storage/' . $module->video['poster']) }}"
                                @endif
                                draggable="false">

                                <source src="{{ asset('storage/' . $module->video['path']) }}" type="{{ $module->video['mime'] ?? 'video/mp4' }}">

                                {{ __('Your browser does not support the video tag.') }}
                            </video>

                            <button class="lv-play-btn vf-play-btn" type="button" aria-label="Play video">
                                <i class="fas fa-play" aria-hidden="true"></i>
                            </button>
                        </div>
                        @endif
                    </div>

                    <div class="blog-content-wrap">

                        <x-shows.social-share :module="$module" :icons="$icons" />

                        <div class="blog-content">

                            <x-shows.video :module="$module" />

                            <x-shows.related-tag :relatedTags="$relatedTags" />

                        </div>
                    </div>
                    <x-shows.navigation :navigation="$navigation" />

                    <x-shows.author :module="$module" :medias="$medias" />

                    <livewire:forms.comment wire:key="comments-{{ $module->post->id }}" :commentable="$module->post" />

                </div>
            </div>
            <div class="col-lg-4 sidebar-wrap">
                <aside class="sidebar-area style-bg">
                    <div class="widget widget_search">

                        @livewire('search.post')

                    </div>

                    @include('partials.aside.category')

                    @include('partials.aside.recent-posts')

                    @include('partials.aside.tag')

                </aside>
            </div>
        </div>

        <x-shows.related-posts :relatedPosts="$relatedPosts" />

    </div>
</section>

<x-sections.pages.footer />

@endsection
