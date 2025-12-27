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

    {{-- Image --}}
    @case('image')
    @if(!empty($block['data']['src']))
    <div class="my-4 py-lg-2">
        <img class="w-100" src="{{ asset('storage/' . $block['data']['src']) }}" alt="{{ !empty($block['data']['title']) ? 'image-' . e($block['data']['title']) : 'image' }}">
    </div>
    @endif
    @break

    {{-- Ad Banner --}}
    @case('ad-banner')
    @if(!empty($block['data']['href']) && !empty($block['data']['images']) && is_array($block['data']['images']))
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

    {{-- Side by Side --}}
    @case('side-by-side')
    @if(
    !empty($block['data']['image']) &&
    !empty($block['data']['heading']) &&
    !empty($block['data']['paragraph'])
    )
    <div class="mb-4 row pb-lg-2 pt-xl-2 gy-4">

        <div class="col-md-auto">
            <img class="w-100" src="{{ asset('storage/' . $block['data']['image']) }}" alt="side-by-side-{{ e($block['data']['heading']) }}">
        </div>

        <div class="col-md">
            <h3 class="box-title-24">{{ $block['data']['heading'] }}</h3>
            <p>{{ $block['data']['paragraph'] }}</p>

            @if(!empty($block['data']['list']) && is_array($block['data']['list']))
            <div class="blog-inner-list">
                <ul>
                    @foreach ($block['data']['list'] as $listItem)
                    @if(!empty($listItem['value']))
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
    @endif
</div>
