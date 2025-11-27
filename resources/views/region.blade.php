@extends('components.layouts.page')

@section('og:title', $seoTitle)

@section('page')

    @foreach ($region->sections as $section)
        @include("sections.{$section->slug}", ['section' => $section])
    @endforeach

@endsection
