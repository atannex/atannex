<section class="space">
    <div class="container">
        @foreach ($section->tabs as $tab)
        <div class="mb-4 row align-items-center">
            <div class="col">
                <h2 class="sec-title has-line">
                    {{ $tab['title'] }}
                </h2>
            </div>
            <div class="col-auto">
                <div class="sec-btn">
                    <div class="filter-menu filter-menu-active">

                        <button data-filter="*" class="tab-btn active" type="button">
                            {{ __('ALL') }}
                        </button>

                        @foreach ($tab['content'] as $region)
                        <button data-filter=".cat-region-{{ $region['id'] }}" class="tab-btn" type="button">
                            {{ $region['name'] }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row gy-30 filter-active">
            @foreach ($tab['content'] as $region)
            @foreach ($region['posts'] as $post)
            <div class="col-lg-6 two-column filter-item cat-region-{{ $region['id'] }}">
                <div class="blog-style4">

                    <div class="blog-img">

                        @include('partials.image', ['post' => $post])
                    </div>

                    <div class="blog-content">

                        @include('partials.category', ['post' => $post])

                        <h3 class="box-title-22">

                            @include('partials.title', ['post' => $post])

                        </h3>

                        <div class="blog-meta">

                            @include('partials.author', ['post' => $post])

                            @include('partials.date', ['post' => $post])

                        </div>

                        <a href="{{ route('posts.show', ['slug' => $post->slug_path]) }}" class="th-btn style2">
                            {{ __('Read More') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
            @endforeach
        </div>
        @endforeach
    </div>
</section>
