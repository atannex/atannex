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

                    $display = $isLast
                        ? ucwords(str_replace('-', ' ', $segment))
                        : implode('', array_map(fn($word) => strtoupper(substr($word, 0, 1)), explode('-', $segment)));
                @endphp

                @if($total > $maxVisible && $index > 1 && $index < $total - 2 && !$isLast)
                    @if($index === 2)
                        <li>…</li>
                    @endif
                    @continue
                @endif

                <li>
                    @if($isLast)
                        {{ $display }}
                    @else
                        <a href="{{ $url }}" title="{{ ucwords(str_replace('-', ' ', $segment)) }}">
                            {{ $display }}
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
