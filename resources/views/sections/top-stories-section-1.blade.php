<section class="space">
    <div class="container">
        <div class="row">
            @php
            $posts = get_posts_from_tabs($section->tabs);
            $latestPost = $posts->first();
            $nextPosts = $posts->skip(1)->take(4);
            @endphp

            <div class="mb-4 col-xl-6 mb-xl-0">
                <div class="row gy-4">
                    @if($latestPost)
                    <div class="dark-theme img-overlay2">

                        @include('components.posts.post-card', ['post' => $latestPost])

                    </div>
                    @endif
                </div>
            </div>

            <div class="col-xl-6">
                <div class="row gy-4">
                    @foreach($nextPosts as $post)
                    <div class="col-xl-6 col-md-6 dark-theme img-overlay2">

                        @include('components.posts.post-card', ['post' => $post])

                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
