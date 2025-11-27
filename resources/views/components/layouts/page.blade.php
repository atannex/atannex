@extends('components.layouts.base')

@section('base')

    <x-sections.preloader />

    @livewire('search.web')

    <x-sections.side-menu />

    <livewire:forms.subscription />

    <x-sections.pages.header />

    @yield('page')

    <x-sections.pages.footer />

@endsection
