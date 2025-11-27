@extends('components.layouts.base')

@section('og:title')

@section('base')

<x-sections.preloader />

@livewire('search.web')

<x-sections.side-menu />

<livewire:forms.subscription />

@yield('app')

@endsection
