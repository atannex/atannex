<div class="content">
    @if(isset($module, $module->content) && is_iterable($module->content))
    @foreach ($module->content as $block)

    @if(!isset($block['type'], $block['data']) || !is_array($block['data']))
    @continue
    @endif

    @switch($block['type'])

    {{-- Paragraphs --}}
    @case('paragraphs')
    @if(!empty($block['data']['content']) && is_array($block['data']['content']))
    @foreach ($block['data']['content'] as $item)
    @if(!empty($item['value']))
    <p>{!! $item['value'] !!}</p>
    @endif
    @endforeach
    @endif
    @break

    {{-- Heading --}}
    @case('heading')
    @if(!empty($block['data']['title']))
    <h3 class="h4">{!! $block['data']['title'] !!}</h3>
    @endif
    @break

    {{-- Ad Banner --}}
    @case('ad-banner')
    @if(
    !empty($block['data']['href']) &&
    !empty($block['data']['images']) &&
    is_array($block['data']['images'])
    )
    <div class="my-4 py-lg-2">
        <a href="{{ $block['data']['href'] }}">
            @foreach ($block['data']['images'] as $image)
            @if(!empty($image['path']) && !empty($image['mode']))
            <img class="{{ $image['mode'] }}-img w-100" src="{{ asset('storage/' . $image['path']) }}" alt="{{ !empty($block['data']['title']) ? 'advertisement-' . e($block['data']['title']) : 'advertisement' }}">
            @endif
            @endforeach
        </a>
    </div>
    @endif
    @break

    {{-- Video --}}
    @case('video')
    @if(
    !empty($block['data']['video']['id']) &&
    !empty($block['data']['cover'])
    )
    <div class="my-4 py-lg-2">
        <div class="yt-wrapper vf-aspect" data-yt-id="{{ $block['data']['video']['id'] }}" role="button" aria-label="Play video">
            <img class="yt-cover" src="{{ asset('storage/' . $block['data']['cover']) }}" alt="Video cover" draggable="false">

            <button class="yt-play-btn vf-play-btn" type="button" aria-label="Play video">
                <i class="fas fa-play" aria-hidden="true"></i>
            </button>
        </div>
    </div>
    @endif
    @break

    {{-- Blockquote --}}
    @case('blockquote')
    @if(!empty($block['data']['quote']))
    <blockquote>
        <p>{{ $block['data']['quote'] }}</p>
        @if(!empty($block['data']['author']))
        <cite>{{ $block['data']['author'] }}</cite>
        @endif
    </blockquote>
    @endif
    @break

    {{-- Video Grid --}}
    @case('video_grid')
    @if(!empty($block['data']['items']) && is_array($block['data']['items']))
    <div class="my-4 row gy-4">
        @foreach ($block['data']['items'] as $item)
        @if(
        !empty($item['video']['id']) &&
        !empty($item['cover'])
        )
        <div class="col-md-6">
            <div class="ytg-wrapper vf-aspect" data-ytg-id="{{ $item['video']['id'] }}" role="button" aria-label="Play video">
                <img class="ytg-cover w-100" src="{{ asset('storage/' . $item['cover']) }}" alt="Video cover" draggable="false">

                <button class="ytg-play-btn vf-play-btn" type="button" aria-label="Play video">
                    <i class="fas fa-play" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endif
    @break

    @endswitch

    @endforeach
    @endif
</div>
