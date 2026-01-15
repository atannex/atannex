<div class="content">
    @foreach ($module['content'] as $block)

    @switch($block['type'])

    @case('paragraphs')
    @if (!empty($block['data']['content']) && is_array($block['data']['content']))
    @foreach ($block['data']['content'] as $item)
    {!! $item['value'] !!}
    @endforeach
    @endif
    @break

    @case('heading')
    @if (!empty($block['data']['content']))
    @php($level = $block['data']['level'] ?? 'h2')

    <{{ array_key_exists($level, $headingLevels) ? $level : 'h2' }} class="heading {{ array_key_exists($level, $headingLevels) ? $level : 'h2' }}">
        {!! $block['data']['content'] !!}
    </{{ array_key_exists($level, $headingLevels) ? $level : 'h2' }}>
    @endif
    @break

     @yield('content')

    @case('image')
    @if (!empty($block['data']['src']))
    <figure class="my-4 py-lg-2">
        <img src="{{ asset('storage/' . $block['data']['src']) }}" alt="{{ e($block['data']['alt'] ?? '') }}" class="w-100 img-fluid" loading="lazy">

        @if (!empty($block['data']['caption']))
        <figcaption class="mt-2 text-muted small">
            {{ e($block['data']['caption']) }}
        </figcaption>
        @endif
    </figure>
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
            <img src="{{ asset('storage/' . $block['data']['image']) }}" alt="side-by-side-{{ e($block['data']['heading'] ?? '') }}" class="img-fluid" style="width:306px; height:auto; aspect-ratio:306/320; object-fit:cover;">
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
