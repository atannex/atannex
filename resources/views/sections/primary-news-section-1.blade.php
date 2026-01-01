<section class="space">
    <div class="container">
        <div class="row">
            <div class="col-xl-8">
                @foreach ($section->tabs as $tab)
                <h2 class="sec-title has-line">
                    {{ $tab['title'] }}
                </h2>
                <div class="row gy-4">
                    @foreach($tab['content'] as $post)
                    <div class="col-sm-6 border-blog two-column">
                        <div class="blog-style1">
                            <div class="blog-img">

                                @include('partials.image',['class'=> 'primary-news-section-widget'])

                                @include('partials.category')

                            </div>

                            <h3 class="box-title-24">

                                <a href="{{ route('page.index', ['slug' => $post->slug_path ]) }}" class="hover-line">
                                    {{ Str::limit($post->title, 60) }}
                                </a>
                            </h3>

                            <div class="blog-meta">

                                @include('partials.author')

                                @include('partials.date')
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>

            @foreach ($section->widgets as $widget)
            @include("widgets.{$widget->slug}", ['widget' => $widget])
            @endforeach
        </div>
    </div>
</section>
