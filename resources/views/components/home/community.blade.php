@props(['byCommunity'])

@foreach($byCommunity as $category)
@if($category->allPosts->isNotEmpty())
<div class="mb-5">
    <h2 class="mt-5 sec-title has-line">
        {{ $category->name }}
    </h2>

    <div class="mbn-24">
        @foreach($category->allPosts as $blog)
        <div class="mb-4">
            <div class="blog-style4">
                <div class="blog-img w-270">

                    @include('partials.image', ['post' => $blog])

                </div>

                <div class="blog-content">

                    @include('partials.category', ['post' => $blog])

                    <h3 class="box-title-22">

                        @include('partials.title', ['post' => $blog])

                    </h3>

                    <p class="blog-text">

                        {{ Str::limit(strip_tags($blog->description), 140) }}
                    </p>

                    <div class="blog-meta">

                        @include('partials.author', ['post' => $blog])

                        @include('partials.date', ['post' => $blog])

                    </div>

                    <a href="{{ route('posts.show', ['slug' => $blog->slug_path]) }}" class="th-btn style2">
                        {{ __("Read More") }}
                        <i class="fas fa-arrow-up-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endforeach
