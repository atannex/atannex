@extends('components.layouts.videos.app')

@section('og:title', seo_title())

@section('video-app')

@include('videos.partials.carousel')

<livewire:catalog />

@include('videos.partials.latest')

@endsection
