@php
$segments = request()->segments();
$url = url('/');
$maxVisible = 3;
$total = count($segments);
$limit = 10;
@endphp

<div class="breadcumb-wrapper">
    <div class="container">
        <ul class="breadcumb-menu">

            @if($global['mainRegions'])
            <li>
                <a href="{{ route('page.index', ['slug' => $global['mainRegions']->first()->slug]) }}">
                    {{ $global['mainRegions']->first()->name }}
                </a>
            </li>
            @endif

            @foreach($segments as $index => $segment)
            @php
            $url .= '/' . $segment;
            $isLast = $index === $total - 1;
            $label = ucwords(str_replace('-', ' ', $segment));

            if ($total > $maxVisible && $index > 1 && $index < $total - 2) { if ($index===2) echo '<li>…</li>' ; continue; } $output=$isLast ? Str::limit($label, $limit) : $label; @endphp <li title="{{ $label }}">
                @if($isLast)
                {{ $output }}
                @else
                <a href="{{ $url }}">{{ $label }}</a>
                @endif
                </li>
                @endforeach

        </ul>
    </div>
</div>
