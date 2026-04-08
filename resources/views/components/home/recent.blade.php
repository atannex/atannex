@props(['byRecent'])

@if($byRecent->isNotEmpty())
<div class="mb-2 th-hero-wrapper hero-1" id="hero" style="position:relative; overflow:visible;">

    <div class="hero-slider-1 th-carousel" data-fade="true" data-slide-show="1" data-md-slide-show="1" data-adaptive-height="false">

        @foreach($byRecent as $post)
        <div class="th-hero-slide" style="
                min-height:520px;
                position:relative;
                display:flex;
                align-items:center;
                overflow:visible;">

            <div class="th-hero-bg" style="
                    position:absolute;
                    inset:0;
                    background-size:cover;
                    background-position:center;
                    overflow:hidden; " data-overlay="black" data-opacity="6" data-bg-src="{{ asset('storage/' . $post->image) }}">
            </div>

            <div class="container" style="position:relative; z-index:2;">
                <div class="blog-bg-style1" style="max-width:720px; overflow:visible;">

                    @if($post->category)
                    <a href="{{ route('categories.show', $post->category->slug_path) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}" data-ani="slideinup" data-ani-delay="0.1s">
                        {{ $post->category->name }}
                    </a>
                    @endif

                    <h3 class="box-title-50" style="
                            display:-webkit-box;
                            -webkit-line-clamp:2;
                            -webkit-box-orient:vertical;
                            overflow:hidden;
                            line-height:1.4;
                            padding-bottom:0.25em;
                        " data-ani="slideinup" data-ani-delay="0.3s">

                        @include('partials.title', ['post' => $post])

                    </h3>

                    <div class="blog-meta" style="margin-bottom:12px;" data-ani="slideinup" data-ani-delay="0.5s">

                        @include('partials.author', ['post' => $post])

                        @include('partials.date', ['post' => $post])

                    </div>

                    <a href="{{ route('posts.show', ['slug' => $post->slug_path]) }}" style="text-decoration:none;">

                        <p class="blog-text" style="
                                display:-webkit-box;
                                -webkit-line-clamp:3;
                                -webkit-box-orient:vertical;
                                overflow:hidden;
                                line-height:1.5;
                                padding-bottom:0.2em; " data-ani="slideinup" data-ani-delay="0.7s">

                            {{ Str::limit(strip_tags($post->description), 70) }}

                        </p>
                    </a>

                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4 hero-tab-area">
        <div class="container">
            <div class="hero-tab" data-asnavfor=".hero-slider-1">

                @foreach($byRecent as $index => $post)
                <div class="tab-btn {{ $index === 0 ? 'active' : '' }}">

                    <a href="javascript:void(0)">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ config('app.name') }}" class="img-fluid home-hero">
                    </a>

                </div>
                @endforeach

            </div>
        </div>
    </div>

</div>
@endif
