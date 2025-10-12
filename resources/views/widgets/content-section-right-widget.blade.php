<div class="mb-10 col-xl-3 mt-35 mt-xl-0 sidebar-wrap">
    <div class="sidebar-area">
        @foreach ($widget->tabs as $tab)
        <h2 class="sec-title has-line">{{ $tab['title'] }}</h2>
        <div class="row gy-4">
            @foreach ($tab['content'] as $post)
            <div class="col-xl-12 col-md-6 border-blog">
                <div class="blog-style2">
                    <div class="blog-img">

                        @include('partials.image')

                    </div>

                    <div class="blog-content">

                        @include('partials.category', ['post' => $post])


                        <h3 class="box-title-18">

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
        @endforeach
    </div>
</div>
