<div class="mb-10 col-xl-4 mt-35 mt-xl-0 sidebar-wrap">
    <div class="sidebar-area">

        @foreach($widget->tabs as $tab)
        <div class="mb-5 widget">

            <h2 class="sec-title fs-20 has-line">
                {{ $tab['title'] }}
            </h2>

            <div class="row gy-4">

                @foreach($tab['content'] as $post)
                <div class="col-xl-12 col-md-6">
                    <div class="blog-style2">

                        <div class="blog-img img-big">

                            @include('partials.image', ['post' => $post])

                        </div>

                        <div class="blog-content">

                            @include('partials.category', ['post' => $post])

                            <h3 class="box-title-20">

                                @include('partials.title', ['post' => $post])

                            </h3>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
