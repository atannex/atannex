@extends('components.layouts.guest')

@section('title', seo_title($seoTitle))

@section('guest')

<x-partials.breadcrumb />

<section class="space-top space-extra-bottom">
    <div class="container">
        <div class="row">

            <div class="col-xxl-9 col-lg-8">
                <div class="mb-30">

                    <div class="border-blog2">
                        <div class="blog-style4">
                            <div class="blog-content">

                                {{-- TITLE --}}
                                <h3 class="box-title-30">
                                    <a class="hover-line" href="{{ route('documents.show', $module->document->slug_path ?? '#') }}">
                                        {{ $module->document->title ?? 'Untitled' }}
                                    </a>
                                </h3>

                                {{-- DESCRIPTION --}}
                                <p class="blog-text">
                                    {!! $module->document->description ?? '' !!}
                                </p>

                                {{-- MODULE CONTENT --}}
                                <div class="blog-meta">
                                    {!! $module->content !!}
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- SIDEBAR --}}
            @include('documents.aside')

        </div>
    </div>
</section>

@endsection
