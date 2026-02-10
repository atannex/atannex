@php
use Illuminate\Support\Str;

$segments = request()->segments();
$total = count($segments);
$maxVisible = 3;
$limit = 10;
$baseUrl = url('/');
@endphp

<div class="breadcumb-wrapper">
    <div class="container">
        <ul class="breadcumb-menu">

            @if (!empty($global['headerRegion']))
            <li>
                <a href="{{ route('page.index', ['slug' => $global['headerRegion']->slug]) }}">
                    {{ $global['headerRegion']->name }}
                </a>
            </li>
            @endif

            @foreach ($segments as $index => $segment)
            @php
            $isLast = $index === $total - 1;

            if ($total > $maxVisible && $index > 1 && $index < $total - 2) { if ($index===2) { echo '<li class="ellipsis">…</li>' ; } continue; } $baseUrl .='/' . $segment; $label=ucwords(str_replace('-', ' ' , $segment)); $text=$isLast ? Str::limit($label, $limit) : $label; @endphp <li title="{{ $label }}">
                @if ($isLast)
                {{ $text }}
                @else
                <a href="{{ $baseUrl }}">{{ $text }}</a>
                @endif
                </li>
                @endforeach

        </ul>
    </div>
</div>
