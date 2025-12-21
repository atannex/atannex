<section class="space-top space-extra-bottom">
    <div class="container">
        <div class="row">
            <div class="col-xxl-9 col-lg-8">
                <div class="mb-30">
                    @foreach($posts as $post)
                    <div class="border-blog2">
                        <div class="blog-style4">
                            <div class="blog-img w-386">

                                @include('partials.image',['class'=> 'blog-list'])

                            </div>
                            <div class="blog-content">

                                @include('partials.category')

                                <h3 class="box-title-30">

                                    @include('partials.title')

                                </h3>
                                <p class="blog-text">
                                    {!! Str::limit($post->description, 200) !!}
                                </p>
                                <div class="blog-meta">

                                    @include('partials.author')

                                    @include('partials.date')
                                </div>
                                <a href="{{ route('page.index', $post->slug_path)}}" class="th-btn style2">
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
