@extends('components.layouts.base')

@section('title', config('app.name'))

@section('base')

<x-sections.guest.header />

@yield('guest')

<x-sections.guest.footer />

@endsection
