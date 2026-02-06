@extends('components.layouts.page')

@section('title', $seoTitle)

@section('page')

    @include('sections.blog-list', ['posts' => $posts])

@endsection
