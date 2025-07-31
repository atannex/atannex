<section class="space-top space-extra-bottom">
    <div class="container">
        <div class="row">
            <div class="col-xxl-9 col-lg-8">
                <div class="mb-30">
                    @foreach($posts as $post)
                    <div class="border-blog2">
                        <div class="blog-style4">
                            <div class="blog-img w-386">
                                <a href="#">
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ config('app.name') }}">
                                </a>
                            </div>
                            <div class="blog-content">
                                <a href="{{ route('page.index', $post->category->slug_path) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
                                    {{ $post->category->name }}
                                </a>
                                <h3 class="box-title-30">
                                    <a href="#" class="hover-line">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                <p class="blog-text">
                                    {!! Str::limit($post->description, 200) !!}
                                </p>
                                <div class="blog-meta">
                                    <a href="">
                                        <i class="far fa-user"></i>
                                        {{ __(" By - ") . $post->author->user->name }}
                                    </a>
                                    <a href="#">
                                        <i class="fal fa-calendar-days"></i>
                                        {{ $post->published_at->format('d M, Y') }}
                                    </a>
                                </div>
                                <a href="#" class="th-btn style2">
                                    {{ __("Read More") }}
                                    <i class="fas fa-arrow-up-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <x-partials.pagination :paginator="$posts" />
            </div>

            <div class="col-xxl-3 col-lg-4 sidebar-wrap">
                @include('partials.aside')
            </div>
        </div>
    </div>
</section>
