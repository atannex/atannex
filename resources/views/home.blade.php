@extends('components.layouts.guest')

@section('og:title', seo_title())

@section('guest')

<x-home.hero-slider :recentPosts="$recentPosts" />

<x-home.editor-picks :editorPicks="$editorPicks" />

<x-home.regional-updates :regions="$regions" />

<x-home.featured-section :getPastWeekPosts="$getPastWeekPosts" :heroTitle="$heroTitle" :sideBlogs="$sideBlogs" :featuredBlog="$featuredBlog" />

<section class="space-bottom">
    <div class="container">
        <div class="row">
            <div class="col-xl-8">

                <x-home.popular-news :popularPosts="$popularPosts" />


                <x-home.featured-news :featuredPosts="$featuredPosts" />
            </div>

            <x-home.most-read-sidebar :mostReadPosts="$mostReadPosts" />
        </div>
    </div>
</section>

@endsection
