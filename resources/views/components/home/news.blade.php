@props(['byNews'])

@foreach($byNews as $category)
@if($category->allPosts->isNotEmpty())
<section class="space">
    <div class="container">
        <h2 class="sec-title has-line">
            {{ __("News") }}
        </h2>

        <div class="row">
            <div class="col-xl-3">
                <div class="row gy-4">
                    @foreach($category->allPosts->skip(1)->take(2) as $post)
                    <div class="col-xl-12 col-sm-6">
                        <div class="blog-style1">
                            <div class="blog-img">

                                @include('partials.image', ['post' => $post])

                                @include('partials.category', ['post' => $post])

                            </div>

                            <h3 class="box-title-22">

                                <a href="{{ route('posts.show', ['slug' => $post->slug_path ]) }}" class="hover-line">
                                    {{ Str::limit($post->title, 40) }}
                                </a>

                            </h3>

                            <div class="blog-meta">

                                @include('partials.author', ['post' => $post])

                                @include('partials.date', ['post' => $post])

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 col-xl-9 mt-xl-0">
                @php
                $featuredBlog = $category->allPosts->first();
                @endphp

                @if($featuredBlog)
                <div class="dark-theme space-40">
                    <div class="blog-style3">
                        <div class="blog-img">

                            @include('partials.image', ['post' => $featuredBlog])

                        </div>
                        <div class="blog-content">

                            @include('partials.category', ['post' => $featuredBlog])

                            <h3 class="box-title-40">

                                <a href="{{ route('posts.show', ['slug' => $featuredBlog->slug_path ]) }}" class="hover-line">
                                    {{ Str::limit($featuredBlog->title, 50) }}
                                </a>

                            </h3>

                            <div class="blog-meta">

                                @include('partials.author', ['post' => $featuredBlog])

                                @include('partials.date', ['post' => $featuredBlog])

                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
@endforeach
