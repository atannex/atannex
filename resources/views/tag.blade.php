@extends('components.layouts.page')

@section('og:title', $seoTitle)

@section('page')

    @include('sections.blog-list', ['posts' => $posts])

@endsection
