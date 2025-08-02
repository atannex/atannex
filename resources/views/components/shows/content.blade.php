<div class="content">
    @foreach ($moduleContent as $section)
    @switch($section['type'])

    @case('paragraphs')
    @foreach ($section['content'] as $paragraph)
    <p>{{ $paragraph }}</p>
    @endforeach
    @break

    @case('ad-banner')
    <div class="my-4 py-lg-2">
        <a href="{{ $section['href'] }}">
            <img class="light-img w-100" src="{{ asset($section['images']['light']) }}" alt="Advertisement">
            <img class="dark-img w-100" src="{{ asset($section['images']['dark']) }}" alt="Advertisement">
        </a>
    </div>
    @break

    @case('image')
    <div class="my-4 py-lg-2">
        <img class="w-100" src="{{ asset($section['src']) }}" alt="Blog Image">
    </div>
    @break

    @case('heading')
    <h3 class="h4">{{ $section['title'] }}</h3>
    @break

    @case('blockquote')
    <blockquote>
        <p>{{ $section['quote'] }}</p>
        <cite>{{ $section['author'] }}</cite>
    </blockquote>
    @break

    @case('side-by-side')
    <div class="mb-4 row pb-lg-2 pt-xl-2 gy-4">
        <div class="col-md-auto">
            <div>
                <img class="w-100" src="{{ asset($section['image']) }}" alt="Blog Image">
            </div>
        </div>
        <div class="col-md">
            <h3 class="box-title-24">{{ $section['heading'] }}</h3>
            <p>{{ $section['paragraph'] }}</p>
            <div class="blog-inner-list">
                <ul>
                    @foreach ($section['list'] as $item)
                    <li><b>{{ Str::before($item, ':') }}:</b> {{ Str::after($item, ':') }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @break

    @endswitch
    @endforeach
</div>
