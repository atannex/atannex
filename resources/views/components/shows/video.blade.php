<div class="content">
    @foreach ($module->content as $block)

    @switch($block['type'])

    @case('paragraphs')
    @foreach ($block['data']['content'] as $item)
    <p>{!! $item['value'] !!}</p>
    @endforeach
    @break

    @case('heading')
    <h3 class="h4">{!! $block['data']['title'] !!}</h3>
    @break

    @case('ad-banner')
    <div class="my-4 py-lg-2">
        <a href="{{ $block['data']['href'] }}">
            @foreach ($block['data']['images'] as $image)
            <img class="{{ $image['mode'] }}-img w-100" src="{{ asset('storage/' . $image['path']) }}" alt="advertisement-{{ $block['data']['title'] }}">
            @endforeach
        </a>
    </div>
    @break

    @case('video')
    <div class="my-4 py-lg-2">
        <div class="yt-wrapper vf-aspect" data-yt-id="{{ $block['data']['video']['id'] }}" role="button" aria-label="Play video">
            <img class="yt-cover" src="{{ asset('storage/' . $block['data']['cover']) }}" alt="Video cover" draggable="false">

            <button class="yt-play-btn vf-play-btn" type="button" aria-label="Play video">
                <i class="fas fa-play" aria-hidden="true"></i>
            </button>
        </div>
    </div>
    @break

    @case('blockquote')
    <blockquote>
        <p>{{ $block['data']['quote'] }}</p>
        <cite>{{ $block['data']['author'] }}</cite>
    </blockquote>
    @break

    @case('video_grid')
    <div class="my-4 row gy-4">
        @foreach ($block['data']['items'] as $item)
        <div class="col-md-6">
            <div class="ytg-wrapper vf-aspect" data-ytg-id="{{ $item['video']['id'] }}" role="button" aria-label="Play video">
                <img class="ytg-cover w-100" src="{{ asset('storage/' . $item['cover']) }}" alt="Video cover" draggable="false">

                <button class="ytg-play-btn vf-play-btn" type="button" aria-label="Play video">
                    <i class="fas fa-play" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @break

    @endswitch

    @endforeach
</div>
