@extends('components.layouts.base')

@section('title')

@section('base')

<x-sections.guest.header />

@yield('guest')

<x-sections.guest.footer />

@endsection
