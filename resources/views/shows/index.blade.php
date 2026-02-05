@extends('components.layouts.base')

@section('og:title', $module->post->title)
@section('og:description', $module->post->description)
@section('og:image', asset('storage/' . $module->post->image))
@section('og:publishedAt', $module->post->published_at)
@section('og:updatedAt', $module->post->updated_at)

@section('base')

<x-sections.preloader />

@livewire('search.web')

<x-sections.side-menu />

<x-sections.category.header />

<x-partials.breadcrumb />

<section class="th-blog-wrapper blog-details space-top space-extra-bottom">
    <div class="container">
        <div class="row">

            <div class="col-12">

                <x-shows.header-content :module="$module" />

                <div class="mb-40 blog-img">
                    <img class="img-fluid image-show" src="{{ asset('storage/' . $module->post->image) }}" alt="{{ config('app.name') }}">
                </div>

            </div>

            <div class="col-xxl-9 col-lg-8">
                <div class="th-blog blog-single">
                    <div class="blog-content-wrap">
                        <div class="share-links-wrap">

                            <x-shows.social-share :module="$module" :icons="$icons" />

                        </div>

                        <div class="blog-content">

                            <livewire:show.info :post="$module->post" wire:key="posts-{{ $module->post->id }}" />

                            <x-shows.content :module="$module" :headingLevels="$headingLevels" />

                            <x-shows.related-tag :relatedTags="$relatedTags" />

                        </div>
                    </div>
                </div>

                <x-shows.navigation :navigation="$navigation" />

                <x-shows.author :module="$module" :medias="$medias" />

                <livewire:forms.post-comment wire:key="comments-{{ $module->post->id }}" :commentable="$module->post" />

                <x-shows.related-posts :relatedPosts="$relatedPosts" />

            </div>

            <div class="col-xxl-3 col-lg-4 sidebar-wrap">
                <aside class="sidebar-area">
                    <div class="widget widget_tag_cloud">

                        @livewire('search.post')

                    </div>

                    @include('partials.aside.category')

                    @include('partials.aside.recent-posts')

                    @include('partials.aside.tag')

                </aside>
            </div>
        </div>
    </div>
    </div>
</section>

<x-sections.category.footer />

@endsection
