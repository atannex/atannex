<div class="row gy-4">
    @foreach ($posts as $post)
    <div class="col-xl-12 col-md-6 border-blog">
        <div class="blog-style2">
            <div class="blog-img">

                @include('partials.image', ['post' => $post])

            </div>
            <div class="blog-content">

                @include('partials.category', ['post' => $post])

                <h3 class="box-title-20">

                    @include('partials.title', ['post' => $post])

                </h3>

                <div class="blog-meta">

                    @include('partials.date', ['post' => $post])

                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
