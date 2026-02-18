@php
use Illuminate\Support\Str;

$segments = request()->segments();
$baseUrl = url('/');
$maxVisible = 3; // Maximum visible segments (excluding region)
$limit = 10;
$total = count($segments);

$breadcrumbs = [];
$currentUrl = $baseUrl;

foreach ($segments as $segment) {
$currentUrl .= '/' . $segment;

$breadcrumbs[] = [
'label' => ucwords(str_replace('-', ' ', $segment)),
'url' => $currentUrl,
];
}
@endphp

<div class="breadcumb-wrapper">
    <div class="container">
        <ul class="breadcumb-menu">

            {{-- Optional Header Region --}}
            @if(!empty($global['headerRegion']))
            <li>
                <a href="{{ route('page.index', ['slug' => $global['headerRegion']->slug]) }}">
                    {{ $global['headerRegion']->name }}
                </a>
            </li>
            @endif

            @foreach($breadcrumbs as $index => $crumb)
            @php
            $isLast = $index === $total - 1;
            @endphp

            {{-- Collapse middle breadcrumbs if too many --}}
            @if($total > $maxVisible && $index > 0 && $index < $total - 1) @if($index===1) <li>…</li>
                @endif
                @continue
                @endif

                <li title="{{ $crumb['label'] }}">
                    @if($isLast)
                    {{ Str::limit($crumb['label'], $limit) }}
                    @else
                    <a href="{{ $crumb['url'] }}">
                        {{ $crumb['label'] }}
                    </a>
                    @endif
                </li>
                @endforeach

        </ul>
    </div>
</div>
