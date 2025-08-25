<div>
    <div class="container">
        <div class="news-area">

            @foreach($section->tabs as $tab)

            <div class="title">{{ $tab['title'] }}:</div>

            @endforeach

            <div class="news-wrap">
                <div class="row slick-marquee">

                    @foreach($tab['content'] as $post)

                    <div class="col-auto">
                        <a href="{{ route('page.index', ['slug' => $post->slug_path ]) }}" class="breaking-news">

                            {{ $post->title }}

                        </a>
                    </div>

                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
