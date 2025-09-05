<div class="content">
    @foreach ($module->module_content as $block)
    @switch($block['type'])

    {{-- Paragraph --}}
    @case('paragraphs')
    @foreach ($block['data']['content'] as $item)
    @if (!empty($item['value']))
    <p>{!! $item['value'] !!}</p>
    @endif
    @endforeach
    @break

    {{-- Heading --}}
    @case('heading')
    @if (!empty($block['data']['title']))
    <h3 class="h4">{!! $block['data']['title'] !!}</h3>
    @endif
    @break

    {{-- Image --}}
    @case('image')
    @if (!empty($block['data']['src']))
    <div class="my-4 py-lg-2">
        <img class="w-100" src="{{ asset('storage/' . $block['data']['src']) }}" @if (!empty($block['data']['title'])) alt="image-{{ $block['data']['title'] }}" @endif>
    </div>
    @endif
    @break

    {{-- Ad Banner --}}
    @case('ad-banner')
    @if (!empty($block['data']['href']) && !empty($block['data']['images']))
    <div class="my-4 py-lg-2">
        <a href="{{ $block['data']['href'] }}">
            @foreach ($block['data']['images'] as $image)
            @if (!empty($image['path']))
            <img class="{{ $image['mode'] ?? 'default' }}-img w-100" src="{{ asset('storage/' . $image['path']) }}" @if (!empty($block['data']['title'])) alt="advertisement-{{ $block['data']['title'] }}" @endif>
            @endif
            @endforeach
        </a>
    </div>
    @endif
    @break

    {{-- Blockquote --}}
    @case('blockquote')
    @if (!empty($block['data']['quote']) || !empty($block['data']['author']))
    <blockquote>
        @if (!empty($block['data']['quote']))
        <p>{{ $block['data']['quote'] }}</p>
        @endif
        @if (!empty($block['data']['author']))
        <cite>{{ $block['data']['author'] }}</cite>
        @endif
    </blockquote>
    @endif
    @break

    {{-- Side-by-side --}}
    @case('side-by-side')
    @if (!empty($block['data']['image']) || !empty($block['data']['heading']) || !empty($block['data']['paragraph']))
    <div class="mb-4 row pb-lg-2 pt-xl-2 gy-4">

        @if (!empty($block['data']['image']))
        <div class="col-md-auto">
            <img class="w-100" src="{{ asset('storage/' . $block['data']['image']) }}" @if (!empty($block['data']['heading'])) alt="side-by-side-{{ $block['data']['heading'] }}" @endif>
        </div>
        @endif

        <div class="col-md">
            @if (!empty($block['data']['heading']))
            <h3 class="box-title-24">{{ $block['data']['heading'] }}</h3>
            @endif

            @if (!empty($block['data']['paragraph']))
            <p>{{ $block['data']['paragraph'] }}</p>
            @endif

            @if (!empty($block['data']['list']))
            <div class="blog-inner-list">
                <ul>
                    @foreach ($block['data']['list'] as $listItem)
                    @if (!empty($listItem['value']))
                    <li>{{ $listItem['value'] }}</li>
                    @endif
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    @endif
    @break

    @endswitch
    @endforeach
</div>
