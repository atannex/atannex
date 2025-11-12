<div class="col-xl-3">
    <div class="row gy-4">
        @foreach ($posts as $post)
            <div class="col-xl-12 col-sm-6 border-blog">
                <div class="blog-style1">
                    <div class="blog-img">

                        @include('partials.image', ['post' => $post])

                        @include('partials.category', ['post' => $post])

                    </div>

                    <h3 class="box-title-22">

                        @include('partials.title', ['post' => $post])

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
