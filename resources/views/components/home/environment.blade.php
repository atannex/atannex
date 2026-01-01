@props(['byEnvironment'])

@isset($byEnvironment)
@if($byEnvironment->isNotEmpty() && $byEnvironment[0]->allPosts->isNotEmpty())
@php
$posts = $byEnvironment[0]->allPosts;
$featured = $posts->first();
$others = $posts->skip(1);
@endphp

<div class="mb-5">

    <h2 class="sec-title has-line">{{ __('Environment') }}</h2>

    <div class="mb-4">
        <div class="dark-theme img-overlay2 space-40">
            <div class="blog-style3">
                <div class="blog-img">

                    @include('partials.image', ['post' => $featured])

                </div>
                <div class="blog-content">

                    @include('partials.category', ['post' => $featured])

                    <h3 class="box-title-40">

                        @include('partials.title', ['post' => $featured])

                    </h3>
                    <div class="blog-meta"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4">
        @forelse($others as $post)
        <div class="col-md-6">
            <div class="blog-style2">
                <div class="blog-img img-big">

                    @include('partials.image', ['post' => $post])

                </div>
                <div class="blog-content">

                    @include('partials.category', ['post' => $post])

                    <h3 class="box-title-20">

                        @include('partials.title', ['post' => $post])

                    </h3>
                    <div class="blog-meta">

                        @include('partials.date', ['post' => $post])

                    </div>
                </div>
            </div>
        </div>
        @empty
        <p class="text-muted">{{ __('No additional posts available.') }}</p>
        @endforelse
    </div>
</div>
@endif
@endisset
