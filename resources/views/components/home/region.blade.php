@props(['byRegion'])

@if($byRegion->isNotEmpty())
<section class="space">
    <div class="container">

        <div class="row align-items-center">
            <div class="col">
                <h2 class="sec-title has-line">Tech News</h2>
            </div>

            <div class="col-auto">
                <div class="sec-btn">
                    <div class="filter-menu filter-menu-active">
                        <button data-filter="*" class="tab-btn active" type="button">
                            {{ __('ALL') }}
                        </button>

                        @php $catIndex = 1; @endphp

                        @foreach($byRegion as $region)

                        @if(is_null($region->parent_id) && $region->allPosts->isNotEmpty())

                        <button data-filter=".cat{{ $catIndex }}" class="tab-btn" type="button">
                            {{ $region->name }}
                        </button>

                        @php $catIndex++; @endphp

                        @endif
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-24 filter-active mbn-24">

            @php $isFirstFeatured = true; @endphp

            @foreach($byRegion as $region)

            @if(is_null($region->parent_id) && $region->allPosts->isNotEmpty())

            @php

            static $catIndex = 1;

            @endphp

            @foreach($region->allPosts as $post)

            <div class="col-xl-4 col-md-6 filter-item cat{{ $catIndex }}">

                @if($isFirstFeatured)

                @php $isFirstFeatured = false; @endphp

                <div class="blog-style3 dark-theme">
                    <div class="blog-img">
                        @include('partials.image', ['post' => $post])
                    </div>

                    <div class="blog-content">
                        @if($post->region)
                        <a href="{{ route('page.index', ['slug' => $post->region->slug_path]) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
                            {{ $post->region->name }}
                        </a>
                        @endif

                        <h3 class="box-title-24">
                            @include('partials.title', ['post' => $post])
                        </h3>

                        <div class="blog-meta">
                            @include('partials.author', ['post' => $post])
                            @include('partials.date', ['post' => $post])
                        </div>
                    </div>
                </div>

                @else

                <div class="blog-style2">
                    <div class="blog-img img-big">
                        @include('partials.image', ['post' => $post])
                    </div>

                    <div class="blog-content">
                        @if($post->region)
                        <a href="{{ route('page.index', ['slug' => $post->region->slug_path]) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
                            {{ $post->region->name }}
                        </a>
                        @endif

                        <h3 class="box-title-20">
                            @include('partials.title', ['post' => $post])
                        </h3>

                        <div class="blog-meta">
                            @include('partials.date', ['post' => $post])
                        </div>
                    </div>
                </div>

                @endif
            </div>
            @endforeach

            @php $catIndex++; @endphp

            @endif
            @endforeach

        </div>
    </div>
</section>
@endif
