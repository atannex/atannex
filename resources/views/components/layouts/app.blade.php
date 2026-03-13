@extends('components.layouts.base')

@section('title', config('app.name'))

@section('base')

@livewire('search.web')

<x-sections.side-menu />

@yield('app')

@endsection
