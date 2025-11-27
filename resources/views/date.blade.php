@extends('components.layouts.page')

@section('og:title', $seoTitle)

@section('page')

    <x-partials.breadcrumb />

    @include('sections.category-3-column', ['posts' => $posts])

@endsection
