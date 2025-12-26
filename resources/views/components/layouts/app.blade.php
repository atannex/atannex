@extends('components.layouts.base')

@section('og:title')

@section('base')

<x-sections.preloader />

@livewire('search.web')

<x-sections.side-menu />

@yield('app')

@endsection
