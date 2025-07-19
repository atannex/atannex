<div class="breadcumb-wrapper">
    <div class="container">
        <ul class="breadcumb-menu">
            <li>
                @if($global['home'])
                <a href="{{ route('page.index', ['slug' => $global['home']->first()->slug]) }}">
                    {{ __('Home') }}
                </a>
                @endif
            </li>

            @php
            $segments = request()->segments();
            $url = url('/');
            @endphp

            @foreach ($segments as $index => $segment)
            @php
            $url .= '/' . $segment;
            $name = ucwords(str_replace('-', ' ', $segment));
            @endphp

            @if ($index == count($segments) - 1)
            <li>{{ $name }}</li>
            @else
            <li><a href="{{ $url }}">{{ $name }}</a></li>
            @endif
            @endforeach
        </ul>
    </div>
</div>
