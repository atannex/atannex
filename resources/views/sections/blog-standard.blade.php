<section class="th-blog-wrapper space-top space-extra-bottom">
    <div class="container">
        <div class="row">
            <div class="col-xxl-9 col-lg-8">
                @foreach ($posts as $post)
                <div class="th-blog blog-single has-post-thumbnail">

                    <div class="blog-img" data-overlay="black" data-opacity="4">

                        @include('partials.image',['class'=> 'blog-standard'])

                        @include('partials.category')

                    </div>

                    <div class="blog-content">
                        <div class="flex flex-wrap gap-3 blog-meta">

                            @include('partials.author')

                            @include('partials.date')

                        </div>


                        <h3 class="box-title-24">

                            @include('partials.title')

                        </h3>

                        <p class="blog-text">
                            {!! Str::limit($post->description, 150) !!}
                        </p>

                        <a href="{{ route('posts.show', ['slug' => $post->slug_path ]) }}" class="th-btn style2">
                            {{ __("Read More") }}
                            <i class="fas fa-arrow-up-right ms-2"></i>
                        </a>
                    </div>
                </div>
                @endforeach

                <x-partials.pagination :paginator="$posts" />

            </div>

            <div class="col-xxl-3 col-lg-4 sidebar-wrap">

                <aside class="sidebar-area">
                    <div class="widget widget_tag_cloud">

                        @livewire('search.post')

                    </div>

                    @include('partials.aside.category')

                    @include('partials.aside.recent-posts')

                    @include('partials.aside.tag')

                </aside>

            </div>
        </div>
    </div>
</section>
