@extends('components.layouts.base')

@section('title')

@section('base')

@livewire('search.web')

<x-sections.side-menu />

@yield('app')

@endsection
