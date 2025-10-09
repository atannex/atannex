<section class="space">
    <div class="container">
        <div class="row">

            @foreach ($section->tabs as $tab)
            @php
            $latestPost = $tab['content']->first();
            $otherPosts = $tab['content']->skip(1);
            @endphp

            <div class="col-xl-3">
                <div class="row gy-4">
                    @foreach ($otherPosts as $post)
                    <div class="col-xl-12 col-sm-6 border-blog dark-theme img-overlay2">
                        <div class="blog-style3">
                            <div class="blog-img">

                                @include('partials.image', [
                                'class' => 'top-stories-left-sidebar',
                                'post' => $post
                                ])

                            </div>

                            <div class="blog-content">

                                @include('partials.category', ['post' => $post])

                                <h3 class="box-title-22">

                                    @include('partials.title', ['post' => $post])

                                </h3>

                                <div class="blog-meta">

                                    @include('partials.author', ['post' => $post])

                                    @include('partials.date', ['post' => $post])

                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @if ($latestPost)
            <div class="mt-4 col-xl-6 mt-xl-0">
                <div class="dark-theme img-overlay2">
                    <div class="blog-style3">
                        <div class="blog-img">

                            @include('partials.image', [
                            'class' => 'top-stories-main-center',
                            'post' => $latestPost
                            ])

                        </div>

                        <div class="blog-content">

                            @include('partials.category', ['post' => $latestPost])

                            <h3 class="box-title-30">

                                @include('partials.title', ['post' => $latestPost])

                            </h3>

                            <div class="blog-meta">

                                @include('partials.author', ['post' => $latestPost])

                                @include('partials.date', ['post' => $latestPost])

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach

            @foreach ($section->widgets as $widget)
            @include("widgets.{$widget->slug}", ['widget' => $widget])
            @endforeach

        </div>
    </div>
</section>
