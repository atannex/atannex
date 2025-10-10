<section class="space">
    <div class="container">
        <div class="row gy-4">

            @foreach ($section->tabs as $tab)

            @php
            $mainPost = $tab['content']->first();
            $sidePosts = $tab['content']->skip(1);
            @endphp

            @if ($mainPost)
            <div class="col-xl-6">
                <div class="dark-theme">
                    <div class="blog-style3">
                        <div class="blog-img">

                            @include('partials.image', [
                            'class' => 'top-stories-main-center',
                            'post' => $mainPost
                            ])

                        </div>

                        <div class="blog-content">

                            @include('partials.category', ['post' => $mainPost])

                            <h3 class="box-title-30">

                                @include('partials.title', ['post' => $mainPost])

                            </h3>

                            <div class="blog-meta">

                                @include('partials.author', ['post' => $mainPost])

                                @include('partials.date', ['post' => $mainPost])

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-xl-6">

                <div class="row gy-4">
                    @foreach ($sidePosts->take(2) as $post)
                    <div class="col-sm-6">
                        <div class="blog-style1">
                            <div class="blog-img">

                                @include('partials.image', [
                                'class' => 'top-stories-small',
                                'post' => $post
                                ])

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

                @if ($sidePosts->count() > 2)

                @php $bottomPost = $sidePosts->skip(2)->first(); @endphp

                @if ($bottomPost)
                <div class="mt-4">
                    <div class="dark-theme">
                        <div class="blog-style3">
                            <div class="blog-img">

                                @include('partials.image', [
                                'class' => 'top-stories-bottom',
                                'post' => $bottomPost
                                ])

                            </div>

                            <div class="blog-content">

                                @include('partials.category', ['post' => $bottomPost])

                                <h3 class="box-title-24">

                                    @include('partials.title', ['post' => $bottomPost])

                                </h3>

                                <div class="blog-meta">

                                    @include('partials.author', ['post' => $bottomPost])

                                    @include('partials.date', ['post' => $bottomPost])

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
