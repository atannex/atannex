<section class="space">
    <div class="container">
        <div class="row">

            <div class="mb-4 col-xl-6 mb-xl-0">
                <div class="row gy-4">
                    @php
                    $firstTab = $section->tabs[0];
                    $latestPost = collect($firstTab['content'] ?? $firstTab['entities'][0]['posts'])->first();
                    @endphp
                    @if($latestPost)
                    <div class="dark-theme img-overlay2">

                        @include('components.posts.post-card', ['post' => $latestPost])

                    </div>
                    @endif
                </div>
            </div>

            <div class="col-xl-6">
                <div class="row gy-4">
                    @php
                    $allPosts = collect($section->tabs)
                    ->flatMap(function ($tab) {
                    if (!empty($tab['content'])) {
                    return $tab['content'];
                    }
                    return collect($tab['entities'])->flatMap(fn($region) => $region['posts']);
                    })
                    ->skip(1)
                    ->take(4);
                    @endphp

                    @foreach($allPosts as $post)
                    <div class="col-xl-6 col-md-6 dark-theme img-overlay2">

                        @include('components.posts.post-card', ['post' => $post])

                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
