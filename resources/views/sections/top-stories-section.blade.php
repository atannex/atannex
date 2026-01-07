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
                                <a href="{{ route('page.index', ['slug' => $post->slug_path ]) }}">
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ config('app.name') }}" class="img-fluid top-stories-left-sidebar">
                                </a>
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

            <div class="mt-4 col-xl-6 mt-xl-0">
                <div class="dark-theme img-overlay2">
                    <div class="blog-style3">
                        <div class="blog-img">
                            <a href="{{ route('page.index', ['slug' => $latestPost->slug_path ]) }}">
                                <img src="{{ asset('storage/' . $latestPost->image) }}" alt="{{ config('app.name') }}" class="img-fluid top-stories-main-center">
                            </a>
                        </div>

                        <div class="blog-content">

                            @include('partials.category', ['post' => $latestPost])

                            <h3 class="box-title-30">
                                @if($latestPost->category)
                                <a href="{{ route('page.index', ['slug' => $latestPost->slug_path ]) }}" class="hover-line">
                                    {{ Str::limit($latestPost->title, 50) }}
                                </a>
                                @else
                                <span>{{ Str::limit($latestPost->title, 50) }}</span>
                                @endif
                            </h3>

                            <div class="blog-meta">
                                <a href="{{ route('page.index', $latestPost->author->user->slug) }}" title="{{ Str::lower($latestPost->author->user->name) }}">
                                    <img src="{{ $latestPost->author->user->image
                                            ? asset('storage/' . $latestPost->author->user->image)
                                            : asset('logo.jpg') }}" alt="{{ Str::lower($latestPost->author->user->name) }}" class="author-avatar">
                                    {{ Str::limit(Str::lower($latestPost->author->user->name), 20) }}
                                </a>

                                @include('partials.date', ['post' => $latestPost])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            @foreach ($section->widgets as $widget)
            @include("widgets.{$widget->slug}", ['widget' => $widget])
            @endforeach

        </div>
    </div>
</section>
