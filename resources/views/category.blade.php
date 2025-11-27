@extends('components.layouts.category')

@section('og:title', seo_title($category->name))

@section('category')

    @foreach ($category->sections as $section)
        @includeIf("sections.{$section->slug}", ['section' => $section])
    @endforeach

@endsection
