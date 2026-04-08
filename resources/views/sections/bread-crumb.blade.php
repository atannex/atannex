<div class="container">
    <div class="news-area">
        @foreach($section->tabs as $tab)
        @if(!empty($tab['content']) && count($tab['content']) > 1)
        <div class="title">{{ $tab['title'] }}:</div>
        <div class="news-wrap">
            <div class="row slick-marquee">
                @foreach($tab['content'] as $post)
                <div class="col-auto">
                    <a href="{{ route('posts.show', ['slug' => $post->slug_path ]) }}" class="breaking-news">
                        {{ $post->title }}
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>
