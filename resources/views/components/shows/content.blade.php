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

    @case('image')
    <div class="my-4 py-lg-2">
        <img class="w-100" src="{{ asset('storage/' . $block['data']['src']) }}" alt="image-{{ $block['data']['title'] }}">
    </div>
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

    @case('blockquote')
    <blockquote>
        <p>{{ $block['data']['quote'] }}</p>
        <cite>{{ $block['data']['author'] }}</cite>
    </blockquote>
    @break

    @case('side-by-side')
    <div class="mb-4 row pb-lg-2 pt-xl-2 gy-4">

        <div class="col-md-auto">
            <img class="w-100" src="{{ asset('storage/' . $block['data']['image']) }}" alt="side-by-side-{{ $block['data']['heading'] }}">
        </div>

        <div class="col-md">
            <h3 class="box-title-24">{{ $block['data']['heading'] }}</h3>
            <p>{{ $block['data']['paragraph'] }}</p>

            <div class="blog-inner-list">
                <ul>
                    @foreach ($block['data']['list'] as $listItem)
                    <li>{{ $listItem['value'] }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
    @break

    @endswitch
    @endforeach
</div>
