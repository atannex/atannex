<section class="space">
    <div class="container">
        @foreach ($section->tabs as $tab)
        <div class="row">
            <div class="col-xl-9">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="sec-title has-line">{{ $tab['title'] }}</h2>
                    </div>
                    <div class="col-auto">
                        <div class="sec-btn">
                            <div class="filter-menu filter-menu-active">
                                <button data-filter="*" class="tab-btn active" type="button">ALL</button>
                                @foreach ($tab['entities'] as $region)
                                    <button data-filter=".cat-region-{{ $region['id'] }}" class="tab-btn" type="button">
                                        {{ $region['name'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-active">
                    @foreach ($tab['entities'] as $region)
                        @foreach ($region['posts'] as $post)
                            <div class="border-blog2 filter-item cat-region-{{ $region['id'] }}">
                                <div class="blog-style4">
                                    <div class="blog-img">
                                        <img class="img-fluid large-carousel-image" src="{{ asset('storage/' . $post->image) }}" alt="{{ config('app.name') }}">
                                    </div>
                                    <div class="blog-content">
                                        <a href="{{ route('page.index', $post->category->slug_path) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
                                            {{ $post->category->name }}
                                        </a>
                                        <h3 class="box-title-24">
                                            <a class="hover-line" href="">
                                                {{ Str::limit($post->title, 100) }}
                                            </a>
                                        </h3>
                                        <p class="blog-text">
                                            {{ Str::limit($post->description, 200) }}
                                        </p>
                                        <div class="blog-meta">
                                            <a href="">
                                                <i class="far fa-user"></i>
                                                {{ __("By - ") . $post->author->user->name }}
                                            </a>
                                            <a href="">
                                                <i class="fal fa-calendar-days"></i>
                                                {{ $post->published_at->format('d M, Y') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            <div class="col-xl-3 mt-35 mt-xl-0 mb-10 sidebar-wrap">
                <div class="sidebar-area">
                    @foreach ($section->widgets as $widget)
                        @include("widgets.{$widget->slug}", ['widget' => $widget])
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
