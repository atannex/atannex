<div class="content">
    @foreach ($module->content as $block)

    @switch($block['type'])

    @case('paragraphs')
    @if (!empty($block['data']['content']))
    {!! $block['data']['content'] !!}
    @endif
    @break

    @case('heading')
    @php
    $tag = in_array($block['data']['level'], ['h1','h2','h3','h4','h5','h6'])
    ? $block['data']['level']
    : 'h2';
    @endphp

    @if (!empty($block['data']['content']))
    <{{ $tag }} class="heading {{ $tag }}">
        {!! $block['data']['content'] !!}
    </{{ $tag }}>
    @endif
    @break

    @case('image')
    @if (!empty($block['data']['src']))
    <div class="my-4 py-lg-2">
        <img class="w-100" src="{{ asset('storage/' . $block['data']['src']) }}" alt="image-{{ e($block['data']['title'] ?? '') }}">
    </div>
    @endif
    @break

    @case('ad-banner')
    @if (!empty($block['data']['href']) && !empty($block['data']['images']))
    <div class="my-4 py-lg-2">
        <a href="{{ $block['data']['href'] }}">
            @foreach ($block['data']['images'] as $image)
            @if (!empty($image['path']))
            <img class="{{ $image['mode'] ?? '' }}-img w-100" src="{{ asset('storage/' . $image['path']) }}" alt="advertisement-{{ e($block['data']['title'] ?? '') }}">
            @endif
            @endforeach
        </a>
    </div>
    @endif
    @break

    @case('blockquote')
    @if (!empty($block['data']['content']))
    <blockquote>
        <p>{{ $block['data']['content'] }}</p>
        @if (!empty($block['data']['attribution']))
        <cite>{{ $block['data']['attribution'] }}</cite>
        @endif
    </blockquote>
    @endif
    @break

    @case('side-by-side')
    @if (!empty($block['data']['image']) || !empty($block['data']['heading']) || !empty($block['data']['content']))
    <div class="mb-4 row pb-lg-2 pt-xl-2 gy-4">
        @if (!empty($block['data']['image']))
        <div class="col-md-auto">
            <img class="w-100" src="{{ asset('storage/' . $block['data']['image']) }}" alt="side-by-side-{{ e($block['data']['heading'] ?? '') }}">
        </div>
        @endif

        <div class="col-md">
            @if (!empty($block['data']['heading']))
            <h3 class="box-title-24">{{ $block['data']['heading'] }}</h3>
            @endif
            @if (!empty($block['data']['content']))
            <p>{!! $block['data']['content'] !!}</p>
            @endif

            @if (!empty($block['data']['highlights']))
            <ul class="blog-inner-list">
                @foreach ($block['data']['highlights'] as $item)
                @if (!empty($item['text']))
                <li>{!! $item['text'] !!}</li>
                @endif
                @endforeach
            </ul>
            @endif
        </div>
    </div>
    @endif
    @break

    @endswitch

    @endforeach
</div>
