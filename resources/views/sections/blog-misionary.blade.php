<section class="space-top space-extra-bottom">
    <div class="container">
        <div class="row">
            <div class="col-xxl-9 col-lg-8">
                <div class="row gy-30 filter-active">
                    @foreach($posts as $post)
                    <div class="filter-item col-xl-4 col-sm-6">
                        <div class="blog-style1">
                            <div class="blog-img">

                                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ config('app.name') }}">

                                <a data-theme-color="{{ \App\Models\Others\Color::randomHex() }}" href="{{ route('page.index', $post->category->slug_path) }}" class="category">

                                    {{ $post->category->name }}

                                </a>
                            </div>
                            <h3 class="box-title-24">
                                <a class="hover-line" href="#">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            <div class="blog-meta">
                                <a href="{{ route('page.index', $post->author->user->slug ) }}">
                                    <i class="far fa-user"></i>
                                    {{ __(" By - ") . $post->author->user->name }}
                                </a>
                                <a href="#">
                                    <i class="fal fa-calendar-days"></i>
                                    {{ $post->published_at->format('d M, Y') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-xxl-3 col-lg-4 sidebar-wrap">
                @include('partials.aside')
            </div>
        </div>
    </div>
</section>
