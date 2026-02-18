@extends('components.layouts.base')

@section('title')

@section('base')

@livewire('search.web')

<x-sections.side-menu />

<x-sections.pages.header />

@yield('page')

<x-sections.pages.footer />

@endsection
