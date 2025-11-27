@extends('components.layouts.app')

@section('og:title')

@section('app')

    <x-sections.category.header />

    <x-partials.breadcrumb />

    @yield('category')

    <x-sections.category.footer />

@endsection
