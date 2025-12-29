@props(['regions'])

@if($regions->isNotEmpty())
<section class="space">
    <div class="container">

        <div class="row align-items-center">
            <div class="col">
                <h2 class="sec-title has-line">
                    {{ __("Updates Per Division") }}
                </h2>
            </div>
            <div class="col-auto">
                <div class="sec-btn">
                    <div class="filter-menu filter-menu-active">
                        <button data-filter="*" class="tab-btn active" type="button">
                            {{ __('ALL') }}
                        </button>
                        @foreach($regions as $index => $region)
                        @if($region->posts->count() > 0)
                        <button data-filter=".cat{{ $index+1 }}" class="tab-btn" type="button">
                            {{ $region->name }}
                        </button>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row gy-24 filter-active mbn-24">

            @foreach($regions as $index => $region)
            @if($region->allPosts->isNotEmpty())
            @foreach($region->allPosts as $post)

            <div class="col-xl-4 col-md-6 filter-item cat{{ $index+1 }}">
                <div class="blog-style2">
                    <div class="blog-img img-big">

                        @include('partials.image', ['post' => $post])

                    </div>
                    <div class="blog-content">

                        @foreach ($post->regions as $postRegion)
                        <a href="{{ route('page.index', ['slug' => $postRegion->slug_path]) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
                            {{ $postRegion->name }}
                        </a>
                        @endforeach

                        <h3 class="box-title-20">

                            @include('partials.title', ['post' => $post])

                        </h3>

                        <div class="blog-meta">

                            @include('partials.date', ['post' => $post])

                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif
