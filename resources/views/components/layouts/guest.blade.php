@extends('components.layouts.base')

@section('og:title')

@section('base')

<x-sections.preloader />

<x-sections.guest.header />

@yield('guest')

<x-sections.guest.footer />

@endsection
