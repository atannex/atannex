<div class="breadcumb-wrapper">
    <div class="container">
        <ul class="breadcumb-menu">
            @if($global['home'])
            <li>
                <a href="{{ route('page.index', ['slug' => $global['home']->first()->slug]) }}">
                    {{ __('Home') }}
                </a>
            </li>
            @endif

            @php
            $segments = request()->segments();
            $url = url('/');
            $maxVisible = 3;
            $total = count($segments);
            @endphp

            @foreach($segments as $index => $segment)
            @php
            $url .= '/' . $segment;
            $isLast = $index === $total - 1;
            $display = ucwords(str_replace('-', ' ', $segment));
            @endphp

            @if($total > $maxVisible && $index > 1 && $index < $total - 2 && !$isLast) @if($index===2) <li>…</li>
                @endif
                @continue
                @endif

                <li>
                    @if($isLast)
                    {{ $display }}
                    @else
                    <a href="{{ $url }}" title="{{ $display }}">
                        {{ $display }}
                    </a>
                    @endif
                </li>
                @endforeach
        </ul>
    </div>
</div>
