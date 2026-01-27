@extends('components.layouts.videos.app')

@section('og:title', seo_title())

@section('video-app')

@include('videos.partials.carousel')

@include('videos.partials.catalog')

@include('videos.partials.latest')

@endsection
