@if ($paginator->hasPages())
<div id="pagination" class="mt-40 text-center th-pagination">
    <ul>

        {{-- Previous --}}
        @if ($paginator->onFirstPage() === false)
        <li>
            <a href="{{ $paginator->previousPageUrl() }}#pagination" aria-label="Previous Page">
                <i class="fas fa-arrow-left"></i>
            </a>
        </li>
        @endif

        @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();

        $start = max(1, $current - 2);
        $end = min($last, $current + 2);
        @endphp

        {{-- First page --}}
        @if ($start > 1)
        <li>
            <a href="{{ $paginator->url(1) }}#pagination" @class(['active'=> $current === 1])>
                {{ str_pad(1, 2, '0', STR_PAD_LEFT) }}
            </a>
        </li>

        @if ($start > 2)
        <li><span class="ellipsis">…</span></li>
        @endif
        @endif

        {{-- Dynamic pages --}}
        @for ($page = $start; $page <= $end; $page++) <li>
            <a href="{{ $paginator->url($page) }}#pagination" @class(['active'=> $page === $current])>
                {{ str_pad($page, 2, '0', STR_PAD_LEFT) }}
            </a>
            </li>
            @endfor

            {{-- Last page --}}
            @if ($end < $last) @if ($end < $last - 1) <li><span class="ellipsis">…</span></li>
                @endif

                <li>
                    <a href="{{ $paginator->url($last) }}#pagination" @class(['active'=> $current === $last])>
                        {{ str_pad($last, 2, '0', STR_PAD_LEFT) }}
                    </a>
                </li>
                @endif

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}#pagination" aria-label="Next Page">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </li>
                @endif

    </ul>
</div>
@endif
