@extends('components.layouts.base')

@section('og:title')

@section('base')

    <x-sections.preloader />

    <x-sections.guest.header />

    @auth
        <livewire:forms.subscription />
    @endauth

    @yield('guest')

    <x-sections.guest.footer />

@endsection
