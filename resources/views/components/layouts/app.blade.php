@extends('components.layouts.base')

@section('title')

@section('base')

<x-sections.preloader />

@livewire('search.web')

<x-sections.side-menu />

@yield('app')

@endsection
