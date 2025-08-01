<section class="space">
    <div class="container">
        <div class="row gy-30">
            @foreach ($posts as $post)
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="blog-style1">
                    <div class="blog-img" data-overlay="black" data-opacity="4">

                        <a href="#">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                        </a>

                        <a href="{{ route('page.index', $post->category->slug_path) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
                            {{ $post->category->name }}
                        </a>
                    </div>

                    <h3 class="box-title-20">
                        <a class="hover-line" href="#">
                            {{ $post->title }}
                        </a>
                    </h3>

                    <div class="blog-meta">
                        <a href="{{ route('page.index', $post->author->user->slug ) }}">
                            <i class="far fa-user"></i> {{ __("By - ") . $post->author->user->name }}
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

        <x-partials.pagination :paginator="$posts" />
    </div>
</section>
