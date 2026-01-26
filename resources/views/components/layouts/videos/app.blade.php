@extends('components.layouts.videos.base')

@section('og:title')

@section('base-video')

<x-layouts.videos.header />

@yield('video-app')

<x-layouts.videos.footer />

<x-layouts.videos.mobile-filter />

@endsection
