@props(['byHistory'])

@foreach($byHistory as $category)
@if(! $category->allPosts->isEmpty())
<div class="mb-10 col-xl-4 mt-35 mt-xl-0 sidebar-wrap">
    <div class="sidebar-area">
        <div class="widget">
            <h2 class="sec-title fs-20 has-line">
                {{ $category->name }}
            </h2>
            <div class="row gy-4">
                @foreach($category->allPosts as $blog)
                <div class="col-xl-12 col-md-6">
                    <div class="blog-style2">
                        <div class="blog-img img-big">

                            @include('partials.image', ['post' => $blog])

                        </div>
                        <div class="blog-content">

                            @include('partials.category', ['post' => $blog])

                            <h3 class="box-title-20">

                                @include('partials.title', ['post' => $blog])

                            </h3>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
