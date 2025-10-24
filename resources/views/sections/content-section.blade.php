<section class="space">
    <div class="container">
        <div class="row">
            <div class="col-xl-9">
                @foreach($section->tabs as $tab)
                <h2 class="sec-title has-line">
                    {{ $tab['title'] }}
                </h2>

                @php
                $topGridPosts = $tab['content']->take(6);
                $featuredPost = $tab['content']->skip(6)->take(1)->first();
                $bottomGridPosts = $tab['content']->skip(7);
                @endphp

                @if ($topGridPosts->isNotEmpty())
                <div class="row gy-4">
                    @foreach ($topGridPosts as $post)
                    <div class="col-lg-4 col-sm-6">
                        <div class="blog-style1">
                            <div class="blog-img">

                                @include('partials.image')

                                @include('partials.category', ['post' => $post])

                            </div>

                            <h3 class="box-title-20">

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
                @endif

                @if ($featuredPost)
                <div class="space-top">
                    <div class="dark-theme">
                        <div class="blog-style3">
                            <div class="blog-img">

                                @include('partials.image')

                            </div>

                            <div class="blog-content">

                                @include('partials.category', ['post' => $featuredPost])

                                <h3 class="box-title-30">

                                    @include('partials.title', ['post' => $featuredPost])

                                </h3>

                                <div class="blog-meta">

                                    @include('partials.author', ['post' => $featuredPost])

                                    @include('partials.date', ['post' => $featuredPost])

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if ($bottomGridPosts->isNotEmpty())
                <div class="mt-30">
                    <div class="row gy-4">
                        @foreach ($bottomGridPosts as $post)
                        <div class="col-md-6">
                            <div class="blog-style1">
                                <div class="blog-img">

                                    @include('partials.image')

                                    @include('partials.category', ['post' => $post])

                                </div>

                                <h3 class="box-title-24">

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
                @endif

                @endforeach
            </div>

            @foreach ($section->widgets as $widget)
            @include("widgets.{$widget->slug}", ['widget' => $widget])
            @endforeach
        </div>
    </div>
</section>
